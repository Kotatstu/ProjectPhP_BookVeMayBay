@extends('layouts.admin')

@section('title', 'Thêm chuyến bay mới')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h3 class="mb-4 text-start text-dark fw-bold">
            <i data-lucide="plane" class="me-2"></i> Thêm chuyến bay mới
        </h3>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.flights.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4 mb-3">
                    <div class="col-md-4">
                        <label for="AirlineID" class="form-label fw-semibold">
                            <i data-lucide="building-2" class="me-1"></i> Hãng hàng không
                        </label>
                        <select name="AirlineID" id="AirlineID" class="form-select rounded-3" required>
                            <option value="">-- Chọn hãng hàng không --</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->AirlineID }}">{{ $airline->AirlineName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="AircraftID" class="form-label fw-semibold">
                            <i data-lucide="plane-takeoff" class="me-1"></i> Máy bay
                        </label>
                        <select name="AircraftID" id="AircraftID" class="form-select rounded-3" required>
                            <option value="">-- Chọn máy bay --</option>
                            @foreach($aircrafts as $aircraft)
                                <option value="{{ $aircraft->AircraftID }}">{{ $aircraft->AircraftCode }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="FlightNumber" class="form-label fw-semibold">
                            <i data-lucide="barcode" class="me-1"></i> Mã chuyến bay
                        </label>
                        <input type="text" name="FlightNumber" class="form-control rounded-3" required maxlength="10">
                    </div>
                </div>

                <div class="row g-4 mb-3">
                    <div class="col-md-6">
                        <label for="DepartureAirport" class="form-label fw-semibold">
                            <i data-lucide="navigation" class="me-1"></i> Sân bay đi
                        </label>
                        <select name="DepartureAirport" class="form-select rounded-3" required>
                            <option value="">-- Chọn sân bay đi --</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->AirportCode }}">{{ $airport->AirportName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="ArrivalAirport" class="form-label fw-semibold">
                            <i data-lucide="map-pin" class="me-1"></i> Sân bay đến
                        </label>
                        <select name="ArrivalAirport" class="form-select rounded-3" required>
                            <option value="">-- Chọn sân bay đến --</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->AirportCode }}">{{ $airport->AirportName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-4 mb-3">
                    <div class="col-md-6">
                        <label for="DepartureTime" class="form-label fw-semibold">
                            <i data-lucide="clock" class="me-1"></i> Giờ khởi hành
                        </label>
                        <input type="datetime-local" name="DepartureTime" class="form-control rounded-3" required>
                    </div>

                    <div class="col-md-6">
                        <label for="ArrivalTime" class="form-label fw-semibold">
                            <i data-lucide="clock-4" class="me-1"></i> Giờ đến
                        </label>
                        <input type="datetime-local" name="ArrivalTime" class="form-control rounded-3" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="Status" class="form-label fw-semibold">
                        <i data-lucide="activity" class="me-1"></i> Tình trạng
                    </label>
                    <select name="Status" class="form-select rounded-3" required>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Departed">Departed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.flights') }}" class="btn btn-outline-secondary rounded-3 px-4">
                        <i data-lucide="arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                        <i data-lucide="plus-circle"></i> Thêm chuyến bay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Lucide icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
