@extends('layouts.admin')

@section('title', 'Chỉnh sửa máy bay')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Chỉnh sửa máy bay</h3>

    <form action="{{ route('admin.aircrafts.update', $aircraft->AircraftID) }}" method="POST" class="border p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="AircraftCode" class="form-label">Mã máy bay</label>
            <input type="text" name="AircraftCode" id="AircraftCode" class="form-control" value="{{ old('AircraftCode', $aircraft->AircraftCode) }}" required>
        </div>

        <div class="mb-3">
            <label for="AircraftType" class="form-label">Loại máy bay</label>
            <input type="text" name="AircraftType" id="AircraftType" class="form-control" value="{{ old('AircraftType', $aircraft->AircraftType) }}" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.aircrafts.index') }}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-success">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
