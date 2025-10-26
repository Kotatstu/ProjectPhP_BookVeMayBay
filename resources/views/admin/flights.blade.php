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

    <h3 class="mb-4">Danh sách chuyến bay</h3>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.flights.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm chuyến bay mới
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
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
                    <th style="width: 70px;">Xem</th>
                    <th style="width: 70px;">Sửa</th>
                    <th style="width: 70px;">Xóa</th>
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
                        <a href="{{ route('admin.flightDetail', $flight->FlightID) }}" 
                        class="btn btn-primary btn-sm w-100" 
                        title="Xem chi tiết">
                            Xem
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('admin.flights.edit', $flight->FlightID) }}" 
                        class="btn btn-warning btn-sm w-100 text-white" 
                        title="Chỉnh sửa">
                            Sửa
                        </a>
                    </td>

                    <td>
                        <form action="{{ route('admin.flights.delete', $flight->FlightID) }}" 
                            method="POST" 
                            onsubmit="return confirm('Bạn có chắc muốn xóa chuyến bay này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger btn-sm w-100" 
                                    title="Xóa">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
