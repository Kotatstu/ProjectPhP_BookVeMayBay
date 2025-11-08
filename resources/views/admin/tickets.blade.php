@extends('layouts.admin')

@section('title', 'Danh sách vé máy bay')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Danh sách Vé Máy Bay</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã vé</th>
                <th>Hành khách</th>
                <th>Hạng ghế</th>
                <th>Phương thức thanh toán</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->TicketID }}</td>
                    <td>{{ $ticket->customer->user->name ?? 'Không có dữ liệu' }}</td>
                    <td>{{ $ticket->fare->cabinClass->ClassName ?? 'Không có dữ liệu' }}</td>
                    <td>{{ $ticket->paymentMethod->PaymentType ?? 'Không có dữ liệu' }}</td>
                    <td>{{ \Carbon\Carbon::parse($ticket->BookingDate)->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($ticket->TotalAmount, 2) }} VNĐ</td>
                    <td>
                        <span class="badge
                            @if($ticket->Status == 'Đã thanh toán') bg-success
                            @elseif($ticket->Status == 'Chờ thanh toán') bg-warning
                            @else bg-secondary @endif">
                            {{ $ticket->Status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.tickets.edit', $ticket->TicketID) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('admin.tickets.delete', $ticket->TicketID) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa vé này không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Không có vé nào được tìm thấy.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
