@extends('layouts.app')

@section('content')
    <div class="container detail-container my-5">
        <div class="card detail-card shadow-lg p-4">
            <!-- Thông tin chuyến bay -->
            <div class="row detail-flight-info g-0 align-items-center mb-4">
                <div class="col-md-3 text-center">
                    <img src="{{ $flightDetail->airline_logo }}" alt="{{ $flightDetail->airline_name }}"
                        class="detail-airline-logo rounded">
                </div>
                <div class="col-md-9">
                    <h4 class="mb-2 detail-flight-title">
                        {{ $flightDetail->from }} → {{ $flightDetail->to }}
                    </h4>
                    <p class="mb-1 detail-flight-date"><i class="bi bi-calendar-event me-2"></i>
                        Ngày bay: <strong>{{ $flightDetail->departure_date }}</strong>
                    </p>
                    <p class="mb-1 detail-flight-time"><i class="bi bi-clock me-2"></i>
                        Giờ: <strong>{{ $flightDetail->departure_time }} - {{ $flightDetail->arrival_time }}</strong>
                    </p>
                    <p class="mb-0 detail-flight-airline"><i class="bi bi-airplane me-2"></i>
                        Hãng: <strong>{{ $flightDetail->airline_name }}</strong>
                    </p>
                </div>
            </div>

            <hr>

            <!-- Danh sách hạng vé -->
            <h5 class="mb-3 text-center detail-fare-title">Các hạng vé khả dụng</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center detail-fare-table">
                    <thead>
                        <tr>
                            <th>Hạng vé</th>
                            <th>Giá cơ bản (VND)</th>
                            <th>Thuế (VND)</th>
                            <th>Tổng giá (VND)</th>
                            <th>Hoàn vé</th>
                            <th>Đổi vé</th>
                            <th>Quy tắc vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flight->fares as $fare)
                            <tr>
                                <td>{{ $fare->cabinClass->ClassName ?? 'Không xác định' }}</td>
                                <td>{{ number_format($fare->BasePrice, 0, ',', '.') }}</td>
                                <td>{{ number_format($fare->Tax, 0, ',', '.') }}</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($fare->BasePrice + $fare->Tax, 0, ',', '.') }}
                                </td>
                                <td>{{ $fare->Refundable ? 'Có' : 'Không' }}</td>
                                <td>{{ $fare->Changeable ? 'Có' : 'Không' }}</td>
                                <td>{{ $fare->FareRules }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Form chọn option đặt vé -->
            <form action="{{ route('cart.store') }}" method="POST" class="detail-fare-form">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flight->FlightID }}">

                <div class="row mb-3">
                    <!-- Chọn hạng vé -->
                    <div class="col-md-4">
                        <label for="fare_id" class="detail-label">Chọn hạng vé</label>
                        <select name="fare_id" id="fare_id" class="detail-select"
                            onchange="window.location='{{ route('flights.detail', $flight->FlightID) }}?fare_id=' + this.value">
                            @foreach ($flight->fares as $fare)
                                <option value="{{ $fare->FareID }}"
                                    {{ ($selectedFare->FareID ?? '') == $fare->FareID ? 'selected' : '' }}>
                                    {{ $fare->cabinClass->ClassName ?? 'Không xác định' }} -
                                    {{ number_format($fare->BasePrice + $fare->Tax, 0, ',', '.') }} VND
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn ghế trống theo hạng vé -->
                    <div class="col-md-4">
                        <label for="seat_id" class="detail-label">Chọn ghế ngồi</label>
                        <select name="seat_id" id="seat_id" class="detail-select">
                            @foreach ($availableSeats as $seat)
                                <option value="{{ $seat->SeatID }}">{{ $seat->SeatNumber }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="col-md-4">
                        <label for="payment_method" class="detail-label">Phương thức thanh toán</label>
                        @if ($paymentMethods->isEmpty())
                            <select name="payment_method" id="payment_method" class="detail-select" disabled>
                                <option>Chưa có phương thức thanh toán</option>
                            </select>
                            @if (!Auth::check())
                                <p class="detail-text">Vui lòng đăng nhập để chọn phương thức thanh toán.</p>
                            @else
                                <p class="detail-text">Bạn chưa có phương thức thanh toán nào. Vui lòng thêm trong hồ sơ.
                                </p>
                            @endif
                        @else
                            <select name="payment_method" id="payment_method" class="detail-select">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->PaymentMethodID }}">
                                        {{ $method->PaymentType }} ({{ $method->Provider }})
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="submit" class="detail-btn">Lưu vé (thêm vào giỏ)</button>
                </div>
            </form>

            <form action="{{ route('cart.storeAndCheckout') }}" method="POST" class="detail-checkout-form mt-3">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flight->FlightID }}">
                <input type="hidden" name="fare_id" value="{{ $selectedFare->FareID }}">
                <input type="hidden" name="seat_id" value="{{ $selectedSeat->SeatID }}">
                <input type="hidden" name="payment_method" value="">
                <button type="submit" class="detail-btn">Thanh toán ngay</button>
            </form>

            <a href="{{ route('home') }}" class="detail-btn mt-4 d-inline-block text-center">
                ← Quay lại danh sách chuyến bay
            </a>
        </div>
    </div>
@endsection
