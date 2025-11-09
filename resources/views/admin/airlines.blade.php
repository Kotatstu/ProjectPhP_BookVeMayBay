@extends('layouts.admin')

@section('title', 'Danh sách hãng hàng không')

@section('content')
<div class="container mt-4">

    {{-- Header và nút thêm hãng --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Danh sách hãng hàng không</h3>
        <a href="{{ route('admin.airlines.create') }}" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle"></i> Thêm hãng
        </a>
    </div>

    {{-- Thông báo thành công --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Bảng danh sách --}}
    <div class="table-responsive shadow-sm bg-white p-3 rounded-3">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 10%">Mã hãng</th>
                    <th style="width: 25%">Tên hãng</th>
                    <th style="width: 20%">Quốc gia</th>
                    <th style="width: 20%">Logo</th>
                    <th style="width: 20%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($airlines as $airline)
                    <tr class="text-center">
                        <td class="fw-semibold text-primary">{{ $airline->AirlineID }}</td>
                        <td>{{ $airline->AirlineName }}</td>
                        <td>{{ $airline->Country }}</td>
                        <td>
                            @if ($airline->LogoURL)
                                <img src="{{ $airline->LogoURL }}" alt="Logo" class="img-fluid" style="height:45px; object-fit:contain;">
                            @else
                                <span class="text-muted fst-italic">Không có</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.airlines.edit', $airline->AirlineID) }}"
                                class="btn btn-warning btn-sm text-white px-3">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="{{ route('admin.airlines.destroy', $airline->AirlineID) }}"
                                    method="POST" 
                                    onsubmit="return confirm('Xác nhận xóa hãng hàng không này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm px-3">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Không có hãng hàng không nào trong hệ thống.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
