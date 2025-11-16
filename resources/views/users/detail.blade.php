@extends('layouts.app')

@section('title', 'Chi tiết chuyến bay & Đường bay')

@section('content')
<div class="container detail-container my-5">

    <div class="card detail-card shadow-lg p-4">

        <!--THÔNG TIN CHUYẾN BAY -->
        <div class="row detail-flight-info g-0 align-items-center mb-4">
            <div class="col-md-3 text-center">
                <img src="{{ $flightDetail->airline_logo ?? '' }}" alt="{{ $flightDetail->airline_name ?? '' }}"
                     class="detail-airline-logo rounded">
            </div>
            <div class="col-md-9">
                <h4 class="mb-2 detail-flight-title">
                    {{ $flightDetail->from ?? '' }} → {{ $flightDetail->to ?? '' }}
                </h4>
                <p class="mb-1 detail-flight-date">
                    <i class="bi bi-calendar-event me-2"></i>
                    Ngày bay: <strong>{{ $flightDetail->departure_date ?? '' }}</strong>
                </p>
                <p class="mb-1 detail-flight-time">
                    <i class="bi bi-clock me-2"></i>
                    Giờ: <strong>{{ $flightDetail->departure_time ?? '' }} - {{ $flightDetail->arrival_time ?? '' }}</strong>
                </p>
                <p class="mb-0 detail-flight-airline">
                    <i class="bi bi-airplane me-2"></i>
                    Hãng: <strong>{{ $flightDetail->airline_name ?? '' }}</strong>
                </p>
            </div>
        </div>

        <hr>

        <!--BẢN ĐỒ ĐƯỜNG BAY -->
        <h5 class="mb-3 text-center detail-fare-title">Đường bay</h5>
        <div id="mapWrapper" class="position-relative mx-auto rounded shadow mb-4" style="width:900px; max-width:100%;">
            <img id="mapImage" src="{{ asset('images/worldMap.png') }}" class="d-block w-100 rounded" style="display:block; height:auto;">
            <canvas id="mapCanvas" class="position-absolute top-0 start-0" style="z-index:10; pointer-events:none;"></canvas>
        </div>

        <hr>

        <!--DANH SÁCH HẠNG VÉ -->
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
                    @forelse ($flight->fares ?? [] as $fare)
                        <tr>
                            <td>{{ $fare->cabinClass->ClassName ?? 'Không xác định' }}</td>
                            <td>{{ number_format($fare->BasePrice ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($fare->Tax ?? 0, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">{{ number_format(($fare->BasePrice ?? 0) + ($fare->Tax ?? 0), 0, ',', '.') }}</td>
                            <td>{{ $fare->Refundable ? 'Có' : 'Không' }}</td>
                            <td>{{ $fare->Changeable ? 'Có' : 'Không' }}</td>
                            <td>{{ $fare->FareRules ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Chưa có thông tin hạng vé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!--SƠ ĐỒ GHẾ -->
        <h5 class="mb-3 text-center detail-fare-title">Chọn ghế</h5>
        @php
            $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
            $columns = range(1, 20);
            $seatLayout = [];

            foreach ($rows as $row) {
                foreach ($columns as $col) {
                    $seatNumber = $col . $row;
                    $seat = $availableSeats->firstWhere('SeatNumber', $seatNumber);

                    if ($col == 3 && !in_array($row, ['C', 'D'])) $seat = null;
                    if ($col == 4 && !in_array($row, ['C', 'D'])) $seat = null;

                    $seatLayout[$row][$col] = $seat;
                }
            }
        @endphp

        <div class="seat-map-grid mb-4">
            @foreach ($rows as $row)
                <div class="seat-row d-flex mb-2">
                    @foreach ($columns as $col)
                        @php
                            $seat = $seatLayout[$row][$col];
                            if (!$seat) {
                                echo '<div class="seat-spacer"></div>';
                                continue;
                            }

                            if ($col <= 3) $key = 'first';
                            elseif ($col <= 6) $key = 'business';
                            else $key = 'economy';

                            $extraGap = $col == 3 || $col == 6 ? 'margin-right:25px;' : '';
                            $isClassAvailable = in_array($seat->CabinClassID, $availableCabinClassIds);
                            $isDisabled = $seat->IsBooked || $seat->IsSold || !$isClassAvailable;
                            $titleText = !$isClassAvailable ? "Vé {$seat->CabinClassName} không khả dụng." : '';
                        @endphp

                        <span title="{{ $titleText }}">
                            <button type="button"
                                    class="seat-btn {{ $key }} {{ $seat->IsBooked ? 'booked' : '' }} {{ $seat->IsSold ? 'sold' : '' }} {{ !$isClassAvailable ? 'disabled' : '' }}"
                                    style="{{ $extraGap }}"
                                    data-seat-id="{{ $seat->SeatID }}"
                                    data-original-class="{{ $key }}"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                {{ $seat->SeatNumber }}
                            </button>
                        </span>
                    @endforeach
                </div>

                @if ($row == 'B' || $row == 'D')
                    <div class="seat-row-gap"></div>
                @endif
            @endforeach
        </div>

        <!--PHƯƠNG THỨC THANH TOÁN-->
        <div class="row mb-3 detail-payment-box">
            <div class="col-md-4">
                <label for="payment_method" class="detail-payment-label">Phương thức thanh toán</label>

                @if (empty($paymentMethods) || $paymentMethods->isEmpty())
                    <select name="payment_method" id="payment_method" class="detail-payment-select" disabled>
                        <option>Chưa có phương thức thanh toán</option>
                    </select>
                @else
                    <select name="payment_method" id="payment_method" class="detail-payment-select">
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->PaymentMethodID }}">
                                {{ $method->PaymentType }} ({{ $method->Provider }})
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" id="selected_seats">
        <input type="hidden" id="checkout_selected_seats">

        <!--2 BUTTON NẰM CẠNH NHAU -->
        <div class="detail-btn-container mt-4">
            <!-- FORM LƯU VÉ -->
            <form action="{{ route('cart.store') }}" method="POST" id="save-form">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flight->FlightID ?? '' }}">
                <input type="hidden" name="selected_seats" id="save_selected_input">
                <input type="hidden" name="payment_method" id="save_payment_input">
                <button type="submit" class="detail-btn">Lưu vé (thêm vào giỏ)</button>
            </form>

            <!-- FORM THANH TOÁN -->
            <form action="{{ route('cart.storeAndCheckout') }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flight->FlightID ?? '' }}">
                <input type="hidden" name="seat_id" id="checkout_seat_input">
                <input type="hidden" name="payment_method" id="checkout_payment_input">
                <button type="submit" class="detail-btn">Thanh toán ngay</button>
            </form>
        </div>

    </div>
</div>

<!--JAVASCRIPT XỬ LÝ GHẾ VÀ BẢN ĐỒ-->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // -----------------------------
    // 1. XỬ LÝ CHỌN GHẾ
    // -----------------------------
    const seatButtons = document.querySelectorAll('.seat-btn:not(.booked):not(.sold):not(.disabled)');
    let selectedSeats = [];
    const saveInput = document.getElementById('save_selected_input');
    const checkoutInput = document.getElementById('checkout_seat_input');
    const paymentSelect = document.getElementById('payment_method');
    const savePaymentInput = document.getElementById('save_payment_input');
    const checkoutPaymentInput = document.getElementById('checkout_payment_input');

    seatButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const seatId = this.dataset.seatId;
            if (selectedSeats.includes(seatId)) {
                selectedSeats = selectedSeats.filter(id => id !== seatId);
                this.classList.remove('selected');
                this.classList.add(this.dataset.originalClass);
            } else {
                selectedSeats.push(seatId);
                this.classList.add('selected');
                this.classList.remove(this.dataset.originalClass);
            }
            saveInput.value = selectedSeats.join(',');
            checkoutInput.value = selectedSeats.join(',');
            if (paymentSelect) {
                savePaymentInput.value = paymentSelect.value;
                checkoutPaymentInput.value = paymentSelect.value;
            }
        });
    });

    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            savePaymentInput.value = this.value;
            checkoutPaymentInput.value = this.value;
        });
    }

    // -----------------------------
    // 2. VẼ ĐƯỜNG BAY TRÊN BẢN ĐỒ
    // -----------------------------
    const img = document.getElementById('mapImage');
    const canvas = document.getElementById('mapCanvas');
    const ctx = canvas.getContext('2d');

    const pix = {
        startX: Number("{{ $startX }}"),
        startY: Number("{{ $startY }}"),
        endX: Number("{{ $endX }}"),
        endY: Number("{{ $endY }}")
    };

    function resizeCanvas() {
        const rect = img.getBoundingClientRect();
        canvas.width  = Math.round(rect.width);
        canvas.height = Math.round(rect.height);
        canvas.style.left = img.offsetLeft + 'px';
        canvas.style.top  = img.offsetTop + 'px';
    }

    function drawLine() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const scaleX = canvas.width / img.naturalWidth;
        const scaleY = canvas.height / img.naturalHeight;
        const p1 = { x: pix.startX * scaleX, y: pix.startY * scaleY };
        const p2 = { x: pix.endX * scaleX,   y: pix.endY * scaleY };
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#007bff';
        ctx.beginPath();
        ctx.moveTo(p1.x, p1.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.stroke();

        ctx.fillStyle = '#fff';
        ctx.strokeStyle = '#007bff';
        ctx.beginPath(); ctx.arc(p1.x, p1.y, 4, 0, Math.PI*2); ctx.fill(); ctx.stroke();
        ctx.beginPath(); ctx.arc(p2.x, p2.y, 4, 0, Math.PI*2); ctx.fill(); ctx.stroke();
    }

    function redraw() {
        resizeCanvas();
        drawLine();
    }

    if (img.complete) requestAnimationFrame(redraw);
    else img.addEventListener('load', () => requestAnimationFrame(redraw));
    window.addEventListener('resize', () => requestAnimationFrame(redraw));
});
</script>
@endpush
