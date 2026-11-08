@extends('layouts.panel')

@section('title', 'New Borrow Transaction')
@section('heading', 'New Borrow Transaction')

@section('content')
<form method="POST" action="{{ route('borrowing.store') }}" id="borrowForm" class="card p-4">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        {{-- Left: inputs --}}
        <div class="col-lg-6">
            <p class="text-muted small">Select a member and book to start. Confirm that all information are correct before proceeding.</p>

            <label class="form-label">Member</label>
            <input list="memberList" id="memberInput" class="form-control mb-3"
                   placeholder="Search name or ID" autocomplete="off">
            <input type="hidden" name="member_id" id="memberId">
            <datalist id="memberList">
                @foreach ($members as $m)
                    <option value="{{ $m->member_code }} — {{ $m->full_name }}"
                            data-id="{{ $m->member_id }}"
                            data-name="{{ $m->full_name }}"
                            data-code="{{ $m->member_code }}"></option>
                @endforeach
            </datalist>

            <label class="form-label">Book</label>
            <input list="bookList" id="bookInput" class="form-control"
                   placeholder="Search book by title or ID" autocomplete="off">
            <input type="hidden" name="book_id" id="bookId">
            <datalist id="bookList">
                @foreach ($books as $b)
                    <option value="BOOK-{{ $b->book_id }} — {{ $b->title }}"
                            data-id="{{ $b->book_id }}"
                            data-title="{{ $b->title }}"
                            data-author="{{ $b->author }}"
                            data-category="{{ $b->category->category_name ?? '—' }}"
                            data-year="{{ $b->publication_year }}"
                            data-avail="{{ $b->available_quantity }}"></option>
                @endforeach
            </datalist>

            <div id="availBox" class="alert alert-secondary py-2 mt-2 mb-3">Select a book to see availability.</div>

            <label class="form-label">Number of copies</label>
            <input type="number" name="quantity" id="quantity" class="form-control mb-3"
                   min="1" max="{{ $maxCopies }}" value="{{ old('quantity', 1) }}" required>

            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Borrow Date</label>
                    <input type="date" name="borrowing_date" id="borrowDate" class="form-control"
                           min="{{ now()->toDateString() }}"
                           value="{{ old('borrowing_date', now()->toDateString()) }}" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" id="dueDate" class="form-control"
                           min="{{ now()->toDateString() }}"
                           value="{{ old('due_date', now()->addDays($loanDays)->toDateString()) }}" required>
                </div>
            </div>
        </div>

        {{-- Right: live summary --}}
        <div class="col-lg-6">
            <p class="text-muted small">Summary of borrowing transaction. Double check all details before confirming transaction.</p>

            <div class="mb-3">
                <div class="fw-semibold">Member</div>
                <div id="sumMember">—</div>
                <div>Member Code: <span id="sumCode">—</span></div>
            </div>

            <div class="mb-3">
                <div class="fw-semibold">Book Details</div>
                <div id="sumTitle">—</div>
                <div id="sumAuthor"></div>
                <div id="sumCategory"></div>
                <div id="sumYear"></div>
                <div>Copies: <span id="sumQty">1</span></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="fw-semibold">Borrow Date</div>
                    <div id="sumBorrow" class="text-success">—</div>
                </div>
                <div class="col-6">
                    <div class="fw-semibold">Due Date</div>
                    <div id="sumDue" class="text-danger">—</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="submit" id="confirmBtn" class="btn btn-success" disabled>Confirm Transaction</button>
        <a href="{{ route('borrowing') }}" class="btn btn-outline-danger">Cancel</a>
        <button type="reset" class="btn btn-warning">Clear all details</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const loanDays  = {{ $loanDays }};
    const maxCopies = {{ $maxCopies }};
    const $ = id => document.getElementById(id);
    const findOpt = (listId, val) =>
        [...document.querySelectorAll('#' + listId + ' option')].find(o => o.value === val);

    const pad = n => String(n).padStart(2, '0');
    const toIso = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const pretty = s => s
        ? new Date(s + 'T00:00:00').toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
        : '—';

    function refresh() {
        const m = findOpt('memberList', $('memberInput').value);
        $('memberId').value = m ? m.dataset.id : '';
        $('sumMember').textContent = m ? m.dataset.name : '—';
        $('sumCode').textContent = m ? m.dataset.code : '—';

        const b = findOpt('bookList', $('bookInput').value);
        $('bookId').value = b ? b.dataset.id : '';
        $('sumTitle').textContent = b ? b.dataset.title : '—';
        $('sumAuthor').textContent = b ? b.dataset.author : '';
        $('sumCategory').textContent = b ? b.dataset.category : '';
        $('sumYear').textContent = b ? b.dataset.year : '';

        const avail = b ? Number(b.dataset.avail) : 0;
        const qty = parseInt($('quantity').value, 10) || 0;
        const limit = b ? Math.min(avail, maxCopies) : maxCopies;

        // the number of copies can't go above what's available
        $('quantity').max = Math.max(limit, 1);
        $('sumQty').textContent = qty || '—';

        const box = $('availBox');
        if (!b) {
            box.className = 'alert alert-secondary py-2 mt-2 mb-3';
            box.textContent = 'Select a book to see availability.';
        } else if (avail <= 0) {
            box.className = 'alert alert-danger py-2 mt-2 mb-3';
            box.textContent = 'No copies available';
        } else if (qty > avail) {
            box.className = 'alert alert-warning py-2 mt-2 mb-3';
            box.textContent = 'Only ' + avail + ' copies available';
        } else {
            box.className = 'alert alert-success py-2 mt-2 mb-3';
            box.textContent = avail + ' copies available';
        }

        $('sumBorrow').textContent = pretty($('borrowDate').value);
        $('sumDue').textContent = pretty($('dueDate').value);

        $('confirmBtn').disabled = !(m && b && avail > 0 && qty >= 1 && qty <= limit);
    }

    $('memberInput').addEventListener('input', refresh);
    $('bookInput').addEventListener('input', refresh);
    $('quantity').addEventListener('input', refresh);
    $('dueDate').addEventListener('change', refresh);

    // changing the borrow date moves the due date to borrow date + loan days
    $('borrowDate').addEventListener('change', () => {
        if ($('borrowDate').value) {
            const d = new Date($('borrowDate').value + 'T00:00:00');
            d.setDate(d.getDate() + loanDays);
            $('dueDate').value = toIso(d);
        }
        refresh();
    });

    $('borrowForm').addEventListener('reset', () => setTimeout(() => {
        $('memberId').value = '';
        $('bookId').value = '';
        refresh();
    }, 0));

    refresh();
</script>
@endpush