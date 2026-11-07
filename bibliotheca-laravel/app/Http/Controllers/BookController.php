<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $books = Book::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('publisher', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($c) use ($search) {
                          $c->where('category_name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('book_id')
            ->paginate(15)
            ->withQueryString();

        return view('books.index', [
            'books'      => $books,
            'categories' => Category::orderBy('category_name')->get(),
            'totalBooks' => Book::sum('quantity'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,category_id',
            'quantity'         => 'required|integer|min:1',
            'status'           => 'required|string',
            'publisher'        => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        ]);

        $data['available_quantity'] = $data['quantity'];
        Book::create($data);

        return redirect()->route('books')->with('success', 'Book added.');
    }

    public function destroy(Book $book)
    {
        if ($book->borrowings()->exists()) {
            return back()->with('error', 'This book has borrowing records and cannot be deleted.');
        }

        $book->delete();
        return back()->with('success', 'Book deleted.');
    }
}