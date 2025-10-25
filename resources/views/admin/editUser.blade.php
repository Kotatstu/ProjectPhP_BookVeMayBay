@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin người dùng')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Chỉnh sửa thông tin người dùng</h4>
        </div>

        <div class="card-body">
            <!-- Hiển thị lỗi nếu có -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form chỉnh sửa thông tin -->
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="name" name="name" 
                        value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" 
                            value="{{ old('password', $user->password) }}" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center mb-3">
                    <div class="form-check form-switch me-3">
                        <input class="form-check-input" type="checkbox" id="isAdmin" name="is_admin"
                            {{ old('is_admin', $isAdmin) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAdmin">Quyền quản trị</label>
                    </div>
                    <div class="text-end">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                </div> 
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Gọi Lucide nếu chưa có -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  lucide.createIcons(); // Kích hoạt icon Lucide

  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  let isVisible = false;

  // Mặc định: ẩn mật khẩu, icon là eye-off
  togglePassword.innerHTML = '<i data-lucide="eye-off"></i>';
  lucide.createIcons();

  togglePassword.addEventListener('click', () => {
    isVisible = !isVisible;
    passwordInput.type = isVisible ? 'text' : 'password';

    // Đổi icon giữa eye / eye-off
    togglePassword.innerHTML = `<i data-lucide="${isVisible ? 'eye' : 'eye-off'}"></i>`;
    lucide.createIcons();
  });
});
</script>
@endpush
