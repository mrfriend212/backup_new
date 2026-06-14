<!doctype html>
<html lang="ar" dir="rtl" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap 5.3 RTL CSS -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap-icons.min.css') }}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/src/style.css') }}" />
</head>

<body class="d-flex flex-column">
    <!-- Topbar -->
    <header class="navbar navbar-dark bg-dark sticky-top p-0 flex-md-nowrap shadow border-bottom border-secondary">
        <div class="d-flex align-items-center w-100">

            <!-- Right side branding (Start in RTL) -->
            <div id="brandWrapper" class="col-md-3 col-lg-2 px-3 m-0 d-none d-md-flex align-items-center justify-content-between brand-wrapper transition-all">
                <a href="#" id="brandLogoLink" class="d-flex align-items-center text-decoration-none text-light gap-3">
                    <img src="https://placehold.co/32x32/495057/FFF?text=Logo" alt="Logo" class="rounded logo-img" width="32" height="32">
                    <span class="fs-5 m-0 brand-text">{{ env('APP_SHORT_NAME') }}</span>
                </a>
                <button id="desktopSidebarToggle" class="btn btn-link text-light p-0 border-0 shadow-none text-decoration-none" title="تغییر وضعیت نوار کناری">
                    <i class="bi bi-list fs-3"></i>
                </button>
            </div>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler d-md-none border-0 shadow-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Global Search -->
            <div class="d-none d-md-flex align-items-center flex-grow-1 px-3">
                <div class="position-relative w-100" style="max-width: 400px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark border-secondary text-secondary border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-dark border-secondary text-light shadow-none border-start-0" id="globalSearchInput" placeholder="جستجو در سیستم..." autocomplete="off" style="box-shadow: none !important; border-right-color: transparent;">
                    </div>
                    <div id="searchResultsDropdown" class="dropdown-menu dropdown-menu-dark w-100 shadow mt-1 p-0" style="display: none; z-index: 1050;">
                        <div class="list-group list-group-flush rounded" id="searchResultsList" style="max-height: 300px; overflow-y: auto;">
                            <!-- Search results will be injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left side Controls (End in RTL) -->
            <div class="d-flex align-items-center ms-auto p-3 gap-3">
                <!-- Theme Toggle -->
                <button id="themeToggleBtn" class="btn btn-link text-light p-0 border-0 shadow-none text-decoration-none" title="تغییر تم">
                    <i class="bi bi-moon fs-5" id="themeIcon"></i>
                </button>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle outline-none gap-2" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-4"></i>
                        <span class="d-none d-sm-inline">ادمین سیستم</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i class="bi bi-person"></i> پروفایل</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i class="bi bi-gear"></i> تنظیمات</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="#"><i class="bi bi-box-arrow-right"></i> خروج</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </header>

    <div class="container-fluid flex-grow-1 overflow-hidden d-flex flex-column wrapper-view">
        <div class="row w-100 h-100 m-0">

            <!-- Sidebar (Right side, Start in RTL) -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 bg-dark sidebar offcanvas-md offcanvas-start border-end border-secondary p-0 h-100 transition-all" tabindex="-1" aria-labelledby="offcanvasSidebarLabel">
                <div class="offcanvas-header d-md-none border-bottom border-secondary" data-bs-theme="dark">
                    <h5 class="offcanvas-title text-white" id="offcanvasSidebarLabel">منو</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column h-100 w-100 p-0 py-md-3">
                    <ul class="nav flex-column mb-auto px-2 sidebar-nav mt-3 mt-md-0">
                        <li class="nav-item">
                            <a class="nav-link active rounded d-flex align-items-center mb-1 text-white gap-3" href="{{ url('dashboard') }}" target="main-frame">
                                <i class="bi bi-speedometer2 fs-5"></i> <span>داشبورد</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Logout Button fixed at bottom of sidebar -->
                    <div class="mt-auto px-3 border-top border-secondary pt-3 pb-3 pb-md-0">
                        <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-right"></i> <span>خروج</span>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area (Left side, End in RTL) -->
            <main id="mainContent" class="col-md-9 col-lg-10 p-0 h-100 overflow-hidden d-flex flex-column bg-body transition-all position-relative">
                <!-- Loading Progress Bar -->
                <div id="iframeLoadingBar" class="progress position-absolute top-0 start-0 w-100 rounded-0" style="height: 3px; z-index: 1050; display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <iframe name="main-frame" id="main-frame" src="{{ url('dashboard') }}" title="Main Content" class="w-100 h-100 border-0 flex-grow-1"></iframe>
            </main>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-2 border-top border-secondary mt-auto">
        <small>کپی‌رایت &copy; ۲۰۲۶. تمامی حقوق محفوظ است.</small>
    </footer>

    <!-- Bootstrap 5 bundled JS (includes Popper for dropdowns) -->
    <script src="{{ asset('assets/lib/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/src/script.js') }}"></script>
</body>

</html>