@extends('layouts.admin')

@section('title', 'Danh sách giá vé')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách giá vé</h2>
        <a href="{{ route('admin.fares.create') }}" class="btn btn-primary">+ Thêm giá vé</a>
    </div>


<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
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
            <tr>
                <td>{{ $fare->FareID }}</td>
                <td>{{ $fare->flight->FlightNumber ?? 'N/A' }}</td>
                <td>{{ $fare->flight->departureAirport->City ?? 'N/A' }}</td>
                <td>{{ $fare->flight->arrivalAirport->City ?? 'N/A' }}</td>
                <td>{{ $fare->flight->DepartureTime }}</td>
                <td>{{ $fare->flight->ArrivalTime }}</td>
                <td>{{ $fare->cabinClass->ClassName ?? 'N/A' }}</td>
                <td>{{ number_format($fare->BasePrice, 2) }}</td>
                <td>{{ number_format($fare->Tax, 2) }}</td>
                <td>{{ number_format($fare->BasePrice + $fare->Tax, 2) }}</td>
                <td>{{ $fare->Currency }}</td>
                <td>
                    @if($fare->Refundable)
                        <span class="badge bg-success">Có</span>
                    @else
                        <span class="badge bg-danger">Không</span>
                    @endif
                </td>
                <td>
                    @if($fare->Changeable)
                        <span class="badge bg-success">Có</span>
                    @else
                        <span class="badge bg-danger">Không</span>
                    @endif
                </td>
                <td>{{ $fare->FareRules ?? 'Không có' }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.fares.edit', $fare->FareID) }}" class="btn btn-sm btn-warning me-1">Sửa</a>

                    <form action="{{ route('admin.fares.destroy', $fare->FareID) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa giá vé này không?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="text-center text-muted">Không có dữ liệu giá vé</td>
            </tr>
        @endforelse
    </tbody>
</table>


</div>
@endsection
