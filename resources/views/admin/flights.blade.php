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

    {{-- Header + nút thêm chuyến bay --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Danh sách chuyến bay</h3>
        <a href="{{ route('admin.flights.create') }}" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle"></i> Thêm chuyến bay mới
        </a>
    </div>

    {{-- Bảng danh sách --}}
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
                    <th style="width: 80px;">Xem</th>
                    <th style="width: 80px;">Sửa</th>
                    <th style="width: 80px;">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flights as $flight)
                <tr>
                    <td class="fw-semibold text-primary">{{ $flight->FlightNumber }}</td>
                    <td>{{ $flight->airline->AirlineName ?? '—' }}</td>
                    <td>{{ $flight->aircraft->AircraftCode ?? '—' }}</td>
                    <td>{{ $flight->departureAirport->AirportName ?? '—' }}</td>
                    <td>{{ $flight->arrivalAirport->AirportName ?? '—' }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($flight->DepartureTime)) }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($flight->ArrivalTime)) }}</td>
                    <td>
                        <span class="badge
                            @if($flight->Status === 'Scheduled') bg-info text-dark
                            @elseif($flight->Status === 'Departed') bg-success
                            @elseif($flight->Status === 'Cancelled') bg-danger
                            @else bg-secondary @endif px-3 py-2">
                            {{ $flight->Status }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('admin.flightDetail', $flight->FlightID) }}"
                        class="btn btn-sm btn-primary w-100 d-flex justify-content-center align-items-center"
                        style="height:36px;">
                            Xem
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.flights.edit', $flight->FlightID) }}"
                        class="btn btn-sm btn-warning text-white w-100 d-flex justify-content-center align-items-center"
                        style="height:36px;">
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
                                    class="btn btn-sm btn-danger w-100 d-flex justify-content-center align-items-center"
                                    style="height:36px;">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-muted py-4">Không có dữ liệu chuyến bay</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
