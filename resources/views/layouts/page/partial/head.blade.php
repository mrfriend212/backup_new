<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Bootstrap 5.3 RTL CSS -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/src/style.css') }}">

    @yield('css')

    @livewireStyles
    
</head>