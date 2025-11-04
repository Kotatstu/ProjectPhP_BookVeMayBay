<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vé Máy Bay</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none" aria-label="Về trang chủ">
                    <i data-lucide="plane" class="text-white"></i>
                    <span class="fw-semibold text-white">Book Vé máy Bay</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                @guest
                    <a class="btn btn-outline-light btn-sm" href="/login">
                        <i data-lucide="log-in"></i> Đăng nhập
                    </a>
                    <a class="btn btn-outline-light btn-sm" href="/register">
                        <i data-lucide="user-plus"></i> Đăng ký
                    </a>
                @endguest

                @auth
                    {{-- Nếu là admin thì hiện nút Quản trị --}}
                    @if (Auth::user()->isAdmin())
                        <a class="btn btn-outline-light btn-sm" href="{{ url('/admin') }}">
                            <i data-lucide="settings"></i> Quản trị
                        </a>
                    @endif

                    <div class="user-info text-white">
                        <i data-lucide="user"></i>
                        Xin chào, <strong>{{ Auth::user()->name }}</strong>
                    </div>

                    <a class="btn btn-outline-light btn-sm" href="{{ route('user.info') }}">
                        <i data-lucide="info"></i> Thông tin cá nhân
                    </a>

                    <a class="btn btn-outline-light btn-sm" href="{{ route('cart.index') }}">
                        <i data-lucide="bookmark"></i> Vé đã lưu
                    </a>

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

    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <script>
        lucide.createIcons();
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let lastScrollTop = 0;
        const navbar = document.querySelector(".navbar");

        window.addEventListener("scroll", function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > 50) {
                navbar.classList.add("hidden");
            } else {
                navbar.classList.remove("hidden");
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
