@extends('layouts.app')

@section('title', 'Analytics')
@section('heading', 'Analytics')

@section('content')

    <style>
        /* =====================================================
           COLOR VARIABLES: change a color here, it updates
           the stat cards AND the charts.
           ===================================================== */
        .analytics {
            /* Main family (matches the dashboard) */
            --an-deep-teal:   #17495b;  /* dark navy-teal -> Total Books / Total Borrowings cards, Borrowed slice */
            --an-cyan:        #0dc8ef;  /* bright cyan    -> Members / Available Copies cards, line chart, Returned slice */
            --an-sky:         #7fdcf3;  /* mid cyan       -> chart accents */
            --an-light-cyan:  #b8f1fc;  /* pale cyan      -> Active Loans / Return Rate cards */

            /* Complement of cyan (opposite on the color wheel) */
            --an-coral:       #ff6b4a;  /* coral          -> Overdue slice, "days late" pills */
            --an-coral-soft:  #ffb09c;  /* soft coral     -> chart accents */

            /* Extra accents */
            --an-sand:        #ffb74d;  /* warm amber     -> Avg Loan Days card, chart accents */
            --an-black:       #000000;  /* black          -> Overdue card */

            /* Text and surfaces */
            --an-ink:         #12343b;  /* main text */
            --an-muted:       #6b8a8e;  /* subtitles, axis labels */
            --an-border:      #d6f4fb;  /* card borders */
            --an-track:       #e8f8fc;  /* empty part of progress bars */
            --an-grid:        rgba(13, 200, 239, .12); /* chart gridlines */
        }

        /* ---------- Stat cards (reusing the dashboard classes) ---------- */
        .analytics .stat-card {
            box-shadow: none !important;
            transition: none;
        }

        .analytics .stat-card .stat-icon,
        .analytics .stat-card .stat-label,
        .analytics .stat-card .stat-value {
            color: inherit;
        }

        .analytics .an-card--deep-teal  { background: var(--an-deep-teal);  color: #fff; }
        .analytics .an-card--cyan       { background: var(--an-cyan);       color: #fff; }
        .analytics .an-card--light-cyan { background: var(--an-light-cyan); color: var(--an-deep-teal); }
        .analytics .an-card--black      { background: var(--an-black);      color: #fff; }
        .analytics .an-card--sand       { background: var(--an-sand);       color: var(--an-ink); }

        /* ---------- Chart cards (round, flat, no shadow) ---------- */
        .analytics .a-card {
            background: #fff;
            border: 1px solid var(--an-border);
            border-radius: 28px;
            padding: 1.5rem;
            box-shadow: none;
            height: 100%;
        }

        .analytics .a-card h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--an-ink);
            margin: 0;
        }

        .analytics .a-card .a-sub {
            font-size: .82rem;
            color: var(--an-muted);
            margin: .15rem 0 1.1rem;
        }

        .analytics .chart-box {
            position: relative;
            height: 280px;
        }

        .analytics .chart-box--sm {
            height: 260px;
        }

        .analytics .a-empty {
            display: grid;
            place-items: center;
            height: 100%;
            min-height: 120px;
            color: var(--an-muted);
            font-size: .9rem;
        }

        /* ---------- Ranked list (most popular books) ---------- */
        .analytics .rank-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: .9rem;
        }

        .analytics .rank-row {
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .analytics .rank-num {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: .85rem;
            font-weight: 700;
            background: var(--an-light-cyan);
            color: var(--an-deep-teal);
        }

        .analytics .rank-row:nth-child(1) .rank-num {
            background: var(--an-deep-teal);
            color: #fff;
        }

        .analytics .rank-row:nth-child(2) .rank-num {
            background: var(--an-cyan);
            color: #fff;
        }

        .analytics .rank-info {
            flex: 1;
            min-width: 0;
        }

        .analytics .rank-title {
            margin: 0;
            font-size: .92rem;
            font-weight: 600;
            color: var(--an-ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .analytics .rank-author {
            margin: 0 0 .3rem;
            font-size: .76rem;
            color: var(--an-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .analytics .rank-track {
            height: 8px;
            border-radius: 999px;
            background: var(--an-track);
            overflow: hidden;
        }

        .analytics .rank-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--an-cyan);
        }

        .analytics .rank-row:nth-child(1) .rank-fill {
            background: var(--an-deep-teal);
        }

        .analytics .rank-count {
            flex-shrink: 0;
            padding: .25rem .7rem;
            border-radius: 999px;
            background: var(--an-track);
            color: var(--an-deep-teal);
            font-size: .78rem;
            font-weight: 700;
        }

        /* ---------- Overdue table ---------- */
        .analytics .an-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 .5rem;
        }

        .analytics .an-table th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--an-muted);
            font-weight: 600;
            padding: 0 1rem .25rem;
            white-space: nowrap;
        }

        .analytics .an-table td {
            padding: .75rem 1rem;
            background: var(--an-track);
            color: var(--an-ink);
            font-size: .9rem;
        }

        .analytics .an-table td:first-child {
            border-radius: 999px 0 0 999px;
            padding-left: 1.25rem;
        }

        .analytics .an-table td:last-child {
            border-radius: 0 999px 999px 0;
            padding-right: 1.25rem;
        }

        .analytics .late-pill {
            display: inline-block;
            padding: .2rem .7rem;
            border-radius: 999px;
            background: var(--an-coral);
            color: #fff;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }
    </style>

    <div class="analytics">

        <h1 class="page-title page-title--spaced">Analytics</h1>

        <!-- Stat Cards -->
        <div class="stat-panel mb-4">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3">

                <div class="col">
                    <div class="stat-card an-card--deep-teal">
                        <i class="bi bi-book stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Total Books</p>
                            <p class="stat-value">{{ number_format($totalBooks) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--cyan">
                        <i class="bi bi-people stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Members</p>
                            <p class="stat-value">{{ number_format($totalMembers) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--light-cyan">
                        <i class="bi bi-arrow-left-right stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Active Loans</p>
                            <p class="stat-value">{{ number_format($activeLoans) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--black">
                        <i class="bi bi-exclamation-triangle stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Overdue</p>
                            <p class="stat-value">{{ number_format($overdue) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--deep-teal">
                        <i class="bi bi-journal-bookmark stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Total Borrowings</p>
                            <p class="stat-value">{{ number_format($totalBorrowings) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--cyan">
                        <i class="bi bi-check2-square stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Available Copies</p>
                            <p class="stat-value">{{ number_format($availableCopies) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--light-cyan">
                        <i class="bi bi-arrow-return-left stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Return Rate</p>
                            <p class="stat-value">{{ $returnRate }}%</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="stat-card an-card--sand">
                        <i class="bi bi-hourglass-split stat-icon"></i>
                        <div class="stat-body">
                            <p class="stat-label">Avg Loan (days)</p>
                            <p class="stat-value">{{ $avgLoanDays }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Row 1: trend + status -->
        <div class="row g-3 mb-3">

            <div class="col-lg-8">
                <div class="a-card">
                    <h2>Borrowings Over Time</h2>
                    <p class="a-sub">Books borrowed per month, last 6 months</p>
                    <div class="chart-box">
                        <canvas id="monthChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="a-card">
                    <h2>Borrowing Status</h2>
                    <p class="a-sub">Borrowed, returned and overdue</p>
                    <div class="chart-box">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- Row 2: popular books + top borrowers -->
        <div class="row g-3 mb-3">

            <div class="col-lg-6">
                <div class="a-card">
                    <h2>Most Popular Books</h2>
                    <p class="a-sub">Top 8 by number of times borrowed</p>

                    @if ($topBooks->count())
                        @php $maxBorrows = $topBooks->max('borrowings_count'); @endphp

                        <ol class="rank-list">
                            @foreach ($topBooks as $book)
                                <li class="rank-row">
                                    <span class="rank-num">{{ $loop->iteration }}</span>

                                    <div class="rank-info">
                                        <p class="rank-title" title="{{ $book->title }}">{{ $book->title }}</p>
                                        <p class="rank-author">{{ $book->author }}</p>
                                        <div class="rank-track">
                                            <div class="rank-fill"
                                                 style="width: {{ ($book->borrowings_count / $maxBorrows) * 100 }}%"></div>
                                        </div>
                                    </div>

                                    <span class="rank-count">{{ $book->borrowings_count }}x</span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <div class="a-empty">No borrowings yet.</div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="a-card">
                    <h2>Top Borrowers</h2>
                    <p class="a-sub">Members who borrowed the most books</p>
                    <div class="chart-box">
                        @if (count($memberData))
                            <canvas id="memberChart"></canvas>
                        @else
                            <div class="a-empty">No borrowings yet.</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Row 3: category + weekday -->
        <div class="row g-3 mb-3">

            <div class="col-lg-5">
                <div class="a-card">
                    <h2>Books by Category</h2>
                    <p class="a-sub">Top 6 categories, the rest grouped as Others</p>
                    <div class="chart-box chart-box--sm">
                        @if (count($categoryData))
                            <canvas id="categoryChart"></canvas>
                        @else
                            <div class="a-empty">No categories yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="a-card">
                    <h2>Busiest Days</h2>
                    <p class="a-sub">Which weekdays books get borrowed the most</p>
                    <div class="chart-box chart-box--sm">
                        <canvas id="weekdayChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- Row 4: overdue list -->
        <div class="row g-3">
            <div class="col-12">
                <div class="a-card">
                    <h2>Currently Overdue</h2>
                    <p class="a-sub">The 5 loans that are the most late</p>

                    @if ($overdueLoans->count())
                        <div class="table-responsive">
                            <table class="an-table">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Late By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($overdueLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->member->full_name ?? 'Unknown' }}</td>
                                            <td>{{ $loan->book->title ?? 'Unknown' }}</td>
                                            <td>{{ $loan->due_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="late-pill">
                                                    {{ abs((int) $loan->due_date->diffInDays(today())) }} day(s)
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="a-empty">Nothing overdue. Great job!</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ---------- Read colors from the CSS variables above ----------
        const root = getComputedStyle(document.querySelector('.analytics'));
        const v = (name) => root.getPropertyValue(name).trim();

        const deepTeal  = v('--an-deep-teal');
        const cyan      = v('--an-cyan');
        const sky       = v('--an-sky');
        const coral     = v('--an-coral');
        const coralSoft = v('--an-coral-soft');
        const sand      = v('--an-sand');
        const ink       = v('--an-ink');
        const muted     = v('--an-muted');
        const grid      = v('--an-grid');

        // ---------- Global defaults ----------
        Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
        Chart.defaults.color = muted;

        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.pointStyle = 'circle';
        Chart.defaults.plugins.legend.labels.boxWidth = 8;
        Chart.defaults.plugins.legend.labels.padding = 14;

        Chart.defaults.plugins.tooltip.backgroundColor = deepTeal;
        Chart.defaults.plugins.tooltip.cornerRadius = 14;
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.titleColor = '#fff';
        Chart.defaults.plugins.tooltip.bodyColor = '#fff';
        Chart.defaults.plugins.tooltip.boxPadding = 6;
        Chart.defaults.plugins.tooltip.usePointStyle = true;

        // Soft grid, no hard axis lines
        const softScales = {
            x: {
                grid: { display: false },
                border: { display: false }
            },
            y: {
                beginAtZero: true,
                ticks: { precision: 0 },
                grid: { color: grid },
                border: { display: false }
            }
        };

        // Shortens long labels so they never get cut off
        const shorten = (label, max) => label.length > max ? label.slice(0, max) + '…' : label;

        // ---------- Data from Laravel ----------
        const monthLabels    = @json($monthLabels);
        const monthData      = @json($monthData);
        const statusData     = @json($statusData);
        const categoryLabels = @json($categoryLabels);
        const categoryData   = @json($categoryData);
        const memberLabels   = @json($memberLabels);
        const memberData     = @json($memberData);
        const weekdayLabels  = @json($weekdayLabels);
        const weekdayData    = @json($weekdayData);

        // ---------- 1. Borrowings over time (smooth line) ----------
        const monthCtx = document.getElementById('monthChart').getContext('2d');

        const gradient = monthCtx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(13, 200, 239, .35)');   // fades from --an-cyan
        gradient.addColorStop(1, 'rgba(13, 200, 239, 0)');

        new Chart(monthCtx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Borrowings',
                    data: monthData,
                    borderColor: cyan,
                    backgroundColor: gradient,
                    borderWidth: 4,
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round',
                    fill: true,
                    tension: 0.45,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: cyan,
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: softScales
            }
        });

        // ---------- 2. Borrowing status (rounded doughnut) ----------
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Borrowed', 'Returned', 'Overdue'],
                datasets: [{
                    data: statusData,
                    backgroundColor: [deepTeal, cyan, coral],
                    borderWidth: 0,
                    borderRadius: 14,
                    spacing: 5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // ---------- 3. Top borrowers (pill-shaped horizontal bars) ----------
        const memberCanvas = document.getElementById('memberChart');

        if (memberCanvas) {
            new Chart(memberCanvas, {
                type: 'bar',
                data: {
                    labels: memberLabels,
                    datasets: [{
                        label: 'Books borrowed',
                        data: memberData,
                        backgroundColor: [deepTeal, cyan, sky, sand, coralSoft],
                        borderRadius: 999,
                        borderSkipped: false,
                        barThickness: 24
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: grid },
                            border: { display: false }
                        },
                        y: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: ink,
                                callback: function (value) {
                                    return shorten(this.getLabelForValue(value), 22);
                                }
                            }
                        }
                    }
                }
            });
        }

        // ---------- 4. Books by category (rounded doughnut, legend at the bottom) ----------
        const categoryCanvas = document.getElementById('categoryChart');

        if (categoryCanvas) {
            // 6 categories + "Others" (muted gray)
            const palette = [deepTeal, cyan, coral, sky, sand, coralSoft, muted];

            new Chart(categoryCanvas, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: categoryLabels.map((_, i) => palette[i % palette.length]),
                        borderWidth: 0,
                        borderRadius: 12,
                        spacing: 4,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                generateLabels: function (chart) {
                                    const base = Chart.overrides.doughnut.plugins.legend.labels.generateLabels(chart);
                                    base.forEach(function (item) {
                                        item.text = shorten(item.text, 16);
                                    });
                                    return base;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ---------- 5. Busiest days (rounded bars) ----------
        const maxDay = Math.max.apply(null, weekdayData);

        new Chart(document.getElementById('weekdayChart'), {
            type: 'bar',
            data: {
                labels: weekdayLabels,
                datasets: [{
                    label: 'Borrowings',
                    data: weekdayData,
                    // the busiest day is highlighted in deep teal
                    backgroundColor: weekdayData.map(function (n) {
                        return (n === maxDay && maxDay > 0) ? deepTeal : sky;
                    }),
                    borderRadius: 14,
                    borderSkipped: false,
                    maxBarThickness: 44
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: softScales
            }
        });

    });
</script>
@endpush