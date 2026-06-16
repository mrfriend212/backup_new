<!DOCTYPE html>
<html lang="fa" dir="rtl" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سامانه</title>
    <!-- Bootstrap 5.3 RTL CSS -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/lib/bootstrap/bootstrap-icons.min.css') }}" rel="stylesheet">
    <style>
        @import url('assets/fonts/Vazirmatn-font-face.css');
        :root, [data-bs-theme] {
            font-family: Vazirmatn, sans-serif;
            --bs-body-font-family: Vazirmatn, sans-serif;
        }
        body, html {
            height: 100%;
            margin: 0;
        }
        .login-bg {
            background-image: url('assets/img/login-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-bg::before {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        [data-bs-theme="light"] .login-card {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        [data-bs-theme="light"] .login-card .text-white {
            color: #212529 !important;
        }
        [data-bs-theme="light"] .login-card .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            color: #212529;
            border-color: #dee2e6;
        }
        .login-card .form-control {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .login-card .form-control:focus {
            background-color: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: none;
            color: white;
        }
        [data-bs-theme="light"] .login-card .form-control:focus {
            background-color: #fff;
            color: #212529;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .login-card .form-label {
            color: #ddd;
        }
        [data-bs-theme="light"] .login-card .form-label {
            color: #495057;
        }
    </style>
</head>
<body class="bg-body">
    <div class="login-bg">
        <div class="card login-card p-4 rounded-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                    <h3 class="text-white fw-bold mt-2">ورود به سامانه</h3>
                    <p class="text-white text-opacity-75 small">لطفا نام کاربری و رمز عبور خود را وارد کنید</p>
                </div>
                
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label small">نام کاربری</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-white"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control border-start-0" name="username" placeholder="نام کاربری خود را وارد کنید" required dir="ltr">
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label small mb-0">رمز عبور</label>
                            <a href="javascript:void(0)" class="text-primary text-decoration-none small">فراموشی رمز؟</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-white"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0" name="password" placeholder="••••••••" required dir="ltr">
                            <button class="btn btn-outline-secondary border text-white border-start-0" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label text-white small" for="rememberMe">مرا به خاطر بسپار</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold mb-3">ورود</button>
                    <div class="text-center">
                        <span class="text-white text-opacity-75 small">حساب کاربری ندارید؟</span>
                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">ثبت نام کنید</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS API -->
    <script src="{{ asset('assets/lib/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const loginForm = document.querySelector('#loginForm');
      
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
