@extends('layouts.app')

@section('content')
<div class="flight-detail-container container my-5">
    <div class="card flight-detail-card mx-auto shadow-lg">
        <div class="row g-0">
            <!-- Ảnh hãng bay -->
            <div class="col-md-4">
                <img src="{{ $flightDetail->airline_logo }}" class="img-fluid rounded-start" alt="{{ $flightDetail->airline_name }}">
            </div>

            <!-- Thông tin chuyến bay -->
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $flightDetail->from }} → {{ $flightDetail->to }}</h4>

                    <div class="flight-info mb-2">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span>Ngày bay: <strong>{{ $flightDetail->departure_date }}</strong></span>
                    </div>

                    <div class="flight-info mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <span>Thời gian: <strong>{{ $flightDetail->departure_time }} - {{ $flightDetail->arrival_time }}</strong></span>
                    </div>

                    <div class="flight-info mb-3">
                        <i class="bi bi-currency-dollar me-2"></i>
                        <span>Giá vé: <strong>{{ number_format($flightDetail->fare) }} VND</strong></span>
                    </div>

                     <!-- Form thêm vào giỏ hàng -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="flight_id" value="{{ $flightDetail->id}}">
                        <button type="submit" class="btn btn-book">Chọn chuyến bay</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
