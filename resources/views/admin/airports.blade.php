@extends('layouts.admin')

@section('title', 'Danh sách sân bay')

@section('content')
<div class="px-4 px-md-5 py-4">
    <div class="bg-white p-4 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"> Danh sách sân bay</h2>
            <a href="{{ route('admin.airports.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm sân bay
            </a>
        </div>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Bảng danh sách sân bay --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 10%;">Mã sân bay</th>
                        <th style="width: 25%;">Tên sân bay</th>
                        <th style="width: 20%;">Thành phố</th>
                        <th style="width: 20%;">Quốc gia</th>
                        <th style="width: 10%;">Múi giờ</th>
                        <th style="width: 15%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($airports as $airport)
                        <tr>
                            <td class="fw-semibold">{{ $airport->AirportCode }}</td>
                            <td>{{ $airport->AirportName }}</td>
                            <td>{{ $airport->City }}</td>
                            <td>{{ $airport->Country }}</td>
                            <td>{{ $airport->TimeZone }}</td>
                            <td>
                                <a href="{{ route('admin.airports.edit', $airport->AirportCode) }}"
                                class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="{{ route('admin.airports.destroy', $airport->AirportCode) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Xác nhận xóa sân bay này?')">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">Không có sân bay nào trong hệ thống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Style nhẹ cho bảng --}}
<style>
    table tbody tr:hover {
        background-color: #f1f8ff !important;
        transition: background-color 0.2s ease;
    }
</style>
@endsection
