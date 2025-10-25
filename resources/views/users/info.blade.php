@extends('layouts.app')

@section('content')
<style>
    .user-info-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .user-info-card {
        width: 60%;
        max-width: 900px;
        border-radius: 20px;
    }

    .btn-return {
        background-color: #007bff;
        color: white;
        border-radius: 25px;
        padding: 10px 25px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-return:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
</style>

<div class="user-info-wrapper">
    <div class="card shadow-lg border-0 rounded-4 user-info-card">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h3 class="mb-0 text-center">
                <i data-lucide="user" class="me-2"></i> Thông tin người dùng
            </h3>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h5 class="text-primary">
                            <i data-lucide="id-card" class="me-2"></i>Thông tin cá nhân
                        </h5>
                        <hr>
                        <p><strong>Họ và tên:</strong> <span class="text-muted">{{ $user->name }}</span></p>
                        <p><strong>Email:</strong> <span class="text-muted">{{ $user->email }}</span></p>
                        <p><strong>Số điện thoại:</strong> <span class="text-muted">{{ $user->phone ?? 'Chưa cập nhật' }}</span></p>
                        <p><strong>Địa chỉ:</strong> <span class="text-muted">{{ $user->address ?? 'Chưa cập nhật' }}</span></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h5 class="text-primary">
                            <i data-lucide="info" class="me-2"></i>Thông tin tài khoản
                        </h5>
                        <hr>
                        <p><strong>ID người dùng:</strong> <span class="text-muted">{{ $user->id }}</span></p>
                        <p><strong>Ngày tạo tài khoản:</strong>
                            <span class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </p>
                        <p><strong>Trạng thái:</strong>
                            <span class="badge bg-success">Hoạt động</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/home') }}" class="btn btn-return">
                    <i data-lucide="arrow-left"></i> Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
