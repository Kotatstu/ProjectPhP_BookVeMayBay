<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vé Máy Bay</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Main content */
        .main-wrapper {
            flex: 1;
            padding-top: 90px; /* bù navbar fixed-top */
        }

        /* Footer luôn ở dưới */
        footer {
            flex-shrink: 0;
            margin-top: auto;
        }

        /* Navbar ẩn mượt */
        .navbar.hidden {
            transform: translateY(-100%);
            transition: transform 0.3s ease-in-out;
        }

        /* Toast animations */
        .toast {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .toast.show {
            opacity: 1;
        }

        .toast.hide {
            opacity: 0;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top bg-primary shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none" aria-label="Về trang chủ">
                <i data-lucide="plane" class="text-white"></i>
                <span class="fw-semibold text-white">Book Vé Máy Bay</span>
            </a>

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
                        <i data-lucide="info"></i> Cá nhân
                    </a>

                    <a class="btn btn-outline-light btn-sm" href="{{ route('cart.index') }}">
                        <i data-lucide="bookmark"></i> Vé đã lưu
                    </a>

                    <a class="btn btn-outline-light btn-sm" href="/logout"
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

    <!-- Toast notifications -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        @if (session('success'))
            <div class="toast toast-success align-items-center border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast toast-error align-items-center border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="toast toast-info align-items-center border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">{{ session('info') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper container">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-light pt-5 pb-4">
        <div class="container-fluid px-5">
            <div class="row gy-4">

                <div class="col-md-4">
                    <h5 class="text-uppercase fw-bold mb-3">Vé Máy Bay 24/7</h5>
                    <p>Đặt vé máy bay trực tuyến nhanh chóng, tiện lợi, hỗ trợ 24/7.</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Liên hệ</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> 140 Lê Trọng Tấn, TP. Hồ Chí Minh</li>
                        <li><i class="bi bi-telephone"></i> +84 123 456 789</li>
                        <li><i class="bi bi-envelope"></i> support@vemaybay247.vn</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Theo dõi chúng tôi</h6>
                    <div>
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter fs-4"></i></a>
                    </div>
                </div>

            </div>

            <hr class="border-secondary my-4">

            <div class="text-center small">
                © {{ date('Y') }} Vé Máy Bay 24/7. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <!-- Navbar hide/show on scroll -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let lastScroll = 0;
            const navbar = document.querySelector(".navbar");

            window.addEventListener("scroll", function () {
                let current = window.pageYOffset || document.documentElement.scrollTop;
                if (current > lastScroll && current > 50) {
                    navbar.classList.add("hidden");
                } else {
                    navbar.classList.remove("hidden");
                }
                lastScroll = current <= 0 ? 0 : current;
            });
        });
    </script>

    <!-- Toast auto-show and hide -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.classList.add('show'); // hiển thị toast
                setTimeout(() => {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                }, 3000);

                toast.querySelector('.btn-close')?.addEventListener('click', () => {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
