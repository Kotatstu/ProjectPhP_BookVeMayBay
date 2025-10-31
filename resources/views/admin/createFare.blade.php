@extends('layouts.admin')

@section('title', 'Thêm giá vé mới')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4">Thêm giá vé mới</h2>


<form action="{{ route('admin.fares.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="FlightID" class="form-label">Chuyến bay</label>
        <select class="form-select" id="FlightID" name="FlightID" required>
            <option value="">-- Chọn chuyến bay --</option>
            @foreach($flights as $flight)
                <option value="{{ $flight->FlightID }}">
                    {{ $flight->FlightNumber }} - {{ $flight->departureAirport->City ?? '' }} → {{ $flight->arrivalAirport->City ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="CabinClassID" class="form-label">Hạng ghế</label>
        <select class="form-select" id="CabinClassID" name="CabinClassID" required>
            <option value="">-- Chọn hạng ghế --</option>
            @foreach($classes as $class)
                <option value="{{ $class->CabinClassID }}">{{ $class->ClassName }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="BasePrice" class="form-label">Giá gốc</label>
            <input type="number" step="0.01" name="BasePrice" id="BasePrice" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="Tax" class="form-label">Thuế</label>
            <input type="number" step="0.01" name="Tax" id="Tax" class="form-control" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="Currency" class="form-label">Tiền tệ</label>
        <input type="text" name="Currency" id="Currency" class="form-control" value="VND" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Hoàn vé</label>
            <select name="Refundable" class="form-select">
                <option value="1">Có</option>
                <option value="0">Không</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Đổi vé</label>
            <select name="Changeable" class="form-select">
                <option value="1">Có</option>
                <option value="0">Không</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="FareRules" class="form-label">Điều khoản</label>
        <textarea name="FareRules" id="FareRules" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-end">
        <a href="{{ route('admin.fares.index') }}" class="btn btn-secondary me-2">Hủy</a>
        <button type="submit" class="btn btn-primary">Thêm mới</button>
    </div>
</form>


</div>
@endsection
