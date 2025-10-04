<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Auth</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                @guest   {{-- Nếu chưa đăng nhập --}}
                    <a class="btn btn-outline-primary me-2" href="/login">Login</a>
                    <a class="btn btn-outline-success" href="/register">Register</a>
                @endguest

                @auth   {{-- Nếu đã đăng nhập --}}
                    <a class="btn btn-outline-info" href="/users-view">Xem danh sách</a>
                @endauth
            </div>

            @auth
            <div class="d-flex align-items-center">
                <span class="me-3">Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                <a class="btn btn-outline-danger btn-sm"
                   href="/logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="/logout" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container">
        {{-- Nội dung riêng của từng view sẽ hiển thị tại đây --}}
        @yield('content')
    </div>
</body>
</html>
