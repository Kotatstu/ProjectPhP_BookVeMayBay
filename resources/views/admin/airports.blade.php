@extends('layouts.admin')

@section('title', 'Danh sách sân bay')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh sách sân bay</h2>
    <a href="{{ route('admin.airports.create') }}" class="btn btn-primary">+ Thêm sân bay</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>Mã sân bay</th>
            <th>Tên sân bay</th>
            <th>Thành phố</th>
            <th>Quốc gia</th>
            <th>Múi giờ</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($airports as $airport)
            <tr class="text-center">
                <td>{{ $airport->AirportCode }}</td>
                <td>{{ $airport->AirportName }}</td>
                <td>{{ $airport->City }}</td>
                <td>{{ $airport->Country }}</td>
                <td>{{ $airport->TimeZone }}</td>
                <td>
                    <a href="{{ route('admin.airports.edit', $airport->AirportCode) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.airports.destroy', $airport->AirportCode) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa sân bay này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Không có sân bay nào trong hệ thống.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
