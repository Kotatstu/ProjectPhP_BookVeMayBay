@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg p-4">
        <!-- Thông tin chuyến bay -->
        <div class="row g-0 align-items-center mb-4">
            <div class="col-md-3 text-center">
                <img src="{{ $flightDetail->airline_logo }}"
                     alt="{{ $flightDetail->airline_name }}"
                     class="img-fluid rounded"
                     style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-md-9">
                <h4 class="mb-2">
                    {{ $flightDetail->from }} → {{ $flightDetail->to }}
                </h4>
                <p class="mb-1"><i class="bi bi-calendar-event text-primary me-2"></i>
                    Ngày bay: <strong>{{ $flightDetail->departure_date }}</strong>
                </p>
                <p class="mb-1"><i class="bi bi-clock text-primary me-2"></i>
                    Giờ: <strong>{{ $flightDetail->departure_time }} - {{ $flightDetail->arrival_time }}</strong>
                </p>
                <p class="mb-0"><i class="bi bi-airplane text-primary me-2"></i>
                    Hãng: <strong>{{ $flightDetail->airline_name }}</strong>
                </p>
            </div>
        </div>

        <hr>

        <!-- Danh sách hạng vé -->
        <h5 class="mb-3 text-center text-primary fw-bold">Các hạng vé & giá chi tiết</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Hạng vé</th>
                        <th>Giá cơ bản (VND)</th>
                        <th>Thuế (VND)</th>
                        <th>Tổng giá (VND)</th>
                        <th>Hoàn vé</th>
                        <th>Đổi vé</th>
                        <th>Quy tắc vé</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flight->fares as $fare)
                        <tr>
                            <td>{{ $fare->cabinClass->ClassName ?? 'Không xác định'}}</td>
                            <td>{{ number_format($fare->BasePrice, 0, ',', '.') }}</td>
                            <td>{{ number_format($fare->Tax, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">
                                {{ number_format($fare->BasePrice + $fare->Tax, 0, ',', '.') }}
                            </td>
                            <td>{{ $fare->Refundable ? 'Có' : 'Không' }}</td>
                            <td>{{ $fare->Changeable ? 'Có' : 'Không' }}</td>
                            <td>{{ $fare->FareRules }}</td>
                            <td>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="flight_id" value="{{ $flightDetail->id }}">
                                    <input type="hidden" name="fare_id" value="{{ $fare->FareID }}">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Thêm vào giỏ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">
            ← Quay lại danh sách chuyến bay
        </a>
    </div>
</div>
@endsection
