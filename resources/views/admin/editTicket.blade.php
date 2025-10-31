@extends('layouts.admin')

@section('title', 'Chỉnh sửa vé máy bay')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa Vé #{{ $ticket->TicketID }}</h2>

    <form action="{{ route('admin.tickets.update', $ticket->TicketID) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Khách hàng</label>
            <select name="CustomerID" class="form-select" required>
                @foreach($customers as $c)
                    <option value="{{ $c->CustomerID }}" {{ $ticket->CustomerID == $c->CustomerID ? 'selected' : '' }}>
                        {{ $c->user->name ?? 'Không rõ' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Hạng ghế</label>
            <select name="FareID" class="form-select" required>
                @foreach($fares as $f)
                    <option value="{{ $f->FareID }}" {{ $ticket->FareID == $f->FareID ? 'selected' : '' }}>
                        {{ $f->cabinClass->ClassName ?? 'Không rõ' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Phương thức thanh toán</label>
            <select name="PaymentMethodID" class="form-select" required>
                @foreach($methods as $m)
                    <option value="{{ $m->PaymentMethodID }}" {{ $ticket->PaymentMethodID == $m->PaymentMethodID ? 'selected' : '' }}>
                        {{ $m->Provider }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tổng tiền</label>
            <input type="number" name="TotalAmount" class="form-control" value="{{ $ticket->TotalAmount }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="Status" class="form-select">
                <option value="Chờ thanh toán" {{ $ticket->Status == 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                <option value="Đã thanh toán" {{ $ticket->Status == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="Đã hủy" {{ $ticket->Status == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
