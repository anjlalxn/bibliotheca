@extends('layouts.panel')

@section('title', $category->category_name)
@section('heading', 'Category Library')

@section('content')

<!-- Category info -->
<div class="card p-4 mb-4">
    <div class="row g-4 align-items-center">

        <div class="col-md-3 text-center">
            <i class="bi bi-tags text-info" style="font-size: 6rem;"></i>
        </div>

        <div class="col-md-9">
            <h4 class="text-info mb-3">{{ $category->category_name }}</h4>

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="fw-semibold">Category ID</div>
                    <div>CAT-{{ $category->category_id }}</div>
                </div>
            </div>

            <hr>

            <div class="row text-center g-3">
                <div class="col-6">
                    <div class="fs-3 fw-bold">{{ $stats['titles'] }}</div>
                    <div class="small text-muted">Total books</div>
                </div>
                <div class="col-6">
                    <div class="fs-3 fw-bold text-success">{{ $stats['available'] }}</div>
                    <div class="small text-muted">Available now</div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('categories') }}" class="btn btn-outline-info">Back to Categories</a>
            </div>
        </div>
    </div>
</div>

<!-- Books grid -->
<section class="section-block">
    <div class="d-flex flex-column-reverse flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
        <div class="search-toolbar d-flex align-items-center gap-2">
            <form class="search-group input-group"
                  action="{{ route('categories.show', $category->category_id) }}"
                  method="GET" role="search">
                <input type="search" class="form-control" name="search" value="{{ request('search') }}"
                       placeholder="Title/Author" aria-label="Search books in this category">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </form>
        </div>
        <div class="text-sm-end">
            <h2 class="section-title">Books</h2>
        </div>
    </div>

    @if ($books->isEmpty())
        <div class="card p-4 text-center text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <div>
                @if (request('search'))
                    No books match "{{ request('search') }}" in this category.
                @else
                    There are no books in this category yet.
                @endif
            </div>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
            @foreach ($books as $book)
                @php
                    $inStock = ($book->available_quantity ?? 0) > 0;
                @endphp
                <div class="col">
                    <div class="card h-100 border rounded-3 overflow-hidden category-book-card"
                         data-title="{{ $book->title }}"
                         data-author="{{ $book->author }}">

                        <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                             style="aspect-ratio: 2/3;">
                            <img class="book-cover-img w-100 h-100"
                                 style="object-fit: cover; display: none;"
                                 alt="{{ $book->title ?? 'Book cover' }}">
                            <div class="book-cover-fallback">
                                <i class="bi bi-book text-info fs-1"></i>
                            </div>
                        </div>

                        <div class="card-body p-2 text-center">
                            <h6 class="card-title mb-1 text-truncate" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h6>
                            <p class="card-text small text-muted mb-1 text-truncate" title="{{ $book->author }}">
                                {{ $book->author }}
                            </p>
                            <span class="badge text-bg-{{ $inStock ? 'success' : 'secondary' }}">
                                {{ $inStock ? 'Available' : 'Unavailable' }}
                            </span>
                            <div class="small text-muted mt-1">
                                {{ $book->available_quantity ?? 0 }} / {{ $book->quantity ?? 0 }} copies
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $books->links() }}
        </div>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const coverCache = new Map();

        // Open Library lookup: title + author first, then title only
        async function findCover(title, author) {
            const key = title.trim().toLowerCase() + '|' + author.trim().toLowerCase();
            if (coverCache.has(key)) return coverCache.get(key);

            let url = null;
            const attempts = author ? [{ title, author }, { title }] : [{ title }];

            for (const q of attempts) {
                try {
                    const params = new URLSearchParams({ ...q, limit: '1', fields: 'cover_i' });
                    const res = await fetch('https://openlibrary.org/search.json?' + params);
                    if (!res.ok) continue;

                    const data = await res.json();
                    const coverId = data.docs && data.docs[0] && data.docs[0].cover_i;

                    if (coverId) {
                        url = 'https://covers.openlibrary.org/b/id/' + coverId + '-M.jpg?default=false';
                        break;
                    }
                } catch (e) { /* ignore network errors, keep the icon */ }
            }

            coverCache.set(key, url);
            return url;
        }

        document.querySelectorAll('.category-book-card').forEach(async function (card) {
            const title = card.dataset.title;
            if (!title) return;

            const img = card.querySelector('.book-cover-img');
            const fallback = card.querySelector('.book-cover-fallback');

            const url = await findCover(title, card.dataset.author || '');

            if (url) {
                // Swap only after the image has actually loaded
                img.onload = function () {
                    img.style.display = 'block';
                    fallback.style.display = 'none';
                };
                img.src = url;
            }
        });
    });
</script>
@endsection