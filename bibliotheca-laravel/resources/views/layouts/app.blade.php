<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bibliotheca') | Bibliotheca</title>

    <!-- Google Fonts: Momo Trust Display (headings) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Momo+Trust+Display&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    @php
        $navItems = [
            ['Dashboard',  'dashboard',  'bi-house'],
            ['Books',      'books',      'bi-book'],
            ['Borrowing',  'borrowing',  'bi-clock'],
            ['Return',     'return',     'bi-arrow-repeat'],
            ['Members',    'members',    'bi-person-circle'],
            ['Categories', 'categories', 'bi-tags'],
            ['Analytics',  'analytics',  'bi-graph-up'],
        ];
    @endphp

    <div class="d-md-flex">

        <!-- ========== Sidebar (offcanvas on mobile, fixed panel on md+) ========== -->
        <aside class="sidebar offcanvas-md offcanvas-start d-flex flex-column" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">

            <!-- Close button (mobile only) -->
            <div class="offcanvas-header d-md-none">
                <h5 class="offcanvas-title visually-hidden" id="sidebarLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column">
                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Bibliotheca" class="img-fluid">
                </div>

                <!-- Navigation: "active" is added when the current route matches -->
                <ul class="nav flex-column">
                    @foreach ($navItems as [$label, $routeName, $icon])
                        <li class="nav-item">
                            <a href="{{ route($routeName) }}"
                               class="nav-link {{ request()->routeIs($routeName) ? 'active' : '' }}"
                               @if (request()->routeIs($routeName)) aria-current="page" @endif>
                                <i class="bi {{ $icon }}"></i> {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- User + logout (pinned to bottom) -->
                <div class="sidebar-footer mt-auto">
                    <div class="sidebar-user d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i> Administrator
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-logout w-100">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ========== Main ========== -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100">

            <!-- Mobile top bar -->
            <header class="mobile-topbar d-flex d-md-none align-items-center justify-content-between">
                <div class="sidebar-logo d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Bibliotheca" class="img-fluid">
                </div>
                <button class="btn btn-menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <main class="main-content">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Bootstrap JS (needed for the mobile offcanvas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>