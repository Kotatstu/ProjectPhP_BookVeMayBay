@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<!-- Gọi thư viện Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
  /* Nền động mượt */
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

  .register-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 460px;
    padding: 36px;
    transition: all 0.4s ease;
    animation: fadeIn 1s ease forwards;
  }

  .register-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
  }

  .register-card h3 {
    text-align: center;
    font-weight: 700;
    color: #003366;
    margin-bottom: 24px;
    letter-spacing: 0.5px;
  }

  /* Input glow */
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

  .input-group-text {
    border-left: none;
    background: #f8f9fa;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    transition: background 0.3s ease;
    cursor: pointer;
  }

  .input-group-text:hover {
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

  /* Hiệu ứng xuất hiện */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="container d-flex justify-content-center align-items-center">
  <div class="register-card">
    <h3>Tạo tài khoản mới</h3>

    <!-- Hiển thị lỗi -->
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="/register" method="POST">
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Họ và tên</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ và tên của bạn" value="{{ old('name') }}" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email của bạn" value="{{ old('email') }}" required>
      </div>

      <!-- Mật khẩu -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
        <div class="input-group">
          <input type="password" id="password" name="password" class="form-control" placeholder="Tạo mật khẩu" required>
          <span class="input-group-text" id="togglePassword">
            <i data-lucide="eye-off"></i>
          </span>
        </div>
      </div>

      <!-- Xác nhận mật khẩu -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu</label>
        <div class="input-group">
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
          <span class="input-group-text" id="toggleConfirmPassword">
            <i data-lucide="eye-off"></i>
          </span>
        </div>
      </div>

      <div class="d-grid mt-4">
        <button type="submit" name="register" class="btn btn-primary py-2">Đăng ký</button>
      </div>

      <div class="text-center mt-3">
        <a href="/login" class="btn btn-link">Đã có tài khoản? Đăng nhập</a>
      </div>
    </form>
  </div>
</div>

<!-- Script Lucide + ẩn/hiện mật khẩu -->
<script>
  lucide.createIcons();

  function toggleVisibility(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    let visible = false;

    button.addEventListener('click', () => {
      visible = !visible;
      input.type = visible ? 'text' : 'password';
      button.innerHTML = `<i data-lucide="${visible ? 'eye' : 'eye-off'}"></i>`;
      lucide.createIcons();
    });
  }

  toggleVisibility('password', 'togglePassword');
  toggleVisibility('password_confirmation', 'toggleConfirmPassword');
</script>

@endsection
