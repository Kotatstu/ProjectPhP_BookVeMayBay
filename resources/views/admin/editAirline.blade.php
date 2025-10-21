@extends('layouts.admin')

@section('title', 'Sửa hãng hàng không')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa hãng hàng không</h2>

    <form action="{{ route('admin.airlines.update', $airline->AirlineID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="AirlineID" class="form-label">Mã hãng</label>
            <input type="text" class="form-control" id="AirlineID" name="AirlineID" value="{{ $airline->AirlineID }}" readonly>
        </div>

        <div class="mb-3">
            <label for="AirlineName" class="form-label">Tên hãng</label>
            <input type="text" class="form-control" id="AirlineName" name="AirlineName" value="{{ $airline->AirlineName }}" required>
        </div>

        <div class="mb-3">
            <label for="Country" class="form-label">Quốc gia</label>
            <input type="text" class="form-control" id="Country" name="Country" value="{{ $airline->Country }}" required>
        </div>

        <div class="mb-3">
            <label for="LogoURL" class="form-label">Logo URL</label>
            <input type="url" class="form-control" id="LogoURL" name="LogoURL" value="{{ $airline->LogoURL }}">
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
