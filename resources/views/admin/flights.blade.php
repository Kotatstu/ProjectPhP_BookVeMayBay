@extends('layouts.admin')

@section('title', 'Danh sách chuyến bay')

@section('content')
<div class="container mt-4">

    {{-- Thanh điều hướng con --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/airlines*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.airlines.index') }}">
                        ✈️ Hãng hàng không
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/aircrafts*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.aircrafts.index') }}">
                        🛩️ Máy bay
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/airports*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.airports.index') }}">
                        🏢 Sân bay
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/flights*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.flights') }}">
                        🗓️ Chuyến bay
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Danh sách chuyến bay --}}
    <h3 class="mb-4">Danh sách chuyến bay</h3>

    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>Mã chuyến bay</th>
                <th>Hãng hàng không</th>
                <th>Máy bay</th>
                <th>Sân bay đi</th>
                <th>Sân bay đến</th>
                <th>Giờ khởi hành</th>
                <th>Giờ đến</th>
                <th>Tình trạng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flights as $flight)
            <tr>
                <td>{{ $flight->FlightNumber }}</td>
                <td>{{ $flight->airline->AirlineName ?? '—' }}</td>
                <td>{{ $flight->aircraft->AircraftCode ?? '—' }}</td>
                <td>{{ $flight->departureAirport->AirportName ?? '—' }}</td>
                <td>{{ $flight->arrivalAirport->AirportName ?? '—' }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($flight->DepartureTime)) }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($flight->ArrivalTime)) }}</td>
                <td>
                    <span class="badge 
                        @if($flight->Status === 'Scheduled') bg-info 
                        @elseif($flight->Status === 'Departed') bg-success 
                        @elseif($flight->Status === 'Cancelled') bg-danger 
                        @else bg-secondary @endif">
                        {{ $flight->Status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.flightDetail', $flight->FlightID) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
