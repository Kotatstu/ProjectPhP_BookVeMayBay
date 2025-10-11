<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vé Máy Bay</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: "Segoe UI", sans-serif;
        }

        /* Thanh navbar */
        .navbar {
            background-color: #0d6efd;
            color: white !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar .btn {
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .navbar .btn-outline-light:hover {
            background-color: white;
            color: #0d6efd;
        }

        .navbar .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .container {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            {{-- Bên trái --}}
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="plane" class="text-white"></i>
                <span class="fw-semibold text-white">Book Vé máy Bay</span>
            </div>

            {{-- Bên phải --}}
            <div class="d-flex align-items-center gap-3">
                @guest   {{-- Nếu chưa đăng nhập --}}
                    <a class="btn btn-outline-light btn-sm" href="/login">
                        <i data-lucide="log-in"></i> Đăng nhập
                    </a>
                    <a class="btn btn-outline-light btn-sm" href="/register">
                        <i data-lucide="user-plus"></i> Đăng ký
                    </a>
                @endguest

                @auth   {{-- Nếu đã đăng nhập --}}
                    <a class="btn btn-outline-light btn-sm" href="/users-view">
                        <i data-lucide="users"></i> Xem danh sách
                    </a>

                    <div class="user-info">
                        <i data-lucide="user"></i>
                        Xin chào, <strong>{{ Auth::user()->name }}</strong>
                    </div>

                    <a class="btn btn-outline-light btn-sm"
                       href="/logout"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-lucide="log-out"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="/logout" method="POST" class="d-none">
                        @csrf
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        {{-- Nội dung riêng của từng view --}}
        @yield('content')
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
