@extends('layouts.admin')

@section('title', 'Danh sách máy bay')

@section('content')
<div class="container mt-4">

    {{-- Tiêu đề và nút thêm máy bay --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Danh sách máy bay</h3>
        <a href="{{ route('admin.aircrafts.create') }}" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle"></i> Thêm máy bay
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
                    <th style="width:10%">ID</th>
                    <th style="width:25%">Mã máy bay</th>
                    <th style="width:45%">Loại máy bay</th>
                    <th style="width:20%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aircrafts as $aircraft)
                    <tr class="text-center">
                        <td class="fw-semibold text-primary">{{ $aircraft->AircraftID }}</td>
                        <td>{{ $aircraft->AircraftCode }}</td>
                        <td>{{ $aircraft->AircraftType }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.aircrafts.edit', $aircraft->AircraftID) }}"
                                class="btn btn-warning btn-sm text-white px-3">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="{{ route('admin.aircrafts.destroy', $aircraft->AircraftID) }}"
                                    method="POST"
                                    onsubmit="return confirm('Xác nhận xóa máy bay này?')">
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
                        <td colspan="4" class="text-center text-muted py-4">
                            Không có máy bay nào trong hệ thống.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
