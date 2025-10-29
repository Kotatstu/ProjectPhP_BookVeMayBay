@extends('layouts.app')

@section('content')

<div class="container cart-container">
    <h3 class="mb-4 fw-bold text-primary">
        <i class="bi bi-cart4 me-2"></i>Giỏ hàng
    </h3>

    @if(session('cart') && count(session('cart')) > 0)
        <table class="table table-bordered cart-table align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Logo</th>
                    <th>Hãng</th>
                    <th>Chặng</th>
                    <th>Ngày bay</th>
                    <th>Giờ</th>
                    <th>Hạng vé</th>
                    <th>Giá vé</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $key => $item)
                <tr class="text-center">
                    <td><img src="{{ $item['airline_logo'] }}" class="cart-logo"></td>
                    <td>{{ $item['airline_name'] }}</td>
                    <td>{{ $item['from'] }} → {{ $item['to'] }}</td>
                    <td>{{ $item['departure_date'] }}</td>
                    <td>{{ $item['departure_time'] }} - {{ $item['arrival_time'] }}</td>
                    <td>{{ $item['cabin_class'] }}</td>
                    <td class="fw-bold text-success">{{ number_format($item['fare'], 0, ',', '.') }} VND</td>
                    <td>Chờ thanh toán</td>
                    <td>
                        <form action="{{ route('cart.checkout') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-credit-card"></i> Thanh toán
                            </button>
                        </form>

                        <form action="{{ route('cart.remove') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa vé này khỏi giỏ?')">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash3"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end fw-bold fs-5 mt-3">
            Tổng cộng:
            <span class="text-danger">
                {{ number_format(array_sum(array_column(session('cart'), 'fare')), 0, ',', '.') }} VND
            </span>
        </div>

        <form action="{{ route('cart.checkoutAll') }}" method="POST" class="text-end mt-3">
            @csrf
            <button type="submit" class="btn btn-success fw-bold">
                <i class="bi bi-wallet2"></i> Thanh toán tất cả vé
            </button>
        </form>

    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>Giỏ hàng trống.
        </div>
    @endif
</div>

@endsection
