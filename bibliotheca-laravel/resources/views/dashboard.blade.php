@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome, admin</p>

    <!-- Stat cards -->
    <div class="stat-panel">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3">
            <div class="col">
                <a href="#" class="stat-card stat-card--teal">
                    <i class="bi bi-bookshelf stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Total Books (Copies)</p>
                        <p class="stat-value">{{ number_format($stats['total']) }}</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
            <div class="col">
                <a href="#" class="stat-card stat-card--cyan">
                    <i class="bi bi-check-square stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Available Books</p>
                        <p class="stat-value">{{ number_format($stats['available']) }}</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
            <div class="col">
                <a href="#" class="stat-card stat-card--light">
                    <i class="bi bi-clock stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Active Borrowings</p>
                        <p class="stat-value">{{ number_format($stats['borrowed']) }}</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
            <div class="col">
                <a href="#" class="stat-card stat-card--black">
                    <i class="bi bi-exclamation-circle stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Overdue Books</p>
                        <p class="stat-value">{{ number_format($stats['overdue']) }}</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
            <div class="col">
                <a href="#" class="stat-card stat-card--dark-gray">
                    <i class="bi bi-envelope-open stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Transaction Details</p>
                        <p class="stat-value">View a transaction</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
            <div class="col">
                <a href="#" class="stat-card stat-card--gray">
                    <i class="bi bi-clock-history stat-icon"></i>
                    <div class="stat-body">
                        <p class="stat-label">Borrow History</p>
                        <p class="stat-value">View history</p>
                    </div>
                    <i class="bi bi-chevron-right stat-arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- All Books -->
    <section class="section-block">
        <div class="d-flex flex-column-reverse flex-sm-row justify-content-between align-items-sm-center gap-3">
            <form class="search-group input-group" action="{{ route('dashboard') }}" method="GET" role="search">
                <input type="search" width="500px" class="form-control" name="search"
                    value="{{ $search }}"
                    placeholder="Author/Category/Book" aria-label="Search books">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </form>
            <h2 class="section-title">All Books</h2>
        </div>

        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Category</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Publisher</th>
                        <th scope="col">Year</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Avail</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->book_id }}</td>
                            <td>{{ $book->category?->category_name ?? '-' }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->publisher }}</td>
                                    
                            @if ($book->publication_year== 0)
                                    <td>Unknown Year</td>
                                    @else
                                    <td>{{ $book->publication_year }}</td>
                                    @endif

                            <td>{{ $book->quantity }}</td>
                            <td>{{ $book->available_quantity }}</td>
                            <td>
                                @if ($book->available_quantity > 0)
                                    <span class="badge text-bg-success">Available</span>
                                @else
                                    <span class="badge text-bg-danger">Unavailable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="table-empty">
                                <i class="bi bi-inbox"></i>
                                @if ($search !== '')
                                    No author, category, or books match "{{ $search }}".
                                @else
                                    No books to display yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Page links -->
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    </section>
@endsection