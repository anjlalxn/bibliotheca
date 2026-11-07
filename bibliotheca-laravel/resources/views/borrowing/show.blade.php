@extends('layouts.panel')

@section('title', 'Borrow Transaction')
@section('heading', session('created') ? 'Borrow Transaction Status' : 'Borrow Transaction')

@section('content')
@php
    $tid = 'B-' . str_pad($borrowing->borrowing_id, 3, '0', STR_PAD_LEFT);
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
                <i class="bi bi-journal-text text-info" style="font-size: 7rem;"></i>
                <h2 class="fw-bold">
                    <span class="badge text-bg-{{ $badge }}">{{ $borrowing->display_status }}</span>
                </h2>
            @endif
        </div>

        <div class="col-md-7">
            <h5 class="text-info">Borrow Transaction ID: {{ $tid }}</h5>

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
@endsection