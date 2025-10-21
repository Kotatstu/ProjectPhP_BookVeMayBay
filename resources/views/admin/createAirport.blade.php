@extends('layouts.admin')

@section('title', 'Thêm sân bay')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thêm sân bay mới</h2>

    <form action="{{ route('admin.airports.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="AirportCode" class="form-label">Mã sân bay</label>
            <input type="text" class="form-control" id="AirportCode" name="AirportCode" value="{{ old('AirportCode') }}" required>
        </div>

        <div class="mb-3">
            <label for="AirportName" class="form-label">Tên sân bay</label>
            <input type="text" class="form-control" id="AirportName" name="AirportName" value="{{ old('AirportName') }}" required>
        </div>

        <div class="mb-3">
            <label for="City" class="form-label">Thành phố</label>
            <input type="text" class="form-control" id="City" name="City" value="{{ old('City') }}" required>
        </div>

        <div class="mb-3">
            <label for="Country" class="form-label">Quốc gia</label>
            <input type="text" class="form-control" id="Country" name="Country" value="{{ old('Country') }}" required>
        </div>

        <div class="mb-3">
            <label for="TimeZone" class="form-label">Múi giờ (VD: UTC+7)</label>
            <input type="text" class="form-control" id="TimeZone" name="TimeZone" value="{{ old('TimeZone') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
