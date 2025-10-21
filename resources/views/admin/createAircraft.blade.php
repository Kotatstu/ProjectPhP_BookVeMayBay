@extends('layouts.admin')

@section('title', 'Thêm máy bay mới')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Thêm máy bay mới</h3>

    <form action="{{ route('admin.aircrafts.store') }}" method="POST" class="border p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="AircraftCode" class="form-label">Mã máy bay</label>
            <input type="text" name="AircraftCode" id="AircraftCode" class="form-control" value="{{ old('AircraftCode') }}" required>
        </div>

        <div class="mb-3">
            <label for="AircraftType" class="form-label">Loại máy bay</label>
            <input type="text" name="AircraftType" id="AircraftType" class="form-control" value="{{ old('AircraftType') }}" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.aircrafts.index') }}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu máy bay</button>
        </div>
    </form>
</div>
@endsection
