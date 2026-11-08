@extends('layouts.panel')

@section('title', 'Return')
@section('heading', 'Return')

@section('content')
@php
    // Popup message (success or error), if any
    $popupSuccess = session('return_success');
    $popupError   = session('return_error');
@endphp

<div class="stat-panel">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3">
        <div class="col">
            <div class="stat-card stat-card--teal">
                <i class="bi bi-calendar-event stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Due Today</p>
                    <p class="stat-value">{{ $stats['dueToday'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--cyan">
                <i class="bi bi-check-square stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Returned Today</p>
                    <p class="stat-value">{{ $stats['returnedToday'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--light">
                <i class="bi bi-clock stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Active Borrowings</p>
                    <p class="stat-value">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card stat-card--black">
                <i class="bi bi-exclamation-circle stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Overdue Books</p>
                    <p class="stat-value">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('books') }}" class="stat-card stat-card--dark-gray">
                <i class="bi bi-inbox stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Locate Book</p>
                    <p class="stat-value">Track status</p>
                </div>
                <i class="bi bi-chevron-right stat-arrow"></i>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('borrowing') }}" class="stat-card stat-card--gray">
                <i class="bi bi-clock-history stat-icon"></i>
                <div class="stat-body">
                    <p class="stat-label">Check Borrows</p>
                    <p class="stat-value">View history</p>
                </div>
                <i class="bi bi-chevron-right stat-arrow"></i>
            </a>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="d-flex flex-column-reverse flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div class="search-toolbar d-flex align-items-center gap-2">
            <form class="search-group input-group" action="{{ route('return') }}" method="GET" role="search">
                <input type="search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search..." aria-label="Search returns">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </form>

            <button class="btn btn-filter" type="button" data-bs-toggle="modal"
                    data-bs-target="#filterModal" aria-label="Filter">
                <i class="bi bi-filter"></i>
            </button>

            @if (request()->hasAny(['status', 'from', 'to']))
                <a href="{{ route('return', array_filter(['search' => request('search')])) }}"
                   class="btn btn-sm btn-outline-secondary">Clear filters</a>
            @endif
        </div>
        <div class="text-sm-end">
            <h2 class="section-title">Returns</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th scope="col">Member</th>
                    <th scope="col">Book</th>
                    <th scope="col">Borrow Date</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Return Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowings as $b)
                    @php
                        $badge = ['Returned' => 'success', 'Overdue' => 'danger', 'Borrowed' => 'info'][$b->display_status];
                    @endphp
                    <tr>
                        <td>
                            {{ $b->member->full_name ?? '—' }}
                            <div class="text-muted small">{{ $b->member->member_code ?? '' }}</div>
                        </td>
                        <td>{{ $b->book->title ?? '—' }}</td>
                        <td>{{ $b->borrowing_date?->format('M d, Y') }}</td>
                        <td>{{ $b->due_date?->format('M d, Y') }}</td>
                        <td>{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                        <td>
                            <span class="badge text-bg-{{ $badge }}">{{ $b->display_status }}</span>
                            @if ($b->display_status === 'Overdue')
                                <div class="text-danger small">{{ (int) $b->due_date->diffInDays(today()) }} day(s) late</div>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            @unless ($b->return_date)
                                <button type="button"
                                        class="btn btn-sm btn-success return-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmReturn"
                                        data-url="{{ route('return.store', $b->borrowing_id) }}"
                                        data-book="{{ $b->book->title ?? '—' }}"
                                        data-member="{{ $b->member->full_name ?? '—' }}">
                                    Return
                                </button>
                            @endunless
                            <a href="{{ route('borrowing.show', $b->borrowing_id) }}"
                               class="btn btn-sm btn-outline-primary" aria-label="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">
                            <i class="bi bi-inbox"></i>
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $borrowings->links() }}
    </div>
</section>

{{-- Filter modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="GET" action="{{ route('return') }}" class="modal-content p-3">
            <h5 class="mb-3">Filter returns</h5>

            {{-- keep the current search when filtering --}}
            <input type="hidden" name="search" value="{{ request('search') }}">

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="borrowed"  @selected(request('status') === 'borrowed')>Borrowed (not yet due)</option>
                    <option value="due_today" @selected(request('status') === 'due_today')>Due today</option>
                    <option value="overdue"   @selected(request('status') === 'overdue')>Overdue</option>
                    <option value="returned"  @selected(request('status') === 'returned')>Returned</option>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Borrowed from</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-6">
                    <label class="form-label">Borrowed to</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button class="btn btn-info text-white">Apply</button>
                <a href="{{ route('return') }}" class="btn btn-secondary">Reset</a>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection