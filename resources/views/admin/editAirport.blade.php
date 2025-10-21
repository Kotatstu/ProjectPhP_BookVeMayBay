@extends('layouts.admin')

@section('title', 'Sửa sân bay')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa sân bay</h2>

    <form action="{{ route('admin.airports.update', $airport->AirportCode) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="AirportCode" class="form-label">Mã sân bay</label>
            <input type="text" class="form-control" id="AirportCode" name="AirportCode" value="{{ $airport->AirportCode }}" readonly>
        </div>

        <div class="mb-3">
            <label for="AirportName" class="form-label">Tên sân bay</label>
            <input type="text" class="form-control" id="AirportName" name="AirportName" value="{{ $airport->AirportName }}" required>
        </div>

        <div class="mb-3">
            <label for="City" class="form-label">Thành phố</label>
            <input type="text" class="form-control" id="City" name="City" value="{{ $airport->City }}" required>
        </div>

        <div class="mb-3">
            <label for="Country" class="form-label">Quốc gia</label>
            <input type="text" class="form-control" id="Country" name="Country" value="{{ $airport->Country }}" required>
        </div>

        <div class="mb-3">
            <label for="TimeZone" class="form-label">Múi giờ</label>
            <input type="text" class="form-control" id="TimeZone" name="TimeZone" value="{{ $airport->TimeZone }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
