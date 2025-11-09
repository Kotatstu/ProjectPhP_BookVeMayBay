@extends('layouts.admin')

@section('title', 'Bảng điều khiển quản trị')

@section('content')
<div class="admin-dashboard py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark mb-2 display-5">🎯 Bảng điều khiển quản trị</h1>
            <p class="text-muted fs-5">Theo dõi và quản lý toàn bộ hệ thống đặt vé máy bay</p>
        </div>

        <div class="row g-4 justify-content-center">

            @php
    $cards = [
        ['icon' => 'users', 'title' => 'Người dùng', 'desc' => 'Quản lý danh sách và quyền truy cập', 'color' => 'primary', 'route' => 'admin.users'],
        ['icon' => 'plane', 'title' => 'Chuyến bay', 'desc' => 'Theo dõi và cập nhật lộ trình', 'color' => 'success', 'route' => 'admin.flights'],
        ['icon' => 'ticket', 'title' => 'Vé', 'desc' => 'Quản lý thông tin đặt chỗ và mã vé', 'color' => 'warning', 'route' => 'admin.tickets.index'],
        ['icon' => 'credit-card', 'title' => 'Thanh toán', 'desc' => 'Kiểm soát và đối soát giao dịch', 'color' => 'info', 'route' => 'admin.fares.index'],
        ['icon' => 'bar-chart-3', 'title' => 'Báo cáo', 'desc' => 'Phân tích dữ liệu và biểu đồ thống kê', 'color' => 'secondary', 'route' => 'admin.revenue'],
        ['icon' => 'users-round', 'title' => 'Thành viên nhóm', 'desc' => 'Thông tin & vai trò trong dự án', 'color' => 'danger', 'route' => 'members'],
        ['icon' => 'user', 'title' => 'Hồ sơ', 'desc' => 'Cập nhật và chỉnh sửa thông tin cá nhân', 'color' => 'dark', 'route' => 'user.info']
    ];
@endphp


            @foreach ($cards as $card)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="dashboard-card card-glass bg-gradient-{{ $card['color'] }}">
                        <div class="card-inner text-center text-white">
                            <div class="icon-wrapper mb-3">
                                <i data-lucide="{{ $card['icon'] }}" class="lucide-icon"></i>
                            </div>
                            <h5 class="fw-bold">{{ $card['title'] }}</h5>
                            <p class="opacity-75 small mb-3">{{ $card['desc'] }}</p>

                            @if ($card['route'])
                                <a href="{{ route($card['route']) }}" class="btn btn-light fw-semibold px-4 shadow-sm rounded-pill">Truy cập</a>
                            @else
                                <button class="btn btn-outline-light fw-semibold px-4 shadow-sm rounded-pill" disabled>Đang phát triển</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

@push('styles')
<style>
    /* Nền tổng thể trắng */
    body {
        background-color: #ffffff !important;
        font-family: 'Poppins', sans-serif;
        color: #212529;
    }

    .admin-dashboard {
        min-height: calc(100vh - 140px);
        background-color: #ffffff;
    }

    /* Thẻ dashboard */
    .card-glass {
        position: relative;
        overflow: hidden;
        border-radius: 1.2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }

    .card-glass:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.15);
    }

    .card-inner {
        padding: 2.5rem 1.5rem;
    }

    .lucide-icon {
        width: 48px;
        height: 48px;
        stroke-width: 1.8;
    }

    /* Màu gradient từng loại thẻ */
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff, #00c6ff); }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745, #7bdcb5); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107, #ff8b00); }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8, #5bc0de); }
    .bg-gradient-danger { background: linear-gradient(135deg, #dc3545, #ff758c); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #6c757d, #adb5bd); }
    .bg-gradient-dark { background: linear-gradient(135deg, #212529, #495057); }

    .btn {
        transition: 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush
@endsection