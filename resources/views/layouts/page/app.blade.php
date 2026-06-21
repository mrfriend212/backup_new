<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="dark">

@include('layouts.page.partial.head')

<body>
    <!-- Blank content with only a simple border around the main content wrapper -->
    <div class="content-border">
        <!-- Content Navigation -->
        @include('layouts.page.partial.nav')

        <!-- Content Title -->
         @hasSection('title')
            <h4 class="fw-bold mb-4">  
                @yield('title')
            </h4>
        @endif

        <main class="flex-grow-1" style="overflow-y: auto; height: calc(100vh - 56px);">
            <div class="container-fluid py-3">
                <!-- Content Body -->
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot }}
                @endif
            </div>
        </main>
    </div>

    @include('layouts.page.partial.script')
</body>

</html>