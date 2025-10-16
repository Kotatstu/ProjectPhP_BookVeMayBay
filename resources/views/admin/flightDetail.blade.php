@extends('layouts.admin')

@section('title', 'Chi tiết chuyến bay')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Chi tiết chuyến bay: {{ $flight->FlightNumber }}</h3>

    <div class="card p-3 mb-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Hãng hàng không:</strong> {{ $flight->airline->AirlineName ?? '—' }}
            </div>
            <div class="col-md-6">
                <strong>Máy bay:</strong> {{ $flight->aircraft->AircraftCode ?? '—' }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Khởi hành:</strong> {{ $flight->departureAirport->AirportName ?? '—' }} <br>
                <small>{{ date('d/m/Y H:i', strtotime($flight->DepartureTime)) }}</small>
            </div>
            <div class="col-md-6">
                <strong>Điểm đến:</strong> {{ $flight->arrivalAirport->AirportName ?? '—' }} <br>
                <small>{{ date('d/m/Y H:i', strtotime($flight->ArrivalTime)) }}</small>
            </div>
        </div>
        <div class="mb-3">
            <strong>Tình trạng:</strong> 
            <span class="badge bg-primary">{{ $flight->Status }}</span>
        </div>
    </div>

    <h5>Bảng giá vé</h5>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Hạng ghế</th>
                <th>Giá cơ bản</th>
                <th>Thuế</th>
                <th>Hoàn tiền</th>
                <th>Đổi vé</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flight->fares as $fare)
            <tr>
                <td>{{ $fare->cabinClass->ClassName }}</td>
                <td>{{ number_format($fare->BasePrice, 0, ',', '.') }} {{ $fare->Currency }}</td>
                <td>{{ number_format($fare->Tax, 0, ',', '.') }} {{ $fare->Currency }}</td>
                <td>{{ $fare->Refundable ? 'Có' : 'Không' }}</td>
                <td>{{ $fare->Changeable ? 'Có' : 'Không' }}</td>
                <td>{{ $fare->FareRules ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-start align-items-center mb-3">
        <span class="me-3"><span class="seat available"></span> Còn trống</span>
        <span class="me-3"><span class="seat booked"></span> Đã đặt</span>
        <span><span class="seat selected"></span> Đang chọn</span>
    </div>

    <div class="seat-map-container">
        <div class="seat-map justify-content-center">
            @php
                $cols = ['A', 'B', 'C', 'D', 'E', 'F']; // 6 ghế mỗi hàng
                $rows = range(1, 20); // 20 hàng
                $seatCounter = 0;
            @endphp

            @foreach($rows as $row)
                @php
                    $visibleCols = $cols;
                    if (in_array($row, [3, 4])) {
                        $visibleCols = array_slice($cols, 0, 2); // bỏ bớt ghế hàng 3,4
                    }
                @endphp

                <div class="seat-row justify-content-center">
                    <div class="seat-label">{{ $row }}</div>

                    @foreach($visibleCols as $col)
                        @php
                            $seatCounter++;
                            $isBooked = in_array($seatCounter, $bookedSeats);
                        @endphp

                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}">
                            {{ $row . $col }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>


    <div class="text-end">
        <a href="{{ route('admin.flights') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
</div>
@endsection

<style>
.seat {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 3px;
    font-size: 14px;
    cursor: pointer;
    color: white;
    transition: all 0.2s ease;
}

.seat.available {
    background-color: #28a745; /* xanh lá – còn trống */
}

.seat.booked {
    background-color: #dc3545; /* đỏ – đã đặt */
    cursor: not-allowed;
    opacity: 0.7;
}

.seat.selected {
    background-color: #ffc107; /* vàng – đang chọn */
    color: #000;
}

.seat:hover:not(.booked):not(.selected) {
    transform: scale(1.05);
}

.seat-label {
    width: 30px;
    text-align: right;
    margin-right: 5px;
}

.seat-map-container {
    display: flex;
    justify-content: center;
}

.seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
}
</style>
