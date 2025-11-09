@extends('layouts.admin')

@section('title', 'Sửa chuyến bay')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-start text-dark fw-bold">
        <i data-lucide="edit-3" class="me-2"></i> Sửa chuyến bay: {{ $flight->FlightNumber }}
    </h3>

    <form action="{{ route('admin.flights.update', $flight->FlightID) }}" method="POST" class="card p-4 shadow-sm border-0 rounded-4">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="AirlineID" class="form-label fw-semibold">
                    <i data-lucide="building-2" class="me-1 text-primary"></i> Hãng hàng không
                </label>
                <select name="AirlineID" class="form-select" required>
                    @foreach($airlines as $airline)
                        <option value="{{ $airline->AirlineID }}"
                            {{ $flight->AirlineID == $airline->AirlineID ? 'selected' : '' }}>
                            {{ $airline->AirlineName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="AircraftID" class="form-label fw-semibold">
                    <i data-lucide="plane" class="me-1 text-success"></i> Máy bay
                </label>
                <select name="AircraftID" class="form-select" required>
                    @foreach($aircrafts as $aircraft)
                        <option value="{{ $aircraft->AircraftID }}"
                            {{ $flight->AircraftID == $aircraft->AircraftID ? 'selected' : '' }}>
                            {{ $aircraft->AircraftCode }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="FlightNumber" class="form-label fw-semibold">
                    <i data-lucide="hash" class="me-1 text-warning"></i> Mã chuyến bay
                </label>
                <input type="text" name="FlightNumber" class="form-control"
                    value="{{ $flight->FlightNumber }}" required maxlength="10">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DepartureAirport" class="form-label fw-semibold">
                    <i data-lucide="plane-takeoff" class="me-1 text-info"></i> Sân bay đi
                </label>
                <select name="DepartureAirport" class="form-select" required>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->AirportCode }}"
                            {{ $flight->DepartureAirport == $airport->AirportCode ? 'selected' : '' }}>
                            {{ $airport->AirportName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="ArrivalAirport" class="form-label fw-semibold">
                    <i data-lucide="plane-landing" class="me-1 text-danger"></i> Sân bay đến
                </label>
                <select name="ArrivalAirport" class="form-select" required>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->AirportCode }}"
                            {{ $flight->ArrivalAirport == $airport->AirportCode ? 'selected' : '' }}>
                            {{ $airport->AirportName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DepartureTime" class="form-label fw-semibold">
                    <i data-lucide="clock" class="me-1 text-primary"></i> Giờ khởi hành
                </label>
                <input type="datetime-local" name="DepartureTime" class="form-control"
                    value="{{ date('Y-m-d\TH:i', strtotime($flight->DepartureTime)) }}" required>
            </div>

            <div class="col-md-6">
                <label for="ArrivalTime" class="form-label fw-semibold">
                    <i data-lucide="clock-3" class="me-1 text-success"></i> Giờ đến
                </label>
                <input type="datetime-local" name="ArrivalTime" class="form-control"
                    value="{{ date('Y-m-d\TH:i', strtotime($flight->ArrivalTime)) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="Status" class="form-label fw-semibold">
                <i data-lucide="activity" class="me-1 text-secondary"></i> Tình trạng
            </label>
            <select name="Status" class="form-select" required>
                <option value="Scheduled" {{ $flight->Status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Departed" {{ $flight->Status == 'Departed' ? 'selected' : '' }}>Departed</option>
                <option value="Cancelled" {{ $flight->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success px-4 me-2 rounded-pill shadow-sm">
                <i data-lucide="save" class="me-1"></i> Lưu thay đổi
            </button>
            <a href="{{ route('admin.flights') }}" class="btn btn-outline-secondary px-4 rounded-pill shadow-sm">
                <i data-lucide="arrow-left" class="me-1"></i> Quay lại
            </a>
        </div>
    </form>
</div>

{{-- Kích hoạt Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
