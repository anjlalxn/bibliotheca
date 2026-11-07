<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    private const LOAN_DAYS = 10;

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $borrowings = Borrowing::with(['member', 'book'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('member', function ($m) use ($search) {
                        $m->where('full_name', 'like', "%{$search}%")
                          ->orWhere('member_code', 'like', "%{$search}%");
                    })->orWhereHas('book', function ($b) use ($search) {
                        $b->where('title', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByDesc('borrowing_id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'active'        => Borrowing::whereNull('return_date')->count(),
            'borrowedToday' => Borrowing::whereDate('borrowing_date', today())->count(),
            'overdue'       => Borrowing::whereNull('return_date')
                                        ->whereDate('due_date', '<', now())
                                        ->count(),
            'returnedToday' => Borrowing::whereDate('return_date', today())->count(),
            'available'     => Book::sum('available_quantity'),
        ];

        return view('borrowing.index', compact('borrowings', 'stats'));
    }

    public function create()
    {
        return view('borrowing.create', [
            'members'  => Member::orderBy('full_name')->get(),
            'books'    => Book::with('category')->orderBy('title')->get(),
            'loanDays' => self::LOAN_DAYS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'      => 'required|exists:members,member_id',
            'book_id'        => 'required|exists:books,book_id',
            'borrowing_date' => 'required|date',
            'due_date'       => 'required|date|after_or_equal:borrowing_date',
        ], [
            'member_id.required' => 'Please pick a member from the suggestions.',
            'book_id.required'   => 'Please pick a book from the suggestions.',
        ]);

        // Both changes succeed together or neither is saved
        $borrowing = DB::transaction(function () use ($data) {
            $book = Book::lockForUpdate()->findOrFail($data['book_id']);

            if ($book->available_quantity < 1) {
                throw ValidationException::withMessages([
                    'book_id' => 'No copies of this book are available.',
                ]);
            }

            $borrowing = Borrowing::create([
                'book_id'        => $book->book_id,
                'member_id'      => $data['member_id'],
                // login isn't working yet, so fall back to the first user
                'user_id'        => Auth::id() ?? User::query()->value('user_id'),                
                'borrowing_date' => $data['borrowing_date'],
                'due_date'       => $data['due_date'],
                'status'         => 'borrowed',
            ]);

            $book->decrement('available_quantity');

            return $borrowing;
        });

        return redirect()
            ->route('borrowing.show', $borrowing->borrowing_id)
            ->with('created', true);
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'book.category']);

        return view('borrowing.show', compact('borrowing'));
    }
}