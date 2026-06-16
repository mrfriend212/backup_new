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
            <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right"></i> <span>خروج</span>
            </a>
        </div>
    </div>
</nav>