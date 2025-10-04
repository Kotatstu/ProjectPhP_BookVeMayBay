@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<!-- Gọi thư viện Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="d-flex align-items-center justify-content-center vh-100">
  <div class="col-md-5">
    <div class="card shadow p-4">
      <h3 class="card-title mb-3 text-center">Đăng nhập</h3>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form action="/login" method="POST">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <!-- Ô nhập mật khẩu có nút ẩn/hiện -->
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <div class="input-group">
            <input type="password" id="password" name="password" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
              <i data-lucide="eye"></i>
            </button>
          </div>
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" name="remember" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>

        <div class="d-grid">
          <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
        </div>

        <div class="text-center mt-3">
          <a href="/register" class="btn btn-link">Chưa có tài khoản? Đăng ký</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script ẩn/hiện mật khẩu -->
<script>
  lucide.createIcons(); // Kích hoạt Lucide icons

  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  let isVisible = false;

  togglePassword.addEventListener('click', () => {
    isVisible = !isVisible;
    passwordInput.type = isVisible ? 'text' : 'password';

    // Đổi icon
    togglePassword.innerHTML = `<i data-lucide="${isVisible ? 'eye-off' : 'eye'}"></i>`;
    lucide.createIcons();
  });
</script>
@endsection
