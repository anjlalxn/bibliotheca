@php
    // True when the last submit was a failed EDIT, so the add form
    // doesn't get pre-filled with the edit form's old input.
    $editFailed = $errors->editBook->any();

    // Filter keys that need to survive a search submit
    $filterKeys = ['category_id', 'status', 'year_from', 'year_to', 'sort'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books | Bibliotheca</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Momo+Trust+Display&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">

    <style>
        /* Book preview panel cover */
        .bk-cover {
            width: 170px;
            aspect-ratio: 2 / 3;
            margin: 0 auto 1.5rem;
            border-radius: .5rem;
            overflow: hidden;
            background: #e9f7fb;
        }

        .bk-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .bk-cover-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.75rem;
            color: #0dcaf0;
        }

        .bk-cover.is-loading .bk-cover-fallback {
            animation: bk-pulse 1.2s ease-in-out infinite;
        }

        @keyframes bk-pulse {
            50% {
                opacity: .45;
            }
        }

        /* Filter button badge */
        .btn-filter {
            position: relative;
        }

        .filter-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 1.25rem;
            height: 1.25rem;
            padding: 0 .3rem;
            border-radius: 999px;
            background: #dc3545;
            color: #fff;
            font-size: .7rem;
            font-weight: 600;
            line-height: 1.25rem;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="d-md-flex">

        <!-- Sidebar -->
        <aside class="sidebar offcanvas-md offcanvas-start d-flex flex-column"
               tabindex="-1"
               id="sidebar"
               aria-labelledby="sidebarLabel">

            <div class="offcanvas-header d-md-none">
                <h5 class="offcanvas-title visually-hidden" id="sidebarLabel">
                    Menu
                </h5>

                <button type="button"
                        class="btn-close btn-close-white ms-auto"
                        data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebar"
                        aria-label="Close">
                </button>
            </div>

            <div class="offcanvas-body d-flex flex-column">

                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Bibliotheca"
                         class="img-fluid">
                </div>

                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="nav-link">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('books') }}"
                           class="nav-link active"
                           aria-current="page">
                            <i class="bi bi-book"></i> Books
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/borrowing') }}" class="nav-link">
                            <i class="bi bi-clock"></i> Borrowing
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/return') }}" class="nav-link">
                            <i class="bi bi-arrow-repeat"></i> Return
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/members') }}" class="nav-link">
                            <i class="bi bi-person-circle"></i> Members
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/categories') }}" class="nav-link">
                            <i class="bi bi-tags"></i> Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-graph-up"></i> Analytics
                        </a>
                    </li>

                </ul>

                <div class="sidebar-footer mt-auto">

                    <div class="sidebar-user d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i> Administrator
                    </div>

                    <button type="button" class="btn btn-logout w-100">
                        Logout
                    </button>

                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100">

            <!-- Mobile Header -->
            <header class="mobile-topbar d-flex d-md-none align-items-center justify-content-between">

                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Bibliotheca"
                         class="img-fluid">
                </div>

                <button class="btn btn-menu"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebar"
                        aria-controls="sidebar"
                        aria-label="Open menu">

                    <i class="bi bi-list"></i>

                </button>

            </header>

            <main class="main-content">

                <h1 class="page-title page-title--spaced">
                    Books
                </h1>

                <!-- Session Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show"
                         role="alert">

                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show"
                         role="alert">

                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ session('error') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>
                    </div>
                @endif

                <!-- Stat Cards -->
                <div class="stat-panel">

                    <div class="row row-cols-1 row-cols-sm-2 g-3">

                        <div class="col">

                            <div class="stat-card stat-card--teal">

                                <i class="bi bi-book stat-icon"></i>

                                <div class="stat-body">
                                    <p class="stat-label">Total Books</p>
                                    <p class="stat-value">{{ number_format($totalBooks) }}</p>
                                </div>

                            </div>

                        </div>

                        <div class="col">

                            <a href="#"
                               class="stat-card stat-card--cyan"
                               data-bs-toggle="modal"
                               data-bs-target="#addBook">

                                <i class="bi bi-plus-circle stat-icon"></i>

                                <div class="stat-body">
                                    <p class="stat-label">Action</p>
                                    <p class="stat-value">Add New Book</p>
                                </div>

                                <i class="bi bi-chevron-right stat-arrow"></i>

                            </a>

                        </div>

                    </div>
                </div>

                <!-- All Books -->
                <section class="section-block">

                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-between align-items-sm-center gap-3">

                        <div class="search-toolbar d-flex align-items-center gap-2">

                            <form class="search-group input-group"
                                  action="{{ route('books') }}"
                                  method="GET"
                                  role="search">

                                {{-- Keep active filters when searching --}}
                                @foreach ($filterKeys as $key)
                                    @if (request()->filled($key))
                                        <input type="hidden"
                                               name="{{ $key }}"
                                               value="{{ request($key) }}">
                                    @endif
                                @endforeach

                                <input type="search"
                                       class="form-control"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Author/Category/Book"
                                       aria-label="Search books">

                                <button class="btn btn-info text-white" type="submit">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>

                            </form>

                            <button class="btn btn-filter"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#filterBooks"
                                    aria-label="Filter">

                                <i class="bi bi-filter"></i>

                                @if ($activeFilterCount > 0)
                                    <span class="filter-badge">{{ $activeFilterCount }}</span>
                                @endif

                            </button>

                        </div>

                        <div class="text-sm-end">
                            <h2 class="section-title">All Books</h2>
                        </div>

                    </div>

                    <!-- Active filter summary -->
                    @if ($activeFilterCount > 0)

                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-muted">

                            <span>
                                <i class="bi bi-funnel me-1"></i>
                                {{ $books->total() }}
                                {{ \Illuminate\Support\Str::plural('result', $books->total()) }}
                                with {{ $activeFilterCount }}
                                {{ \Illuminate\Support\Str::plural('filter', $activeFilterCount) }}
                                applied
                            </span>

                            <a href="{{ route('books', array_filter(['search' => request('search')])) }}"
                               class="link-danger text-decoration-none">
                                <i class="bi bi-x-circle me-1"></i>Clear filters
                            </a>

                        </div>

                    @endif

                    <!-- Books Table -->
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
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($books as $book)

                                    @php
                                        $isAvailable = $book->available_quantity > 0
                                            && strtolower((string) $book->status) === 'available';

                                        $year = $book->publication_year ?: 'Unknown Year';
                                    @endphp

                                    <tr>

                                        <td>BOOK-{{ $book->book_id }}</td>

                                        <td>
                                            {{ $book->category->category_name ?? '—' }}
                                        </td>

                                        <td>{{ $book->title }}</td>

                                        <td>{{ $book->author }}</td>

                                        <td>{{ $book->publisher ?: '—' }}</td>

                                        <td>{{ $year }}</td>

                                        <td>{{ $book->quantity }}</td>

                                        <td>{{ $book->available_quantity }}</td>

                                        <td>

                                            @if ($isAvailable)
                                                <span class="badge text-bg-success">
                                                    Available
                                                </span>
                                            @else
                                                <span class="badge text-bg-danger">
                                                    Unavailable
                                                </span>
                                            @endif

                                        </td>

                                        <td class="text-nowrap">

                                            <!-- View Book -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary view-btn"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#viewBook"
                                                    data-id="BOOK-{{ $book->book_id }}"
                                                    data-title="{{ $book->title }}"
                                                    data-author="{{ $book->author }}"
                                                    data-publisher="{{ $book->publisher }}"
                                                    data-year="{{ $year }}"
                                                    data-qty="{{ $book->quantity }}"
                                                    data-category="{{ $book->category->category_name ?? '—' }}"
                                                    data-status="{{ $isAvailable ? 'Available' : 'Unavailable' }} ({{ $book->available_quantity }} copies)"
                                                    aria-label="View book">

                                                <i class="bi bi-eye"></i>

                                            </button>

                                            <!-- Edit Book -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBook"
                                                    data-book-id="{{ $book->book_id }}"
                                                    data-url="{{ route('books.update', $book->book_id) }}"
                                                    data-title="{{ $book->title }}"
                                                    data-author="{{ $book->author }}"
                                                    data-category-id="{{ $book->category_id }}"
                                                    data-publisher="{{ $book->publisher }}"
                                                    data-year="{{ $book->publication_year }}"
                                                    data-qty="{{ $book->quantity }}"
                                                    data-status="{{ strtolower((string) $book->status) }}"
                                                    data-borrowed="{{ max(0, $book->quantity - $book->available_quantity) }}"
                                                    aria-label="Edit book">

                                                <i class="bi bi-pencil-square"></i>

                                            </button>

                                            <!-- Delete Book -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteBook"
                                                    data-title="{{ $book->title }}"
                                                    data-url="{{ route('books.destroy', $book->book_id) }}"
                                                    aria-label="Delete book">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="10" class="table-empty">

                                            <i class="bi bi-inbox"></i>

                                            @if (request('search') || $activeFilterCount > 0)

                                                No books match your
                                                @if (request('search'))
                                                    search "{{ request('search') }}"
                                                    @if ($activeFilterCount > 0) and @endif
                                                @endif
                                                @if ($activeFilterCount > 0)
                                                    filters
                                                @endif.

                                            @else

                                                No books to display yet.

                                            @endif

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $books->links() }}
                    </div>

                </section>

            </main>
        </div>
    </div>

    <!-- ====================================== -->
    <!-- FILTER BOOKS MODAL                     -->
    <!-- ====================================== -->

    <div class="modal fade"
         id="filterBooks"
         tabindex="-1"
         aria-labelledby="filterBooksLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <form method="GET"
                  action="{{ route('books') }}"
                  class="modal-content p-3">

                {{-- Keep the current search term when filtering --}}
                @if (request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-header border-0">

                    <div>
                        <h5 class="modal-title fw-semibold" id="filterBooksLabel">
                            Filter Books
                        </h5>

                        <small class="text-muted">
                            Narrow down the list. Filters work together with search.
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- Category -->
                        <div class="col-12">

                            <label for="filter_category_id" class="form-label">
                                Category
                            </label>

                            <select name="category_id"
                                    id="filter_category_id"
                                    class="form-select">

                                <option value="">All categories</option>

                                @foreach ($categories as $c)

                                    <option value="{{ $c->category_id }}"
                                        @selected($filters['category_id'] === (int) $c->category_id)>

                                        {{ $c->category_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- Status -->
                        <div class="col-12">

                            <label for="filter_status" class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    id="filter_status"
                                    class="form-select">

                                <option value="">Any status</option>

                                <option value="available"
                                    @selected($filters['status'] === 'available')>
                                    Available (copies on the shelf)
                                </option>

                                <option value="unavailable"
                                    @selected($filters['status'] === 'unavailable')>
                                    Unavailable (none on the shelf)
                                </option>

                            </select>

                        </div>

                        <!-- Publication Year Range -->
                        <div class="col-6">

                            <label for="filter_year_from" class="form-label">
                                Year from
                            </label>

                            <input type="number"
                                   name="year_from"
                                   id="filter_year_from"
                                   value="{{ $filters['year_from'] }}"
                                   class="form-control"
                                   placeholder="e.g. 1990"
                                   min="1000"
                                   max="{{ date('Y') }}"
                                   step="1">

                        </div>

                        <div class="col-6">

                            <label for="filter_year_to" class="form-label">
                                Year to
                            </label>

                            <input type="number"
                                   name="year_to"
                                   id="filter_year_to"
                                   value="{{ $filters['year_to'] }}"
                                   class="form-control"
                                   placeholder="e.g. {{ date('Y') }}"
                                   min="1000"
                                   max="{{ date('Y') }}"
                                   step="1">

                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Books with an unknown publication year are
                                hidden while a year range is set.
                            </small>
                        </div>

                        <!-- Sort -->
                        <div class="col-12">

                            <label for="filter_sort" class="form-label">
                                Sort by
                            </label>

                            <select name="sort"
                                    id="filter_sort"
                                    class="form-select">

                                <option value="id"
                                    @selected($filters['sort'] === 'id')>
                                    Book ID (default)
                                </option>

                                <option value="title_asc"
                                    @selected($filters['sort'] === 'title_asc')>
                                    Title (A → Z)
                                </option>

                                <option value="title_desc"
                                    @selected($filters['sort'] === 'title_desc')>
                                    Title (Z → A)
                                </option>

                                <option value="author_asc"
                                    @selected($filters['sort'] === 'author_asc')>
                                    Author (A → Z)
                                </option>

                                <option value="year_desc"
                                    @selected($filters['sort'] === 'year_desc')>
                                    Year (newest first)
                                </option>

                                <option value="year_asc"
                                    @selected($filters['sort'] === 'year_asc')>
                                    Year (oldest first)
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <a href="{{ route('books', array_filter(['search' => request('search')])) }}"
                       class="btn btn-outline-secondary">

                        Reset

                    </a>

                    <button type="button"
                            class="btn btn-outline-danger"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-info text-white">

                        <i class="bi bi-funnel me-1"></i>
                        Apply Filters

                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- ADD BOOK MODAL                         -->
    <!-- ====================================== -->

    <div class="modal fade"
         id="addBook"
         tabindex="-1"
         aria-hidden="true"
         data-show-on-load="{{ $errors->any() ? '1' : '0' }}">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <form method="POST"
                  action="{{ route('books.store') }}"
                  class="modal-content p-3">

                @csrf

                <!-- Modal Header -->
                <div class="modal-header border-0">

                    <div>
                        <h5 class="modal-title fw-semibold">
                            Add New Book
                        </h5>

                        <small class="text-muted">
                            Enter the book information below.
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Validation Errors -->
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Please check the following:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <!-- Book Information -->
                    <h6 class="fw-semibold mb-3">
                        Book Information
                    </h6>

                    <div class="row g-3">

                        <!-- Title -->
                        <div class="col-md-6">

                            <label for="title" class="form-label">
                                Book Title <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ $editFailed ? '' : old('title') }}"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Enter book title"
                                   maxlength="255"
                                   required>

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Author -->
                        <div class="col-md-6">

                            <label for="author" class="form-label">
                                Author <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="author"
                                   name="author"
                                   value="{{ $editFailed ? '' : old('author') }}"
                                   class="form-control @error('author') is-invalid @enderror"
                                   placeholder="Enter author name"
                                   maxlength="255"
                                   required>

                            @error('author')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Duplicate Warning -->
                        <div class="col-12">

                            <div class="alert alert-warning py-2 mb-0">

                                <i class="bi bi-exclamation-triangle me-1"></i>

                                <strong>Already in the catalog?</strong>

                                If this book already exists, update its
                                quantity instead of creating another record.

                            </div>

                        </div>

                        <!-- Category -->
                        <div class="col-md-6">

                            <label for="category_id" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>

                            <select name="category_id"
                                    id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror"
                                    required>

                                <option value="">Select a category</option>

                                @foreach ($categories as $c)

                                    <option value="{{ $c->category_id }}"
                                        @selected(! $editFailed && old('category_id') == $c->category_id)>

                                        {{ $c->category_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Publisher -->
                        <div class="col-md-6">

                            <label for="publisher" class="form-label">
                                Publisher
                            </label>

                            <input type="text"
                                   name="publisher"
                                   id="publisher"
                                   value="{{ $editFailed ? '' : old('publisher') }}"
                                   class="form-control @error('publisher') is-invalid @enderror"
                                   placeholder="Enter publisher"
                                   maxlength="255">

                            @error('publisher')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Publication Year -->
                        <div class="col-md-6">

                            <label for="publication_year" class="form-label">
                                Publication Year
                            </label>

                            <select name="publication_year"
                                    id="publication_year"
                                    class="form-select @error('publication_year') is-invalid @enderror">

                                <option value="">Select a year</option>

                                @for ($y = date('Y'); $y >= 1000; $y--)

                                    <option value="{{ $y }}"
                                        @selected(! $editFailed && old('publication_year') == $y)>

                                        {{ $y }}

                                    </option>

                                @endfor

                            </select>

                            @error('publication_year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Inventory Information -->
                    <h6 class="fw-semibold mb-3">
                        Inventory Information
                    </h6>

                    <div class="row g-3">

                        <!-- Quantity -->
                        <div class="col-md-6">

                            <label for="quantity" class="form-label">
                                Quantity <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   value="{{ $editFailed ? 1 : old('quantity', 1) }}"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   min="1"
                                   max="100000"
                                   step="1"
                                   required>

                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Total number of physical copies.
                            </small>

                        </div>

                        <!-- Status -->
                        <div class="col-md-6">

                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>

                                <option value="available"
                                    @selected(($editFailed ? 'available' : old('status', 'available')) === 'available')>
                                    Available
                                </option>

                                <option value="unavailable"
                                    @selected(! $editFailed && old('status') === 'unavailable')>
                                    Unavailable
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <!-- Inventory Information Notice -->
                    <div class="alert alert-info mt-4 mb-0 py-2">

                        <i class="bi bi-info-circle me-1"></i>

                        Available quantity will automatically be initialized
                        based on the selected status.

                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0">

                    <button type="reset"
                            class="btn btn-outline-secondary">

                        Clear

                    </button>

                    <button type="button"
                            class="btn btn-outline-danger"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-info text-white">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add Book

                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- EDIT BOOK MODAL                        -->
    <!-- ====================================== -->

    <div class="modal fade"
         id="editBook"
         tabindex="-1"
         aria-labelledby="editBookLabel"
         aria-hidden="true"
         data-show-on-load="{{ $editFailed ? '1' : '0' }}">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <form method="POST"
                  id="editBookForm"
                  action="{{ $editFailed && old('edit_book_id') ? route('books.update', old('edit_book_id')) : '' }}"
                  class="modal-content p-3">

                @csrf
                @method('PUT')

                <input type="hidden"
                       name="edit_book_id"
                       id="edit_book_id"
                       value="{{ $editFailed ? old('edit_book_id') : '' }}">

                <!-- Modal Header -->
                <div class="modal-header border-0">

                    <div>
                        <h5 class="modal-title fw-semibold" id="editBookLabel">
                            Edit Book
                        </h5>

                        <small class="text-muted">
                            Update the book information below.
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Validation Errors -->
                    @if ($editFailed)

                        <div class="alert alert-danger" id="editErrors">

                            <strong>
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Please check the following:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->editBook->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <!-- Book Information -->
                    <h6 class="fw-semibold mb-3">
                        Book Information
                    </h6>

                    <div class="row g-3">

                        <!-- Title -->
                        <div class="col-md-6">

                            <label for="edit_title" class="form-label">
                                Book Title <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="edit_title"
                                   name="title"
                                   value="{{ $editFailed ? old('title') : '' }}"
                                   class="form-control @if($errors->editBook->has('title')) is-invalid @endif"
                                   maxlength="255"
                                   required>

                            @if ($errors->editBook->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('title') }}
                                </div>
                            @endif

                        </div>

                        <!-- Author -->
                        <div class="col-md-6">

                            <label for="edit_author" class="form-label">
                                Author <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="edit_author"
                                   name="author"
                                   value="{{ $editFailed ? old('author') : '' }}"
                                   class="form-control @if($errors->editBook->has('author')) is-invalid @endif"
                                   maxlength="255"
                                   required>

                            @if ($errors->editBook->has('author'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('author') }}
                                </div>
                            @endif

                        </div>

                        <!-- Category -->
                        <div class="col-md-6">

                            <label for="edit_category_id" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>

                            <select name="category_id"
                                    id="edit_category_id"
                                    class="form-select @if($errors->editBook->has('category_id')) is-invalid @endif"
                                    required>

                                <option value="">Select a category</option>

                                @foreach ($categories as $c)

                                    <option value="{{ $c->category_id }}"
                                        @selected($editFailed && old('category_id') == $c->category_id)>

                                        {{ $c->category_name }}

                                    </option>

                                @endforeach

                            </select>

                            @if ($errors->editBook->has('category_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('category_id') }}
                                </div>
                            @endif

                        </div>

                        <!-- Publisher -->
                        <div class="col-md-6">

                            <label for="edit_publisher" class="form-label">
                                Publisher
                            </label>

                            <input type="text"
                                   name="publisher"
                                   id="edit_publisher"
                                   value="{{ $editFailed ? old('publisher') : '' }}"
                                   class="form-control @if($errors->editBook->has('publisher')) is-invalid @endif"
                                   maxlength="255">

                            @if ($errors->editBook->has('publisher'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('publisher') }}
                                </div>
                            @endif

                        </div>

                        <!-- Publication Year -->
                        <div class="col-md-6">

                            <label for="edit_publication_year" class="form-label">
                                Publication Year
                            </label>

                            <select name="publication_year"
                                    id="edit_publication_year"
                                    class="form-select @if($errors->editBook->has('publication_year')) is-invalid @endif">

                                <option value="">Select a year</option>

                                @for ($y = date('Y'); $y >= 1000; $y--)

                                    <option value="{{ $y }}"
                                        @selected($editFailed && old('publication_year') == $y)>

                                        {{ $y }}

                                    </option>

                                @endfor

                            </select>

                            @if ($errors->editBook->has('publication_year'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('publication_year') }}
                                </div>
                            @endif

                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Inventory Information -->
                    <h6 class="fw-semibold mb-3">
                        Inventory Information
                    </h6>

                    <div class="row g-3">

                        <!-- Quantity -->
                        <div class="col-md-6">

                            <label for="edit_quantity" class="form-label">
                                Quantity <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="quantity"
                                   id="edit_quantity"
                                   value="{{ $editFailed ? old('quantity') : '' }}"
                                   class="form-control @if($errors->editBook->has('quantity')) is-invalid @endif"
                                   min="1"
                                   max="100000"
                                   step="1"
                                   required>

                            @if ($errors->editBook->has('quantity'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('quantity') }}
                                </div>
                            @endif

                            <small class="text-muted" id="edit_borrowed_note">
                                Total number of physical copies.
                            </small>

                        </div>

                        <!-- Status -->
                        <div class="col-md-6">

                            <label for="edit_status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    id="edit_status"
                                    class="form-select @if($errors->editBook->has('status')) is-invalid @endif"
                                    required>

                                <option value="available"
                                    @selected($editFailed && old('status') === 'available')>
                                    Available
                                </option>

                                <option value="unavailable"
                                    @selected($editFailed && old('status') === 'unavailable')>
                                    Unavailable
                                </option>

                            </select>

                            @if ($errors->editBook->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->editBook->first('status') }}
                                </div>
                            @endif

                        </div>

                    </div>

                    <!-- Inventory Information Notice -->
                    <div class="alert alert-info mt-4 mb-0 py-2">

                        <i class="bi bi-info-circle me-1"></i>

                        Available quantity is recalculated automatically.
                        Copies that are currently borrowed are kept out of
                        the available count, and setting the status to
                        Unavailable sets available copies to 0.

                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-info text-white">

                        <i class="bi bi-check-circle me-1"></i>
                        Save Changes

                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- DELETE BOOK CONFIRMATION MODAL         -->
    <!-- ====================================== -->
    <div class="modal fade" id="deleteBook" tabindex="-1" aria-labelledby="deleteBookLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" id="deleteBookLabel">
                        Delete Book
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete this book?</p>
                    <p class="fw-semibold mb-3" id="deleteBookTitle"></p>
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        This action cannot be undone. Books with existing borrowing records cannot be deleted.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteBookForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Delete Book
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ====================================== -->
    <!-- VIEW BOOK DETAILS PANEL                -->
    <!-- ====================================== -->

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="viewBook"
         aria-labelledby="viewBookLabel">

        <div class="offcanvas-header">

            <div>

                <h6 class="m-0" id="v-id"></h6>

                <h3 id="v-title"
                    class="text-info"
                    aria-labelledby="viewBookLabel">
                </h3>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>

        </div>

        <div class="offcanvas-body">

            <!-- Book Cover -->
            <div class="bk-cover" id="v-cover-box">

                <img id="v-cover"
                     alt="Book cover"
                     hidden>

                <div id="v-cover-fallback"
                     class="bk-cover-fallback">

                    <i class="bi bi-book"></i>

                </div>

            </div>

            <!-- Open Library Disclaimer -->
            <div class="alert alert-light border small text-muted mb-4">

                <i class="bi bi-info-circle me-1"></i>

                <strong>About Book Covers</strong>

                <p class="mb-0 mt-1">

                    Book cover images are automatically retrieved from the
                    Open Library API when a matching book is found in its
                    catalog. Cover availability depends on Open Library's
                    database. If no matching cover is available, a default
                    book icon will be displayed instead.

                </p>

            </div>

            <!-- Book Details -->
            <dl>

                <dt>Author</dt>
                <dd id="v-author"></dd>

                <dt>Publisher</dt>
                <dd id="v-publisher"></dd>

                <dt>Publication Date</dt>
                <dd id="v-year"></dd>

                <dt>Quantity</dt>
                <dd id="v-qty"></dd>

                <dt>Category</dt>
                <dd id="v-category"></dd>

                <dt>Status</dt>
                <dd id="v-status" class="fw-bold"></dd>

            </dl>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ====================================== -->
    <!-- CUSTOM JAVASCRIPT                      -->
    <!-- ====================================== -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ==========================================
            // ADD / EDIT MODALS: reopen if validation fails
            // ==========================================

            ['addBook', 'editBook'].forEach(function (id) {

                const modal = document.getElementById(id);

                if (modal && modal.dataset.showOnLoad === '1') {

                    bootstrap.Modal.getOrCreateInstance(modal).show();

                }

            });

            // ==========================================
            // FILTER MODAL: don't send empty fields
            // ==========================================
            // Keeps the URL clean (no ?category_id=&status=&...)

            const filterForm = document.querySelector('#filterBooks form');

            if (filterForm) {

                filterForm.addEventListener('submit', function () {

                    filterForm.querySelectorAll('input, select').forEach(function (field) {

                        // Skip the default sort too
                        if (field.value === '' || (field.name === 'sort' && field.value === 'id')) {

                            field.disabled = true;

                        }

                    });

                });

            }

            // ==========================================
            // EDIT BOOK MODAL
            // ==========================================

            const editForm = document.getElementById('editBookForm');
            const editBookId = document.getElementById('edit_book_id');
            const editBorrowedNote = document.getElementById('edit_borrowed_note');

            document.querySelectorAll('.edit-btn').forEach(function (btn) {

                btn.addEventListener('click', function () {

                    const d = this.dataset;

                    // Point the form at this book's update route
                    editForm.action = d.url;
                    editBookId.value = d.bookId;

                    // Fill the fields
                    document.getElementById('edit_title').value = d.title || '';
                    document.getElementById('edit_author').value = d.author || '';
                    document.getElementById('edit_category_id').value = d.categoryId || '';
                    document.getElementById('edit_publisher').value = d.publisher || '';
                    document.getElementById('edit_publication_year').value = d.year || '';
                    document.getElementById('edit_quantity').value = d.qty || 1;
                    document.getElementById('edit_status').value =
                        d.status === 'unavailable' ? 'unavailable' : 'available';

                    // Show how many copies are currently out, and don't
                    // let the quantity go below that in the browser either
                    const borrowed = parseInt(d.borrowed || '0', 10);
                    const qtyInput = document.getElementById('edit_quantity');

                    if (d.status === 'available' && borrowed > 0) {
                        qtyInput.min = borrowed;
                        editBorrowedNote.textContent =
                            borrowed + ' cop' + (borrowed === 1 ? 'y is' : 'ies are')
                            + ' currently borrowed, so quantity cannot go below that.';
                    } else {
                        qtyInput.min = 1;
                        editBorrowedNote.textContent = 'Total number of physical copies.';
                    }

                    // Clear errors left over from a previous failed attempt
                    const oldErrors = document.getElementById('editErrors');
                    if (oldErrors) oldErrors.remove();

                    editForm.querySelectorAll('.is-invalid').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });

                });

            });

            // ==========================================
            // DELETE BOOK MODAL
            // ==========================================
            const deleteForm = document.getElementById('deleteBookForm');
            const deleteTitle = document.getElementById('deleteBookTitle');

            document.querySelectorAll('.delete-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    deleteTitle.textContent = this.dataset.title || 'this book';
                    deleteForm.action = this.dataset.url;
                });
            });

            // ==========================================
            // BOOK COVER PREVIEW
            // ==========================================

            const coverBox = document.getElementById('v-cover-box');
            const coverImg = document.getElementById('v-cover');
            const coverFallback = document.getElementById('v-cover-fallback');

            const coverCache = new Map();

            let latestRequest = 0;

            // Display cover or fallback icon
            function showCover(url) {

                coverBox.classList.remove('is-loading');

                if (url) {

                    coverImg.src = url;
                    coverImg.hidden = false;
                    coverFallback.hidden = true;

                } else {

                    coverImg.hidden = true;
                    coverImg.removeAttribute('src');
                    coverFallback.hidden = false;

                }

            }

            // Handle failed image loading
            coverImg.addEventListener('error', function () {

                showCover(null);

            });

            // ==========================================
            // OPEN LIBRARY API
            // ==========================================

            async function findCover(title, author) {

                const key = title.trim().toLowerCase()
                    + '|'
                    + author.trim().toLowerCase();

                // Return cached result if already searched
                if (coverCache.has(key)) {

                    return coverCache.get(key);

                }

                // Try title + author first, then title alone
                const attempts = author
                    ? [
                        { title: title, author: author },
                        { title: title }
                    ]
                    : [
                        { title: title }
                    ];

                let url = null;

                for (const query of attempts) {

                    try {

                        const params = new URLSearchParams({

                            ...query,
                            limit: '1',
                            fields: 'cover_i'

                        });

                        const response = await fetch(
                            'https://openlibrary.org/search.json?' + params
                        );

                        if (!response.ok) {

                            continue;

                        }

                        const data = await response.json();
                        const coverId = data.docs?.[0]?.cover_i;

                        if (coverId) {

                            url = 'https://covers.openlibrary.org/b/id/'
                                + encodeURIComponent(coverId)
                                + '-L.jpg?default=false';

                            break;

                        }

                    } catch (error) {

                        // API or network error
                        console.warn(
                            'Open Library lookup failed:',
                            error
                        );

                    }

                }

                // Cache result, including missing covers
                coverCache.set(key, url);

                return url;

            }

            // ==========================================
            // VIEW BOOK DETAILS
            // ==========================================

            document.querySelectorAll('.view-btn').forEach(function (btn) {

                btn.addEventListener('click', async function () {

                    const data = this.dataset;

                    // Populate book information
                    [
                        'id',
                        'title',
                        'author',
                        'publisher',
                        'year',
                        'qty',
                        'category',
                        'status'

                    ].forEach(function (key) {

                        const element = document.getElementById('v-' + key);

                        element.textContent = data[key] || '—';

                    });

                    // Change status text color
                    const status = document.getElementById('v-status');

                    status.className = 'fw-bold ' +
                        (
                            data.status?.startsWith('Available')
                                ? 'text-success'
                                : 'text-danger'
                        );

                    // Reset cover before loading another one
                    showCover(null);

                    coverBox.classList.add('is-loading');

                    // Track latest request to avoid outdated responses
                    const request = ++latestRequest;

                    // Retrieve cover from Open Library
                    const url = await findCover(
                        data.title || '',
                        data.author || ''
                    );

                    // Only update the currently selected book
                    if (request === latestRequest) {

                        showCover(url);

                    }

                });

            });

        });
    </script>

</body>

</html>