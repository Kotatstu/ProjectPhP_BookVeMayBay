@extends('layouts.app')

@section('content')
<style>
    .user-info-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .user-info-card {
        width: 70%;
        max-width: 950px;
        border-radius: 20px;
        background: white;
    }

    .btn-custom {
        border-radius: 25px;
        padding: 10px 25px;
        transition: all 0.3s ease;
        border: none;
        color: white;
    }

    .btn-update {
        background-color: #28a745;
    }

    .btn-update:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-return {
        background-color: #007bff;
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
                {{-- THÔNG TIN CÁ NHÂN --}}
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h5 class="text-primary">
                            <i data-lucide="id-card" class="me-2"></i>Thông tin cá nhân
                        </h5>
                        <hr>
                        <p><strong>Họ và tên:</strong>
                            <span class="text-muted">{{ $user->name }}</span>
                        </p>
                        <p><strong>Email:</strong>
                            <span class="text-muted">{{ $user->email }}</span>
                        </p>
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

                        {{-- 🔹 PHƯƠNG THỨC THANH TOÁN --}}
                        <p><strong>Phương thức thanh toán:</strong>
                            <span class="text-muted">
                                @if(isset($payment) && $payment)
                                    {{ $payment->PaymentType ?? 'Không rõ' }} -
                                    {{ $payment->Provider ?? 'Không rõ' }}
                                    ({{ $payment->AccountNumber ?? '---' }})
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                {{-- THÔNG TIN TÀI KHOẢN --}}
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <h5 class="text-primary">
                            <i data-lucide="info" class="me-2"></i>Thông tin tài khoản
                        </h5>
                        <hr>
                        <p><strong>ID người dùng:</strong>
                            <span class="text-muted">{{ $user->id }}</span>
                        </p>
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
            <div class="text-center mt-4">
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
