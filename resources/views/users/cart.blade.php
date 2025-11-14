@extends('layouts.app')

@section('content')
    <div class="container cart-container">
        <h3 class="mb-4 fw-bold text-primary">
            <i class="bi bi-cart4 me-2"></i>Giỏ hàng
        </h3>

        @php
            $pendingTickets = $tickets->where('Status', 'Chờ thanh toán');
            $paidTickets = $tickets->where('Status', 'Đã thanh toán');
            $cancelledTickets = $tickets->where('Status', 'Đã huỷ');
        @endphp

        {{-- Vé chờ thanh toán --}}
        @if ($pendingTickets->isNotEmpty())
            <h5 class="mt-4 text-primary">Vé chờ thanh toán</h5>
            <table class="table table-bordered cart-table align-middle text-center cart-table-pending">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Hãng</th>
                        <th>Chặng</th>
                        <th>Ngày bay</th>
                        <th>Giờ</th>
                        <th>Hạng vé</th>
                        <th>Ghế</th>
                        <th>Giá vé</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingTickets as $ticket)
                        <tr>
                            <td>
                                @php
                                    $logoFile = $ticket->flight->airline->LogoURL ?? null;
                                    $logoPath = public_path('storage/airline/' . $logoFile);

                                    $logoUrl =
                                        $logoFile && file_exists($logoPath)
                                            ? asset('storage/airline/' . $logoFile)
                                            : asset('images/default.jpg');
                                @endphp

                                <img src="{{ $logoUrl }}" class="cart-logo"
                                    alt="{{ $ticket->flight->airline->AirlineName ?? 'logo' }}">
                            </td>
                            <td>{{ $ticket->flight->airline->AirlineName ?? '' }}</td>
                            <td>{{ $ticket->flight->departureAirport->AirportName ?? '' }} ->
                                {{ $ticket->flight->arrivalAirport->AirportName ?? '' }}</td>

                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureTime)->format('d/m/Y') }}</td>

                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureTime)->format('H:i') ?? '' }} -
                                {{ \Carbon\Carbon::parse($ticket->flight->ArrivalTime)->format('H:i') ?? '' }}</td>

                            <td>{{ $ticket->fare->cabinClass->ClassName ?? '' }}</td>
                            <td>{{ $ticket->seat->SeatNumber ?? '' }}</td>
                            <td class="fw-bold text-success">{{ number_format($ticket->TotalAmount, 0, ',', '.') }} VND</td>
                            <td>{{ $ticket->Status }}</td>
                            <td class="cart-btn-group">
                                {{-- Thanh toán --}}
                                <form action="{{ route('cart.checkoutForm', $ticket->TicketID) }}" method="GET"
                                    class="d-inline-block">
                                    <button type="submit" class="cart-btn cart-btn-primary">
                                        <i class="bi bi-credit-card"></i> Thanh toán
                                    </button>
                                </form>

                                {{-- Xoá --}}
                                <form action="{{ route('cart.remove', $ticket->TicketID) }}" method="POST"
                                    class="d-inline-block" onsubmit="return confirm('Xóa vé này khỏi giỏ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-btn cart-btn-danger">
                                        <i class="bi bi-trash3"></i> Xóa
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <form action="{{ route('cart.edit', $ticket->TicketID) }}" method="GET"
                                    class="d-inline-block">
                                    <button type="submit" class="cart-btn cart-btn-warning">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Vé đã thanh toán --}}
        @if ($paidTickets->isNotEmpty())
            <h5 class="mt-4 text-success">Vé đã thanh toán</h5>
            <table class="table table-bordered cart-table align-middle text-center cart-table-paid">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Hãng</th>
                        <th>Chặng</th>
                        <th>Ngày bay</th>
                        <th>Giờ</th>
                        <th>Hạng vé</th>
                        <th>Ghế</th>
                        <th>Giá vé</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paidTickets as $ticket)
                        <tr>
                            <td>
                                <img src="{{ $ticket->flight->airline->AirlineLogo ?? '' }}" class="cart-logo">
                            </td>
                            <td>{{ $ticket->flight->airline->AirlineName ?? '' }}</td>
                            <td>{{ $ticket->flight->departureAirport->AirportName ?? '' }} ->
                                {{ $ticket->flight->arrivalAirport->AirportName ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureDate)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureTime)->format('H:i') ?? '' }} -
                                {{ \Carbon\Carbon::parse($ticket->flight->ArrivalTime)->format('H:i') ?? '' }}</td>
                            <td>{{ $ticket->fare->cabinClass->ClassName ?? '' }}</td>
                            <td>{{ $ticket->seat->SeatNumber ?? '' }}</td>
                            <td class="fw-bold text-success">{{ number_format($ticket->TotalAmount, 0, ',', '.') }} VND
                            </td>
                            <td>{{ $ticket->Status }}</td>
                            <td class="cart-btn-group">
                                {{-- Huỷ vé --}}
                                <form action="{{ route('cart.cancel', $ticket->TicketID) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="cart-btn cart-btn-warning">
                                        <i class="bi bi-x-circle"></i> Huỷ vé
                                    </button>
                                </form>

                                {{-- Xoá --}}
                                <form action="{{ route('cart.remove', $ticket->TicketID) }}" method="POST"
                                    class="d-inline-block" onsubmit="return confirm('Xóa vé này khỏi giỏ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-btn cart-btn-danger">
                                        <i class="bi bi-trash3"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Vé đã huỷ --}}
        @if ($cancelledTickets->isNotEmpty())
            <h5 class="mt-4 text-secondary">Vé đã huỷ</h5>
            <table class="table table-bordered cart-table align-middle text-center cart-table-cancelled">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Hãng</th>
                        <th>Chặng</th>
                        <th>Ngày bay</th>
                        <th>Giờ</th>
                        <th>Hạng vé</th>
                        <th>Ghế</th>
                        <th>Giá vé</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cancelledTickets as $ticket)
                        <tr>
                            <td>
                                <img src="{{ $ticket->flight->airline->AirlineLogo ?? '' }}" class="cart-logo">
                            </td>
                            <td>{{ $ticket->flight->airline->AirlineName ?? '' }}</td>
                            <td>{{ $ticket->flight->departureAirport->AirportName ?? '' }} ->
                                {{ $ticket->flight->arrivalAirport->AirportName ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureDate)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->flight->DepartureTime)->format('H:i') ?? '' }} -
                                {{ \Carbon\Carbon::parse($ticket->flight->ArrivalTime)->format('H:i') ?? '' }}</td>
                            <td>{{ $ticket->fare->cabinClass->ClassName ?? '' }}</td>
                            <td>{{ $ticket->seat->SeatNumber ?? '' }}</td>
                            <td class="fw-bold text-success">{{ number_format($ticket->TotalAmount, 0, ',', '.') }} VND
                            </td>
                            <td>{{ $ticket->Status }}</td>
                            <td class="cart-btn-group">
                                {{-- Chỉ Xoá --}}
                                <form action="{{ route('cart.remove', $ticket->TicketID) }}" method="POST"
                                    class="d-inline-block" onsubmit="return confirm('Xóa vé này khỏi giỏ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-btn cart-btn-danger">
                                        <i class="bi bi-trash3"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        {{-- Tổng tiền chỉ cộng vé chờ thanh toán --}}
        <div class="text-end fw-bold fs-5 mt-3 cart-total">
            Tổng cộng:
            <span class="text-danger">
                {{ number_format($pendingTickets->sum('TotalAmount'), 0, ',', '.') }} VND
            </span>
        </div>
        <div class="cart-btn-wrapper">
            @if ($pendingTickets->count() > 0)
                @if ($pendingTickets->count() == 1)
                    <form action="{{ route('cart.checkoutForm', $pendingTickets->first()->TicketID) }}" method="GET">
                        <button type="submit" class="cart-checkout-btn cart-checkout-btn-single">Thanh toán vé</button>
                    </form>
                @else
                    <form action="{{ route('cart.checkoutAllForm') }}" method="GET">
                        @csrf
                        <input type="hidden" name="ticket_ids"
                            value="{{ $pendingTickets->pluck('TicketID')->implode(',') }}">
                        <button type="submit" class="cart-checkout-btn cart-checkout-btn-multiple">Thanh toán tất
                            cả</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
@endsection
