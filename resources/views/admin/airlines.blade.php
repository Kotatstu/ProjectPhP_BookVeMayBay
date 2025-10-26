@extends('layouts.admin')

@section('title', 'Danh sách hãng hàng không')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh sách hãng hàng không</h2>
    <a href="{{ route('admin.airlines.create') }}" class="btn btn-primary">+ Thêm hãng</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr class="text-center">
            <th>Mã hãng</th>
            <th>Tên hãng</th>
            <th>Quốc gia</th>
            <th>Logo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($airlines as $airline)
            <tr class="text-center">
                <td>{{ $airline->AirlineID }}</td>
                <td>{{ $airline->AirlineName }}</td>
                <td>{{ $airline->Country }}</td>
                <td>
                    @if ($airline->LogoURL)
                        <img src="{{ $airline->LogoURL }}" alt="Logo" style="height:40px;">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.airlines.edit', $airline->AirlineID) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.airlines.destroy', $airline->AirlineID) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa hãng hàng không này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Không có hãng hàng không nào trong hệ thống.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
