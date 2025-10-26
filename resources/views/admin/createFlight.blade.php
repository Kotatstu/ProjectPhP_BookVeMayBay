@extends('layouts.admin')

@section('title', 'Thêm chuyến bay mới')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">✈️ Thêm chuyến bay mới</h3>

    <form action="{{ route('admin.flights.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="AirlineID" class="form-label">Hãng hàng không</label>
                <select name="AirlineID" id="AirlineID" class="form-select" required>
                    <option value="">-- Chọn hãng hàng không --</option>
                    @foreach($airlines as $airline)
                        <option value="{{ $airline->AirlineID }}">{{ $airline->AirlineName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="AircraftID" class="form-label">Máy bay</label>
                <select name="AircraftID" id="AircraftID" class="form-select" required>
                    <option value="">-- Chọn máy bay --</option>
                    @foreach($aircrafts as $aircraft)
                        <option value="{{ $aircraft->AircraftID }}">{{ $aircraft->AircraftCode }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="FlightNumber" class="form-label">Mã chuyến bay</label>
                <input type="text" name="FlightNumber" class="form-control" required maxlength="10">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DepartureAirport" class="form-label">Sân bay đi</label>
                <select name="DepartureAirport" class="form-select" required>
                    <option value="">-- Chọn sân bay đi --</option>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->AirportCode }}">{{ $airport->AirportName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="ArrivalAirport" class="form-label">Sân bay đến</label>
                <select name="ArrivalAirport" class="form-select" required>
                    <option value="">-- Chọn sân bay đến --</option>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->AirportCode }}">{{ $airport->AirportName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DepartureTime" class="form-label">Giờ khởi hành</label>
                <input type="datetime-local" name="DepartureTime" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="ArrivalTime" class="form-label">Giờ đến</label>
                <input type="datetime-local" name="ArrivalTime" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="Status" class="form-label">Tình trạng</label>
            <select name="Status" class="form-select" required>
                <option value="Scheduled">Scheduled</option>
                <option value="Departed">Departed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm chuyến bay
        </button>
        <a href="{{ route('admin.flights') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
