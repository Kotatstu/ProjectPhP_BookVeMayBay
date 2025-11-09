<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang quản trị')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            background-color: #ffffff;
            color: #212529;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #0d6efd !important;
        }

        .navbar-brand, 
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #dceeff !important;
        }

        footer {
            background-color: #f8f9fa;
            color: #495057;
            border-top: 1px solid #dee2e6;
        }

        footer span {
            color: #0d6efd;
            font-weight: 600;
        }

        .lucide-icon {
            width: 20px;
            height: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg shadow-sm py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/admin">
                <i data-lucide="layout-dashboard" class="lucide-icon"></i>
                Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-1" href="/home">
                            <i data-lucide="home"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-1" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i data-lucide="log-out"></i> Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="text-center mt-5 py-3">
        © 2025 - Hệ thống đặt vé máy bay | <span>Laravel Framework</span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>

    @stack('scripts')
</body>
</html>