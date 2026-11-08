<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    // List + search
    public function index(Request $request)
    {
        $members = Member::withCount('borrowings')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('member_code', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('member_id')
            ->paginate(10)
            ->withQueryString();

        $totalMembers = Member::count();

        return view('members.index', compact('members', 'totalMembers'));
    }

    // Member info + borrowed books
    public function show(Member $member)
    {
        $borrowings = $member->borrowings()
            ->with('book')
            ->orderByDesc('borrowing_date')
            ->get();

        $stats = [
            'total'    => $borrowings->count(),
            'active'   => $borrowings->where('display_status', 'Borrowed')->count(),
            'overdue'  => $borrowings->where('display_status', 'Overdue')->count(),
            'returned' => $borrowings->where('display_status', 'Returned')->count(),
        ];

        return view('members.show', compact('member', 'borrowings', 'stats'));
    }

    public function store(Request $request)
    {
        // No member_code here: it is generated automatically
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:members,email'],
            'status'    => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Retry a few times in case two requests pick the same code at once
        $member = null;

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                $member = Member::create($data + [
                    'member_code' => $this->nextMemberCode(),
                ]);
                break;
            } catch (QueryException $e) {
                if ($attempt === 2) {
                    throw $e;
                }
            }
        }

        return redirect()->route('members')
            ->with('members_success', "Member registered successfully. Code: {$member->member_code}");
    }

    public function update(Request $request, Member $member)
    {
        // member_code is not editable, so it is not validated or saved here
        $request->validateWithBag('editMember', [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('members', 'email')->ignore($member->member_id, 'member_id'),
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Cannot deactivate a member who still has borrowed or overdue books
        if ($request->status === 'inactive' && $member->status !== 'inactive') {
            $hasOpenBorrowings = $member->borrowings()
                ->get()
                ->contains(fn ($b) => in_array($b->display_status, ['Borrowed', 'Overdue']));

            if ($hasOpenBorrowings) {
                return redirect()->route('members')
                    ->with('members_error', 'Cannot set this member to inactive because they still have borrowed or overdue books.');
            }
        }

        $member->update($request->only('full_name', 'email', 'status'));

        return redirect()->route('members')
            ->with('members_success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        // Keep the borrowing history intact
        if ($member->borrowings()->exists()) {
            return redirect()->route('members')
                ->with('members_error', 'Cannot delete this member because they have borrowing records.');
        }

        $member->delete();

        return redirect()->route('members')
            ->with('members_success', 'Member deleted successfully.');
    }

    // Builds the next code: MC-0001, MC-0002, ...
    private function nextMemberCode(): string
    {
        $next = ((int) Member::max('member_id')) + 1;

        return 'MC-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}