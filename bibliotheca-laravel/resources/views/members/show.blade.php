@extends('layouts.panel')

@section('title', $member->full_name)
@section('heading', 'Member Details')

@section('content')
@php
    $isActive = strtolower((string) $member->status) === 'active';
    $badgeMap = ['Returned' => 'success', 'Overdue' => 'danger', 'Borrowed' => 'info'];
@endphp

<!-- Member info -->
<div class="card p-4 mb-4">
    <div class="row g-4 align-items-center">

        <div class="col-md-3 text-center">
            <i class="bi bi-person-circle text-info" style="font-size: 6rem;"></i>
            <div>
                @if ($isActive)
                    <span class="badge text-bg-success fs-6">Active</span>
                @else
                    <span class="badge text-bg-secondary fs-6">{{ ucfirst((string) $member->status) ?: 'Inactive' }}</span>
                @endif
            </div>
        </div>

        <div class="col-md-9">
            <h4 class="text-info mb-3">{{ $member->full_name }}</h4>

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="fw-semibold">Member Code</div>
                    <div>{{ $member->member_code }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="fw-semibold">Email</div>
                    <div>{{ $member->email }}</div>
                </div>
            </div>

            <hr>

            <div class="row text-center g-3">
                <div class="col-6 col-md-3">
                    <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                    <div class="small text-muted">Total borrowed</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-3 fw-bold text-info">{{ $stats['active'] }}</div>
                    <div class="small text-muted">Currently borrowed</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-3 fw-bold text-danger">{{ $stats['overdue'] }}</div>
                    <div class="small text-muted">Overdue</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-3 fw-bold text-success">{{ $stats['returned'] }}</div>
                    <div class="small text-muted">Returned</div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('members') }}" class="btn btn-outline-info">Back to Members</a>
            </div>
        </div>
    </div>
</div>

<!-- Borrowed books grid -->
<section class="section-block">
    <h2 class="section-title mb-3">Borrowed Books</h2>

    @if ($borrowings->isEmpty())
        <div class="card p-4 text-center text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <div>This member hasn't borrowed any books yet.</div>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
            @foreach ($borrowings as $borrowing)
                @php
                    $status = $borrowing->display_status;
                @endphp
                <div class="col">
                    <a href="{{ route('borrowing.show', $borrowing->borrowing_id) }}"
                       class="text-decoration-none text-reset">
                        <div class="card h-100 border rounded-3 overflow-hidden member-book-card"
                             data-title="{{ $borrowing->book->title ?? '' }}"
                             data-author="{{ $borrowing->book->author ?? '' }}">

                            <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                 style="aspect-ratio: 2/3;">
                                <img class="book-cover-img w-100 h-100"
                                     style="object-fit: cover; display: none;"
                                     alt="{{ $borrowing->book->title ?? 'Book cover' }}">
                                <div class="book-cover-fallback">
                                    <i class="bi bi-book text-info fs-1"></i>
                                </div>
                            </div>

                            <div class="card-body p-2 text-center">
                                <h6 class="card-title mb-1 text-truncate" title="{{ $borrowing->book->title ?? '—' }}">
                                    {{ $borrowing->book->title ?? '—' }}
                                </h6>
                                <p class="card-text small text-muted mb-1 text-truncate"
                                   title="{{ $borrowing->book->author ?? '' }}">
                                    {{ $borrowing->book->author ?? '' }}
                                </p>
                                <span class="badge text-bg-{{ $badgeMap[$status] ?? 'secondary' }}">{{ $status }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const coverCache = new Map();

        // Same Open Library lookup as the dashboard: title + author first, then title only
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

        document.querySelectorAll('.member-book-card').forEach(async function (card) {
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