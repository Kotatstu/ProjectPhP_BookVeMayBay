@extends('layouts.admin')

@section('title', 'Danh sách giá vé')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">Danh sách giá vé</h2>
        <a href="{{ route('admin.fares.create') }}" class="btn btn-primary shadow-sm px-4">+ Thêm giá vé</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary text-center align-middle">
                    <tr>
                        <th>#</th>
                        <th>Mã chuyến bay</th>
                        <th>Điểm đi</th>
                        <th>Điểm đến</th>
                        <th>Giờ khởi hành</th>
                        <th>Giờ đến</th>
                        <th>Hạng ghế</th>
                        <th>Giá gốc</th>
                        <th>Thuế</th>
                        <th>Tổng giá</th>
                        <th>Tiền tệ</th>
                        <th>Hoàn vé</th>
                        <th>Đổi vé</th>
                        <th>Điều khoản</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fares as $fare)
                        <tr class="text-center">
                            <td>{{ $fare->FareID }}</td>
                            <td><a href="#" class="fw-bold text-primary text-decoration-none">{{ $fare->flight->FlightNumber ?? 'N/A' }}</a></td>
                            <td>{{ $fare->flight->departureAirport->City ?? 'N/A' }}</td>
                            <td>{{ $fare->flight->arrivalAirport->City ?? 'N/A' }}</td>
                            <td>{{ $fare->flight->DepartureTime }}</td>
                            <td>{{ $fare->flight->ArrivalTime }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $fare->cabinClass->ClassName ?? 'N/A' }}</span>
                            </td>
                            <td>{{ number_format($fare->BasePrice, 2) }}</td>
                            <td>{{ number_format($fare->Tax, 2) }}</td>
                            <td class="fw-bold text-success">{{ number_format($fare->BasePrice + $fare->Tax, 2) }}</td>
                            <td>{{ $fare->Currency }}</td>
                            <td>
                                <span class="badge {{ $fare->Refundable ? 'bg-success' : 'bg-danger' }}">
                                    {{ $fare->Refundable ? 'Có' : 'Không' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $fare->Changeable ? 'bg-success' : 'bg-danger' }}">
                                    {{ $fare->Changeable ? 'Có' : 'Không' }}
                                </span>
                            </td>
                            <td class="text-start">{{ $fare->FareRules ?? 'Không có' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.fares.edit', $fare->FareID) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.fares.destroy', $fare->FareID) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa giá vé này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center text-muted py-4">Không có dữ liệu giá vé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bootstrap Icons (nếu chưa có) --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
