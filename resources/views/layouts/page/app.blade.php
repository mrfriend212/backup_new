<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="dark">

@include('layouts.page.partial.head')

<body>
    <!-- Blank content with only a simple border around the main content wrapper -->
    <div class="content-border">
        <!-- Content Navigation -->
        @include('layouts.page.partial.nav')

        <!-- Content Title -->
        <h4 class="fw-bold mb-4">
            @hasSection('title')
                @yield('title')
            @else
                {{ $title ?? 'عنوان صفحه' }}
            @endif
        </h4>

        <!-- Content Body -->
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </div>

    @include('layouts.page.partial.script')
</body>

</html>