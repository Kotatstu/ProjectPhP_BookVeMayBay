@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="col-md-6">
    <div class="card p-4 shadow-lg">
      <h3 class="card-title mb-3 text-center">Tạo tài khoản mới</h3>

      <!-- Hiển thị lỗi validation -->
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
          <label for="name" class="form-label">Họ và tên</label>
          <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100">Đăng ký</button>
        <a href="/login" class="btn btn-link d-block text-center mt-2">Đã có tài khoản? Đăng nhập</a>
      </form>
    </div>
  </div>
</div>
@endsection
