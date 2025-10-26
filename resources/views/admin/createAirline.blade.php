@extends('layouts.admin')

@section('title', 'Thêm hãng hàng không')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thêm hãng hàng không</h2>

    <form action="{{ route('admin.airlines.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="AirlineID" class="form-label">Mã hãng</label>
            <input type="text" class="form-control" id="AirlineID" name="AirlineID" value="{{ old('AirlineID') }}" required>
        </div>

        <div class="mb-3">
            <label for="AirlineName" class="form-label">Tên hãng</label>
            <input type="text" class="form-control" id="AirlineName" name="AirlineName" value="{{ old('AirlineName') }}" required>
        </div>

        <div class="mb-3">
            <label for="Country" class="form-label">Quốc gia</label>
            <input type="text" class="form-control" id="Country" name="Country" value="{{ old('Country') }}" required>
        </div>

        <div class="mb-3">
            <label for="LogoURL" class="form-label">Logo URL</label>
            <input type="text" class="form-control" id="LogoURL" name="LogoURL" value="{{ old('LogoURL') }}">
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
