<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد</title>
    <!-- Bootstrap 5.3 RTL CSS -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/src/style.css') }}">
</head>

<body>
    <!-- Blank content with only a simple border around the main content wrapper -->
    <div class="content-border">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">خانه</a></li>
                <li class="breadcrumb-item active" aria-current="page">داشبورد</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-4">باکس ساده</h4>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 fw-bold">عنوان باکس ساده</h5>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-0">
                    <h1>نوع کاربر : {{auth()->user()->user_type}}</h1>
                </p>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS for Dropdowns -->
    <script src="{{ asset('assets/lib/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>