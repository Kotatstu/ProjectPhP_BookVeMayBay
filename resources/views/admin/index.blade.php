<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background: #f8fafc;
        }
        .navbar {
            background: #0d6efd;
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: #fff !important;
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .container {
            margin-top: 40px;
        }
        .card {
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .logout-btn {
            border: none;
            background: none;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .logout-btn:hover {
            text-decoration: underline;
        }
        .badge {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    {{-- Thanh điều hướng --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i data-lucide="shield"></i> Admin Dashboard
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 d-flex align-items-center gap-1">
                    <i data-lucide="user"></i>
                    Xin chào, <b>{{ Auth::user()->name }}</b>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i data-lucide="log-out"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Nội dung chính --}}
    <div class="container">
        <div class="card p-4">
            <h3 class="mb-3 text-center text-primary d-flex justify-content-center align-items-center gap-2">
                <i data-lucide="users"></i> Danh sách người dùng
            </h3>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i data-lucide="check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i data-lucide="alert-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <table class="table table-bordered table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th><i data-lucide="hash"></i> ID</th>
                        <th><i data-lucide="user"></i> Tên</th>
                        <th><i data-lucide="mail"></i> Email</th>
                        <th><i data-lucide="key-round"></i> Quyền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->id <= 3)
                                    <span class="badge bg-success d-inline-flex align-items-center gap-1">
                                        <i data-lucide="shield-check"></i> Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary d-inline-flex align-items-center gap-1">
                                        <i data-lucide="user"></i> User
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center mt-3">
                <a href="/home" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                    <i data-lucide="home"></i> Quay lại Home
                </a>
            </div>
        </div>
    </div>

    {{-- Kích hoạt Lucide --}}
    <script>
        lucide.createIcons();
    </script>

</body>
</html>
