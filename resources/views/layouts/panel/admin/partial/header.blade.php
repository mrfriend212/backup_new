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
            <!-- <div class="d-none d-md-flex align-items-center flex-grow-1 px-3">
                <div class="position-relative w-100" style="max-width: 400px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark border-secondary text-secondary border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-dark border-secondary text-light shadow-none border-start-0" id="globalSearchInput" placeholder="جستجو در سیستم..." autocomplete="off" style="box-shadow: none !important; border-right-color: transparent;">
                    </div>
                    <div id="searchResultsDropdown" class="dropdown-menu dropdown-menu-dark w-100 shadow mt-1 p-0" style="display: none; z-index: 1050;">
                        <div class="list-group list-group-flush rounded" id="searchResultsList" style="max-height: 300px; overflow-y: auto;"> -->
                            <!-- Search results will be injected here -->
                        <!-- </div>
                    </div>
                </div>
            </div> -->

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
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }} {{auth()->user()->family}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i class="bi bi-person"></i>پروفایل ادمین</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i class="bi bi-gear"></i> تنظیمات</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}""><i class="bi bi-box-arrow-right"></i> خروج</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </header>