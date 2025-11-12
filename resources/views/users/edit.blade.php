@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f3ff; /* màu nền chung cho toàn trang */
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
    }

    .edit-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        padding: 50px 20px;
    }

    .edit-card {
        width: 85%;
        max-width: 1000px;
        border-radius: 20px;
        background-color: #ffffff; /* giữ form màu trắng */
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.1);
        border: 1px solid #e0ecff;
        transition: all 0.3s ease;
    }

    .edit-card:hover {
        transform: translateY(-3px);
    }

    .card-header {
        background: linear-gradient(135deg, #007bff, #4ab3ff);
        color: #fff;
        padding: 18px 25px;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .card-header h3 {
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
    }

    .card-body {
        padding: 35px 40px;
    }

    .section-title {
        font-weight: 700;
        color: #0d6efd;
        margin-top: 25px;
        margin-bottom: 10px;
        border-left: 5px solid #0d6efd;
        padding-left: 12px;
        font-size: 1.2rem;
    }

    label.form-label {
        font-weight: 600;
        color: #0d6efd;
    }

    input.form-control,
    select.form-select {
        border-radius: 12px;
        border: 1px solid #cfe2ff;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.05);
        transition: all 0.3s ease;
    }

    input.form-control:focus,
    select.form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13, 110, 253, 0.25);
    }

    .btn-custom {
        border-radius: 25px;
        padding: 12px 25px;
        transition: all 0.3s ease;
        border: none;
        color: white;
        font-weight: 500;
    }

    .btn-save {
        background: linear-gradient(135deg, #007bff, #4ab3ff);
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #0066d3, #3b9ef0);
        transform: translateY(-2px);
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d, #8e9aa3);
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #5a6268, #76838f);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 25px 20px;
        }
    }
</style>

<div class="edit-wrapper">
    <div class="card edit-card">
        <div class="card-header text-center">
            <h3><i data-lucide="edit-3" class="me-2"></i>Cập nhật thông tin người dùng</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('user.update') }}" method="POST">
                @csrf

                {{-- THÔNG TIN CÁ NHÂN --}}
                <h5 class="section-title">Thông tin cá nhân</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ optional($customer)->Phone }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" {{ optional($customer)->Gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ optional($customer)->Gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ optional($customer)->DateOfBirth }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Quốc tịch</label>
                        <input type="text" name="nationality" class="form-control" value="{{ optional($customer)->Nationality }}">
                    </div>
                </div>

                {{-- PHƯƠNG THỨC THANH TOÁN --}}
                <h5 class="section-title">Phương thức thanh toán</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Loại thanh toán</label>
                        <select name="payment_type" class="form-select">
                            <option value="">-- Chọn loại thanh toán --</option>
                            <option value="Credit Card" {{ optional($payment)->PaymentType == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="Debit Card" {{ optional($payment)->PaymentType == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="E-Wallet" {{ optional($payment)->PaymentType == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nhà cung cấp</label>
                        <select name="provider" class="form-select">
                            <option value="">-- Chọn nhà cung cấp --</option>
                            <option value="Visa" {{ optional($payment)->Provider == 'Visa' ? 'selected' : '' }}>Visa</option>
                            <option value="MasterCard" {{ optional($payment)->Provider == 'MasterCard' ? 'selected' : '' }}>MasterCard</option>
                            <option value="MoMo" {{ optional($payment)->Provider == 'MoMo' ? 'selected' : '' }}>MoMo</option>
                            <option value="ZaloPay" {{ optional($payment)->Provider == 'ZaloPay' ? 'selected' : '' }}>ZaloPay</option>
                            <option value="ShopeePay" {{ optional($payment)->Provider == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số tài khoản / thẻ</label>
                        <input type="text" name="account_number" class="form-control" value="{{ optional($payment)->AccountNumber }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ngày hết hạn</label>
                        <input type="date" name="expiry_date" class="form-control" value="{{ optional($payment)->ExpiryDate }}">
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('user.info') }}" class="btn btn-custom btn-back me-3">
                        <i data-lucide="arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-custom btn-save">
                        <i data-lucide="save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
@endsection
