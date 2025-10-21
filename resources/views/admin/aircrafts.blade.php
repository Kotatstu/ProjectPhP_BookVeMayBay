@extends('layouts.admin')

@section('title', 'Danh sách máy bay')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh sách máy bay</h2>
    <a href="{{ route('admin.aircrafts.create') }}" class="btn btn-primary">+ Thêm máy bay</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr class="text-center">
            <th>ID</th>
            <th>Mã máy bay</th>
            <th>Loại máy bay</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($aircrafts as $aircraft)
            <tr class="text-center">
                <td>{{ $aircraft->AircraftID }}</td>
                <td>{{ $aircraft->AircraftCode }}</td>
                <td>{{ $aircraft->AircraftType }}</td>
                <td>
                    <a href="{{ route('admin.aircrafts.edit', $aircraft->AircraftID) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.aircrafts.destroy', $aircraft->AircraftID) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa máy bay này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Không có máy bay nào trong hệ thống.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
