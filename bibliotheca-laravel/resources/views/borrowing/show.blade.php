@extends('layouts.panel')

@section('title', 'Borrow Transaction')
@section('heading', session('created') ? 'Borrow Transaction Status' : 'Borrow Transaction')

@section('content')
@php
    $ids   = session('created_ids', [$borrowing->borrowing_id]);
    $tids  = collect($ids)->map(fn ($id) => 'B-' . str_pad($id, 3, '0', STR_PAD_LEFT));
    $badge = ['Returned' => 'success', 'Overdue' => 'danger', 'Borrowed' => 'info'][$borrowing->display_status];
@endphp

<div class="card p-4">
    <div class="row g-4 align-items-center">
        <div class="col-md-5 text-center">
            @if (session('created'))
                <i class="bi bi-check-circle-fill text-success" style="font-size: 7rem;"></i>
                <h2 class="text-success fw-bold">SUCCESS</h2>
                <p class="small text-muted">Transaction has been successfully completed.</p>
            @else
                <div id="book-cover-box"
                     data-title="{{ $borrowing->book->title ?? '' }}"
                     data-author="{{ $borrowing->book->author ?? '' }}">
                    <img id="book-cover-img"
                         alt="{{ $borrowing->book->title ?? 'Book cover' }}"
                         class="rounded mb-2"
                         style="display: none; max-height: 260px; max-width: 100%; object-fit: contain;">
                    <i id="book-cover-fallback" class="bi bi-journal-text text-info" style="font-size: 7rem;"></i>
                </div>
                <h2 class="fw-bold">
                    <span class="badge text-bg-{{ $badge }}">{{ $borrowing->display_status }}</span>
                </h2>
            @endif
        </div>

        <div class="col-md-7">
            <h5 class="text-info"> 
                Borrow Transaction ID{{ $tids->count() > 1 ? 's' : '' }}: {{ $tids->join(', ') }}
            </h5>
            @if ($tids->count() > 1)
                <div class="text-muted small">{{ $tids->count() }} copies borrowed</div>
            @endif
            <div class="fw-semibold mt-3">Member</div>
            <div>{{ $borrowing->member->full_name ?? '—' }}</div>
            <div>Member Code: {{ $borrowing->member->member_code ?? '—' }}</div>

            <div class="fw-semibold mt-3">Book Details</div>
            <div>{{ $borrowing->book->title ?? '—' }}</div>
            <div>{{ $borrowing->book->author ?? '' }}</div>
            <div>{{ $borrowing->book->category->category_name ?? '' }}</div>
            <div>{{ $borrowing->book->publication_year ?? '' }}</div>

            <div class="row mt-3">
                <div class="col-6">
                    <div class="fw-semibold">Borrow Date</div>
                    <div class="text-success">{{ $borrowing->borrowing_date?->format('F j, Y') }}</div>
                </div>
                <div class="col-6">
                    <div class="fw-semibold">Due Date</div>
                    <div class="text-danger">{{ $borrowing->due_date?->format('F j, Y') }}</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                @if (session('created'))
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-info">Go back Home</a>
                    <a href="{{ route('borrowing.create') }}" class="btn btn-success">Create another one</a>
                @else
                    <a href="{{ route('borrowing') }}" class="btn btn-outline-info">Back to Borrowing</a>
                @endif
            </div>
        </div>
    </div>
</div>

@unless (session('created'))
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const box = document.getElementById('book-cover-box');
            if (!box) return;

            const title = box.dataset.title;
            const author = box.dataset.author;
            const img = document.getElementById('book-cover-img');
            const fallback = document.getElementById('book-cover-fallback');

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
                        // Swap only after the image has actually loaded
                        img.onload = () => {
                            img.style.display = 'inline-block';
                            fallback.style.display = 'none';
                        };
                        img.src = 'https://covers.openlibrary.org/b/id/' + coverId + '-L.jpg?default=false';
                        break;
                    }
                } catch (e) { /* ignore network errors, keep the icon */ }
            }
        });
    </script>
@endunless
@endsection