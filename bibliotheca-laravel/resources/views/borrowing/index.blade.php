@extends('layouts.panel')

@section('title', 'Borrowing')
@section('heading', 'Borrowing')

@section('content')
<div class="stat-panel">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3">
        <div class="col">
            <div class="stat-card stat-card--teal">
                <i class="bi bi-journal-text stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Active Borrowings</p>
                    <p class="stat-value">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--cyan">
                <i class="bi bi-check-square stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Borrowed Today</p>
                    <p class="stat-value">{{ $stats['borrowedToday'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--black">
                <i class="bi bi-exclamation-circle stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Overdue</p>
                    <p class="stat-value">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--light-gray">
                <i class="bi bi-arrow-counterclockwise stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Returned Today</p>
                    <p class="stat-value">{{ $stats['returnedToday'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('books') }}" class="stat-card stat-card--gray">
                <i class="bi bi-book stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Available Books</p>
                    <p class="stat-value">{{ $stats['available'] }}</p>
                </div>
                <i class="bi bi-chevron-right stat-arrow"></i>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('borrowing.create') }}" class="stat-card stat-card--outline">
                <i class="bi bi-envelope-open stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">New Borrow Transaction</p>
                    <p class="stat-value">Create</p>
                </div>
                <i class="bi bi-chevron-right stat-arrow"></i>
            </a>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="d-flex flex-column-reverse flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div class="search-toolbar d-flex align-items-center gap-2">
            <form class="search-group input-group" action="{{ route('borrowing') }}" method="GET" role="search">
                <input type="search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search..." aria-label="Search borrowings">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </form>
            <button class="btn btn-filter" type="button" aria-label="Filter">
                <i class="bi bi-filter"></i>
            </button>
        </div>
        <div class="text-sm-end">
            <h2 class="section-title">Borrowing</h2>
            <p class="section-subtitle">Member Transactions</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th scope="col">Member</th>
                    <th scope="col">Book</th>
                    <th scope="col">Date Borrowed</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowings as $b)
                    @php
                        $badge = ['Returned' => 'success', 'Overdue' => 'danger', 'Borrowed' => 'info'][$b->display_status];

                        // Reminder email (opens a Gmail draft) for this borrowing
                        $memberEmail = $b->member->email ?? null;
                        $bookTitle   = $b->book->title ?? 'Unknown book';
                        $dueText     = $b->due_date?->format('F j, Y') ?? '-';
                        $isOverdue   = $b->display_status === 'Overdue';

                        $remindSubject = $isOverdue ? 'Bibliotheca | Overdue book notice' : 'Bibliotheca | Reminder: your book is due soon';
                        $remindBody = "Hello " . ($b->member->full_name ?? '') . ",\n\n"
                            . ($isOverdue
                                ? "The book \"{$bookTitle}\" was due on {$dueText} and has not been returned yet. Please return it as soon as you can."
                                : "This is a reminder that the book \"{$bookTitle}\" is due on {$dueText}. Please return it on or before that date.")
                            . "\n\nThank you.
                           ";

                        $remindLink = $memberEmail
                            ? 'https://mail.google.com/mail/?view=cm&to=' . rawurlencode($memberEmail)
                                . '&su=' . rawurlencode($remindSubject)
                                . '&body=' . rawurlencode($remindBody)
                            : '#';
                    @endphp
                    <tr>
                        <td>
                            {{ $b->member->full_name ?? '—' }}
                            <div class="text-muted small">{{ $b->member->member_code ?? '' }}</div>
                        </td>
                        <td>{{ $b->book->title ?? '—' }}</td>
                        <td>{{ $b->borrowing_date?->format('M d, Y') }}</td>
                        <td>{{ $b->due_date?->format('M d, Y') }}</td>
                        <td><span class="badge text-bg-{{ $badge }}">{{ $b->display_status }}</span></td>
                        <td>
                            <a href="{{ route('borrowing.show', $b->borrowing_id) }}"
                               class="btn btn-sm btn-outline-primary" aria-label="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if ($b->display_status !== 'Returned')
                                <a href="{{ $remindLink }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-warning remind-bell {{ $remindLink === '#' ? 'disabled' : '' }}"
                                   data-key="reminded-borrowing-{{ $b->borrowing_id }}-{{ $b->display_status }}"                                   title="{{ $memberEmail ? 'Email a reminder to the member' : 'This member has no email' }}"
                                   aria-label="Send reminder">
                                    <i class="bi bi-bell"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">
                            <i class="bi bi-inbox"></i>
                            No borrowing records yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $borrowings->links() }}
</section>


@endsection