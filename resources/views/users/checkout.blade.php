@extends('layouts.app')

@section('content')
    <div class="container checkout-container my-5">
        <h3 class="checkout-header mb-4">
            <i class="bi bi-credit-card-2-front me-2"></i>Thanh toán vé
        </h3>

        <div class="row g-4">
            {{-- Thông tin cá nhân --}}
            <div class="col-md-6">
                <div class="checkout-card checkout-info p-3">
                    <h5 class="checkout-section-title"><i class="bi bi-person-circle"></i> Thông tin cá nhân</h5>
                    <hr>
                    <p><strong>Họ và tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ optional($customer)->Phone ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Giới tính:</strong> {{ optional($customer)->Gender ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Ngày sinh:</strong>
                        {{ optional($customer)->DateOfBirth ? date('d/m/Y', strtotime($customer->DateOfBirth)) : 'Chưa cập nhật' }}
                    </p>
                    <p><strong>Quốc tịch:</strong> {{ optional($customer)->Nationality ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>

            {{-- Thông tin vé & thanh toán --}}
            <div class="col-md-6">
                <div class="checkout-card checkout-ticket p-3">
                    <h5 class="checkout-section-title"><i class="bi bi-ticket-perforated"></i> Thông tin vé</h5>
                    <hr>

                    @if ($ticket->flight->airline->LogoURL ?? false)
                        <img src="{{ asset('storage/airline/' . $ticket->flight->airline->LogoURL) }}"
                            alt="{{ $ticket->flight->airline->AirlineName }}" class="mb-2 checkout-ticket-logo">
                    @endif

                    <p><strong>Hãng:</strong> {{ $ticket->flight->airline->AirlineName ?? '' }}</p>
                    <p><strong>Chặng:</strong>
                        {{ $ticket->flight->departureAirport->AirportName ?? $ticket->flight->DepartureAirport }} →
                        {{ $ticket->flight->arrivalAirport->AirportName ?? $ticket->flight->ArrivalAirport }}
                    </p>
                    <p><strong>Ngày bay:</strong> {{ date('d/m/Y', strtotime($ticket->flight->DepartureTime)) }}</p>
                    <p><strong>Giờ:</strong> {{ date('H:i', strtotime($ticket->flight->DepartureTime)) }} -
                        {{ date('H:i', strtotime($ticket->flight->ArrivalTime)) }}</p>
                    <p><strong>Hạng vé:</strong> {{ $ticket->fare->cabinClass->ClassName ?? '' }}</p>
                    <p><strong>Ghế:</strong> {{ $ticket->seat->SeatNumber ?? '' }}</p>
                    <p class="fw-bold text-success"><strong>Tổng tiền:</strong>
                        {{ number_format($ticket->TotalAmount, 0, ',', '.') }} VND
                    </p>

                    <form action="{{ route('cart.checkout', $ticket->TicketID) }}" method="POST"
                        class="checkout-form mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_method" class="form-label fw-bold">Phương thức thanh toán</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">-- Chọn --</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->PaymentMethodID }}"
                                        {{ $ticket->PaymentMethodID == $method->PaymentMethodID ? 'selected' : '' }}>
                                        {{ $method->PaymentType }} ({{ $method->Provider }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="checkout-btn mt-2">Thanh toán</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
