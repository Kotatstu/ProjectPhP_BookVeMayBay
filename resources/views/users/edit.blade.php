@extends('layouts.app')

@section('content')
<style>
    .edit-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #fff;
        padding: 30px;
    }

    .edit-card {
        width: 75%;
        max-width: 1000px;
        border-radius: 20px;
        padding: 20px;
    }

    .btn-custom {
        border-radius: 25px;
        padding: 10px 25px;
        transition: all 0.3s ease;
        border: none;
        color: white;
    }

    .btn-save {
        background-color: #007bff;
    }

    .btn-save:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .btn-back {
        background-color: #6c757d;
    }

    .btn-back:hover {
        background-color: #5a6268;
        transform: scale(1.05);
    }

    .section-title {
        font-weight: 700;
        color: #0d6efd;
        margin-top: 25px;
        border-left: 5px solid #0d6efd;
        padding-left: 10px;
    }
</style>

<div class="edit-wrapper">
    <div class="card shadow-lg border-0 edit-card">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h3 class="mb-0 text-center">
                <i data-lucide="edit-3" class="me-2"></i> Cập nhật thông tin người dùng
            </h3>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('user.update') }}" method="POST">
                @csrf
                {{-- --- THÔNG TIN NGƯỜI DÙNG --- --}}
                <h5 class="section-title">Thông tin cá nhân</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Họ và tên</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ optional($customer)->Phone }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" {{ optional($customer)->Gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ optional($customer)->Gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Ngày sinh</label>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ optional($customer)->DateOfBirth }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Quốc tịch</label>
                        <input type="text" name="nationality" class="form-control"
                            value="{{ optional($customer)->Nationality }}">
                    </div>
                </div>

                {{-- --- PHƯƠNG THỨC THANH TOÁN --- --}}
                <h5 class="section-title">Phương thức thanh toán</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Loại thanh toán</label>
                        <select name="payment_type" class="form-select">
                            <option value="">-- Chọn loại thanh toán --</option>
                            <option value="Credit Card" {{ optional($payment)->PaymentType == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="Debit Card" {{ optional($payment)->PaymentType == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="E-Wallet" {{ optional($payment)->PaymentType == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Nhà cung cấp</label>
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
                        <label class="form-label fw-bold text-primary">Số tài khoản / thẻ</label>
                        <input type="text" name="account_number" class="form-control"
                            value="{{ optional($payment)->AccountNumber }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Ngày hết hạn</label>
                        <input type="date" name="expiry_date" class="form-control"
                            value="{{ optional($payment)->ExpiryDate }}">
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
