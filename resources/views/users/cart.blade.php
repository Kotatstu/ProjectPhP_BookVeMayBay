@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">Giỏ hàng</h3>

    @if(session('cart') && count(session('cart')) > 0)
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Hãng</th>
                    <th>Chặng</th>
                    <th>Ngày bay</th>
                    <th>Thời gian</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('cart') as $item)
                <tr>
                    <td><img src="{{ $item['airline_logo'] }}" width="80" alt="{{ $item['airline_name'] }}"></td>
                    <td>{{ $item['airline_name'] }}</td>
                    <td>{{ $item['from'] }} → {{ $item['to'] }}</td>
                    <td>{{ $item['departure_date'] }}</td>
                    <td>{{ $item['departure_time'] }} - {{ $item['arrival_time'] }}</td>
                    <td>{{ number_format($item['fare']) }} VND</td>
                    <td>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="flight_id" value="{{ $item['id'] }}">
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end fw-bold fs-5">
            Tổng: {{ number_format(array_sum(array_column(session('cart'), 'fare'))) }} VND
        </div>
    @else
        <p>Giỏ hàng trống.</p>
    @endif
</div>
@endsection
