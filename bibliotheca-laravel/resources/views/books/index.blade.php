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
        .bk-cover { width: 170px; aspect-ratio: 2 / 3; margin: 0 auto 1.5rem; border-radius: .5rem; overflow: hidden; background: #e9f7fb; }
        .bk-cover img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .bk-cover-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3.75rem; color: #0dcaf0; }
        .bk-cover.is-loading .bk-cover-fallback { animation: bk-pulse 1.2s ease-in-out infinite; }
        @keyframes bk-pulse { 50% { opacity: .45; } }
    </style>
</head>
<body>

    <div class="d-md-flex">

        <!-- Sidebar -->
        <aside class="sidebar offcanvas-md offcanvas-start d-flex flex-column" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
            <div class="offcanvas-header d-md-none">
                <h5 class="offcanvas-title visually-hidden" id="sidebarLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column">
                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Bibliotheca" class="img-fluid">
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="{{ route('books') }}" class="nav-link active" aria-current="page"><i class="bi bi-book"></i> Books</a></li>
                    <li class="nav-item"><a href="{{ url('/borrowing') }}" class="nav-link"><i class="bi bi-clock"></i> Borrowing</a></li>
                    <li class="nav-item"><a href="{{ url('/return') }}" class="nav-link"><i class="bi bi-arrow-repeat"></i> Return</a></li>
                    <li class="nav-item"><a href="{{ url('/members') }}" class="nav-link"><i class="bi bi-person-circle"></i> Members</a></li>
                    <li class="nav-item"><a href="{{ url('/categories') }}" class="nav-link"><i class="bi bi-tags"></i> Categories</a></li>
                    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-graph-up"></i> Analytics</a></li>
                </ul>

                <div class="sidebar-footer mt-auto">
                    <div class="sidebar-user d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i> Administrator
                    </div>
                    <button type="button" class="btn btn-logout w-100">Logout</button>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100">

            <header class="mobile-topbar d-flex d-md-none align-items-center justify-content-between">
                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Bibliotheca" class="img-fluid">
                </div>
                <button class="btn btn-menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <main class="main-content">

                <h1 class="page-title page-title--spaced">Books</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Stat cards -->
                <div class="stat-panel">
                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                        <div class="col">
                            <div class="stat-card stat-card--teal">
                                <i class="bi bi-book stat-icon"></i>
                                <div class="stat-body">
                                    <p class="stat-label">Total Books</p>
                                    <p class="stat-value">{{ $totalBooks }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <a href="#" class="stat-card stat-card--cyan" data-bs-toggle="modal" data-bs-target="#addBook">
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
                            <form class="search-group input-group" action="{{ route('books') }}" method="GET" role="search">
                                <input type="search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Author/Category/Book" aria-label="Search books">
                                <button class="btn btn-info text-white" type="submit">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                            </form>
                            <button class="btn btn-filter" type="button" aria-label="Filter">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                        <div class="text-sm-end">
                            <h2 class="section-title">All Books</h2>
                        </div>
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
                                        <td>{{ $book->category->category_name ?? '—' }}</td>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author }}</td>
                                        <td>{{ $book->publisher }}</td>
                                        <td>{{ $year }}</td>
                                        <td>{{ $book->quantity }}</td>
                                        <td>{{ $book->available_quantity }}</td>
                                        <td>
                                            @if ($isAvailable)
                                                <span class="badge text-bg-success">Available</span>
                                            @else
                                                <span class="badge text-bg-danger">Unavailable</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-primary view-btn"
                                                data-bs-toggle="offcanvas" data-bs-target="#viewBook"
                                                data-id="BOOK-{{ $book->book_id }}"
                                                data-title="{{ $book->title }}"
                                                data-author="{{ $book->author }}"
                                                data-publisher="{{ $book->publisher }}"
                                                data-year="{{ $year }}"
                                                data-qty="{{ $book->quantity }}"
                                                data-category="{{ $book->category->category_name ?? '—' }}"
                                                data-status="{{ $isAvailable ? 'Available' : 'Unavailable' }} ({{ $book->available_quantity }} copies)"
                                                aria-label="View">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <form method="POST" action="{{ route('books.destroy', $book->book_id) }}"
                                                  class="d-inline" onsubmit="return confirm('Delete this book?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" aria-label="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="table-empty">
                                            <i class="bi bi-inbox"></i>
                                            @if (request('search'))
                                                No author, category, or books match "{{ request('search') }}".
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

            </main>
        </div>
    </div>

    <!-- Add Book modal -->
    <div class="modal fade" id="addBook" tabindex="-1" aria-hidden="true"
         data-show-on-load="{{ $errors->any() ? '1' : '0' }}">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('books.store') }}" class="modal-content p-3">
                @csrf
                <small class="text-muted mb-3">Create a new book record.</small>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Book Title</label>
                        <input name="title" value="{{ old('title') }}" class="form-control" placeholder="Enter title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Author</label>
                        <input name="author" value="{{ old('author') }}" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-warning py-2 m-0">
                            This book may already exist in your catalog. Adding another copy of an existing book?
                            Update the quantity from the book records table.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->category_id }}" @selected(old('category_id') == $c->category_id)>{{ $c->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Publisher</label>
                        <input name="publisher" value="{{ old('publisher') }}" class="form-control" placeholder="Enter publisher">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Publication Date</label>
                        <select name="publication_year" class="form-select">
                            <option value="">Select a year</option>
                            @for ($y = date('Y'); $y >= 1900; $y--)
                                <option value="{{ $y }}" @selected(old('publication_year') == $y)>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button class="btn btn-info text-white">Add</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="reset" class="btn btn-secondary">Clear all details</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View details panel -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="viewBook">
        <div class="offcanvas-header">
            <div>
                <h6 class="m-0" id="v-id"></h6>
                <h3 id="v-title" class="text-info"></h3>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="bk-cover" id="v-cover-box">
                <img id="v-cover" alt="Book cover" hidden>
                <div id="v-cover-fallback" class="bk-cover-fallback"><i class="bi bi-book"></i></div>
            </div>

            <dl>
                <dt>Author</dt><dd id="v-author"></dd>
                <dt>Publisher</dt><dd id="v-publisher"></dd>
                <dt>Publication Date</dt><dd id="v-year"></dd>
                <dt>Quantity</dt><dd id="v-qty"></dd>
                <dt>Category</dt><dd id="v-category"></dd>
                <dt>Status</dt><dd id="v-status" class="fw-bold"></dd>
            </dl>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Re-open the Add Book modal if validation failed
        const addModal = document.getElementById('addBook');
        if (addModal.dataset.showOnLoad === '1') {
            new bootstrap.Modal(addModal).show();
        }

        // ----- Book preview panel -----
        const coverBox = document.getElementById('v-cover-box');
        const coverImg = document.getElementById('v-cover');
        const coverFallback = document.getElementById('v-cover-fallback');
        const coverCache = new Map();
        let latestRequest = 0;

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

        // If Open Library has no image, the URL 404s and we fall back to the icon
        coverImg.addEventListener('error', () => showCover(null));

        async function findCover(title, author) {
            const key = title + '|' + author;
            if (coverCache.has(key)) return coverCache.get(key);

            let url = null;
            // Try title + author first, then title only
            const attempts = author ? [{ title, author }, { title }] : [{ title }];

            for (const q of attempts) {
                try {
                    const params = new URLSearchParams({ ...q, limit: '1', fields: 'cover_i' });
                    const res = await fetch('https://openlibrary.org/search.json?' + params);
                    if (!res.ok) continue;
                    const data = await res.json();
                    const coverId = data.docs && data.docs[0] && data.docs[0].cover_i;
                    if (coverId) {
                        url = 'https://covers.openlibrary.org/b/id/' + coverId + '-L.jpg?default=false';
                        break;
                    }
                } catch (e) { /* network error: use the fallback icon */ }
            }

            coverCache.set(key, url);
            return url;
        }

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const d = btn.dataset;

                ['id', 'title', 'author', 'publisher', 'year', 'qty', 'category', 'status'].forEach(k => {
                    document.getElementById('v-' + k).textContent = d[k] || '—';
                });

                const status = document.getElementById('v-status');
                status.className = 'fw-bold ' + (d.status && d.status.startsWith('Available') ? 'text-success' : 'text-danger');

                // Show the icon while the cover loads, then swap it in
                showCover(null);
                coverBox.classList.add('is-loading');
                const request = ++latestRequest;

                const url = await findCover(d.title, d.author);
                if (request === latestRequest) showCover(url);
            });
        });
    </script>
</body>
</html>