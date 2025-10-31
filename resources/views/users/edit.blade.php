@extends('layouts.app')

@section('content')
<style>
    .edit-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .edit-card {
        width: 70%;
        max-width: 950px;
        border-radius: 20px;
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
</style>

<div class="edit-wrapper">
    <div class="card shadow-lg border-0 rounded-4 edit-card">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h3 class="mb-0 text-center">
                <i data-lucide="edit-3" class="me-2"></i> Cập nhật thông tin người dùng
            </h3>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('user.update') }}" method="POST">
                @csrf
                <div class="row g-3">
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
