@extends('layouts.admin')

@section('title', 'Sửa chuyến bay')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">✏️ Sửa chuyến bay: {{ $flight->FlightNumber }}</h3>

    <form action="{{ route('admin.flights.update', $flight->FlightID) }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="AirlineID" class="form-label">Hãng hàng không</label>
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
                <label for="AircraftID" class="form-label">Máy bay</label>
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
                <label for="FlightNumber" class="form-label">Mã chuyến bay</label>
                <input type="text" name="FlightNumber" class="form-control" value="{{ $flight->FlightNumber }}" required maxlength="10">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DepartureAirport" class="form-label">Sân bay đi</label>
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
                <label for="ArrivalAirport" class="form-label">Sân bay đến</label>
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
                <label for="DepartureTime" class="form-label">Giờ khởi hành</label>
                <input type="datetime-local" name="DepartureTime" class="form-control" 
                    value="{{ date('Y-m-d\TH:i', strtotime($flight->DepartureTime)) }}" required>
            </div>

            <div class="col-md-6">
                <label for="ArrivalTime" class="form-label">Giờ đến</label>
                <input type="datetime-local" name="ArrivalTime" class="form-control"
                    value="{{ date('Y-m-d\TH:i', strtotime($flight->ArrivalTime)) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="Status" class="form-label">Tình trạng</label>
            <select name="Status" class="form-select" required>
                <option value="Scheduled" {{ $flight->Status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Departed" {{ $flight->Status == 'Departed' ? 'selected' : '' }}>Departed</option>
                <option value="Cancelled" {{ $flight->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu thay đổi
        </button>
        <a href="{{ route('admin.flights') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
