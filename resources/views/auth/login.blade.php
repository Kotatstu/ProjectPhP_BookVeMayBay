@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<!-- Gọi thư viện Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
  /* Hiệu ứng nền nhẹ nhàng */
  body {
    background: linear-gradient(135deg, #b3e5fc, #e3f2fd, #ffffff);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bgmove 8s ease-in-out infinite alternate;
  }

  @keyframes bgmove {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
  }

  .login-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 420px;
    padding: 36px;
    transform: translateY(0);
    transition: all 0.4s ease;
  }

  .login-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
  }

  .login-card h3 {
    text-align: center;
    font-weight: 700;
    color: #003366;
    margin-bottom: 24px;
    letter-spacing: 0.5px;
  }

  /* Input có hiệu ứng glow khi focus */
  .form-control {
    border-radius: 12px;
    border: 1px solid #d0d7de;
    transition: 0.3s;
  }

  .form-control:focus {
    border-color: #007BFF;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
  }

  .input-group .form-control {
    border-right: none;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }

  .input-group .btn {
    border-left: none;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    background: #f8f9fa;
    transition: background 0.3s ease;
  }

  .input-group .btn:hover {
    background: #e9ecef;
  }

  .btn-primary {
    background: linear-gradient(90deg, #007BFF, #00C6FF);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border-radius: 12px;
  }

  .btn-primary:hover {
    transform: scale(1.04);
    box-shadow: 0 4px 16px rgba(0, 123, 255, 0.3);
  }

  .btn-link {
    color: #007BFF;
    font-weight: 500;
    transition: 0.3s;
  }

  .btn-link:hover {
    color: #0056b3;
    text-decoration: underline;
  }

  .icon-eye {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Hiệu ứng fade-in khi form hiện ra */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .login-card {
    animation: fadeIn 1s ease forwards;
  }
</style>

<div class="container d-flex align-items-center justify-content-center">
  <div class="login-card">
    <h3>Đăng nhập tài khoản</h3>

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
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email của bạn" value="{{ old('email') }}" required>
      </div>

      <!-- Ô nhập mật khẩu có icon ẩn/hiện -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
        <div class="input-group">
          <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
          <button type="button" class="btn btn-outline-secondary icon-eye" id="togglePassword">
            <i data-lucide="eye"></i>
          </button>
        </div>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
      </div>

      <div class="d-grid">
        <button type="submit" name="login" class="btn btn-primary py-2">Đăng nhập</button>
      </div>

      <div class="text-center mt-3">
        <a href="/register" class="btn btn-link">Chưa có tài khoản? Đăng ký</a>
      </div>
    </form>
  </div>
</div>

<!-- Script xử lý ẩn/hiện mật khẩu -->
<script>
  lucide.createIcons();

  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  let isVisible = false;

  togglePassword.addEventListener('click', () => {
    isVisible = !isVisible;
    passwordInput.type = isVisible ? 'text' : 'password';
    togglePassword.innerHTML = `<i data-lucide="${isVisible ? 'eye-off' : 'eye'}"></i>`;
    lucide.createIcons();
  });
</script>

@endsection
