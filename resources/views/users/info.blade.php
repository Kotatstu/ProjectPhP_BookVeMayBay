@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f7fa, #f1f8e9);
        font-family: 'Segoe UI', sans-serif;
    }

    .user-info-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 40px 15px;
    }

    .user-info-card {
        width: 100%;
        max-width: 1000px;
        border-radius: 25px;
        background: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .user-info-card:hover {
        transform: translateY(-3px);
    }

    .card-header {
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: white;
        text-align: center;
        padding: 25px;
    }

    .card-header h3 {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .card-body {
        padding: 40px 35px;
    }

    .info-section {
        background: #f9fafc;
        border: 1px solid #e5e9f2;
        border-radius: 15px;
        padding: 20px 25px;
        transition: all 0.3s ease;
    }

    .info-section:hover {
        background: #f1f5fb;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.15);
    }

    .info-section h5 {
        font-weight: 600;
        color: #007bff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-section p {
        margin-bottom: 10px;
        font-size: 15px;
    }

    .info-section strong {
        color: #343a40;
    }

    .info-section span.text-muted {
        color: #6c757d !important;
    }

    hr {
        margin: 10px 0 15px;
    }

    .btn-custom {
        border-radius: 30px;
        padding: 12px 25px;
        transition: all 0.3s ease;
        border: none;
        color: white;
        font-weight: 500;
    }

    .btn-update {
        background: linear-gradient(135deg, #28a745, #6dd070);
    }

    .btn-update:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #23923c, #4ac959);
    }

    .btn-return {
        background: linear-gradient(135deg, #007bff, #5ab0ff);
    }

    .btn-return:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #0056b3, #4a9bff);
    }

    .btn i {
        vertical-align: middle;
        margin-right: 6px;
    }

    @media (max-width: 767px) {
        .card-body {
            padding: 25px 15px;
        }
    }
</style>

<div class="user-info-wrapper">
    <div class="card user-info-card">
        <div class="card-header">
            <h3>
                <i data-lucide="user" class="me-2"></i>Thông tin người dùng
            </h3>
        </div>

        <div class="card-body">
            <div class="row g-4">
                {{-- THÔNG TIN CÁ NHÂN --}}
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i data-lucide="id-card"></i>Thông tin cá nhân</h5>
                        <hr>
                        <p><strong>Họ và tên:</strong> <span class="text-muted">{{ $user->name }}</span></p>
                        <p><strong>Email:</strong> <span class="text-muted">{{ $user->email }}</span></p>
                        <p><strong>Số điện thoại:</strong>
                            <span class="text-muted">{{ optional($customer)->Phone ?? 'Chưa cập nhật' }}</span>
                        </p>
                        <p><strong>Giới tính:</strong>
                            <span class="text-muted">{{ optional($customer)->Gender ?? 'Chưa cập nhật' }}</span>
                        </p>
                        <p><strong>Ngày sinh:</strong>
                            <span class="text-muted">
                                @if(optional($customer)->DateOfBirth)
                                    {{ date('d/m/Y', strtotime(optional($customer)->DateOfBirth)) }}
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </p>
                        <p><strong>Quốc tịch:</strong>
                            <span class="text-muted">{{ optional($customer)->Nationality ?? 'Chưa cập nhật' }}</span>
                        </p>
                        <p><strong>Phương thức thanh toán:</strong>
                            <span class="text-muted">
                                @if(isset($payment) && $payment)
                                    {{ $payment->PaymentType ?? 'Không rõ' }} - {{ $payment->Provider ?? 'Không rõ' }} ({{ $payment->AccountNumber ?? '---' }})
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                {{-- THÔNG TIN TÀI KHOẢN --}}
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i data-lucide="info"></i>Thông tin tài khoản</h5>
                        <hr>
                        <p><strong>ID người dùng:</strong> <span class="text-muted">{{ $user->id }}</span></p>
                        <p><strong>Ngày tạo tài khoản:</strong>
                            <span class="text-muted">
                                @if($user->created_at)
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                @else
                                    Không xác định
                                @endif
                            </span>
                        </p>

                        <p><strong>Hội viên:</strong>
                            @if(isset($loyalty) && $loyalty)
                                <span class="badge bg-success">{{ $loyalty->MembershipLevel }}</span><br>
                                <strong>Điểm tích lũy:</strong>
                                <span class="text-primary fw-bold">{{ $loyalty->Points }}</span>
                            @else
                                <span class="badge bg-secondary">Chưa tham gia</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- NÚT HÀNH ĐỘNG --}}
            <div class="text-center mt-5">
                <a href="{{ url('/home') }}" class="btn btn-custom btn-return me-3">
                    <i data-lucide="arrow-left"></i> Quay lại trang chủ
                </a>
                <a href="{{ route('user.edit') }}" class="btn btn-custom btn-update">
                    <i data-lucide="edit"></i> Cập nhật thông tin
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
