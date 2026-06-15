<!doctype html>
<html lang="ar" dir="rtl" data-bs-theme="dark">

@include('layouts.panel.master.partial.head')

<body class="d-flex flex-column">
    <!-- Topbar -->
    @include('layouts.panel.master.partial.header')

    <div class="container-fluid flex-grow-1 overflow-hidden d-flex flex-column wrapper-view">
        <div class="row w-100 h-100 m-0">

            <!-- Sidebar (Right side, Start in RTL) -->
            @include('layouts.panel.master.partial.sidebar')

            <!-- Main Content Area (Left side, End in RTL) -->
            @include('layouts.panel.master.partial.main-content-area')

        </div>
    </div>

    <!-- Footer -->
    @include('layouts.panel.master.partial.footer')

    @include('layouts.panel.master.partial.script')
</body>

</html>