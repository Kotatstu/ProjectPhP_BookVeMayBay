@extends('layouts.admin')

@section('title', 'Thống kê doanh thu')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center text-primary fw-bold">Thống kê doanh thu</h2>

    {{-- Tổng quan doanh thu --}}
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100 text-center bg-primary text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-cash-stack fs-1 mb-2"></i>
                    <h5 class="card-title">Tổng doanh thu</h5>
                    <p id="totalRevenue" class="fs-3 fw-bold">0 VNĐ</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100 text-center bg-success text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-calendar-check fs-1 mb-2"></i>
                    <h5 class="card-title">Số tháng có doanh thu</h5>
                    <p class="fs-3 fw-bold">{{ count($revenueByMonth) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100 text-center bg-info text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-airplane fs-1 mb-2"></i>
                    <h5 class="card-title">Số hãng hàng không</h5>
                    <p class="fs-3 fw-bold">{{ count($revenueByAirline) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bảng doanh thu theo tháng --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">Doanh thu theo tháng</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tháng</th>
                            <th>Năm</th>
                            <th>Doanh thu (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueByMonth as $item)
                            <tr>
                                <td>{{ $item->month }}</td>
                                <td>{{ $item->year }}</td>
                                <td class="revenue">{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bảng doanh thu theo hãng hàng không --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-success text-white fw-bold">Doanh thu theo hãng hàng không</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Hãng hàng không</th>
                            <th>Doanh thu (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueByAirline as $item)
                            <tr>
                                <td>{{ $item->AirlineName }}</td>
                                <td class="revenue">{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bảng doanh thu theo hạng vé --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-info text-white fw-bold">Doanh thu theo hạng vé</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Hạng vé</th>
                            <th>Doanh thu (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueByCabin as $item)
                            <tr>
                                <td>{{ $item->ClassName }}</td>
                                <td class="revenue">{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script tính tổng doanh thu --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let total = 0;

    document.querySelectorAll('.revenue').forEach(td => {
        let val = parseFloat(td.textContent.replace(/\./g, '').replace(/,/g, ''));
        if(!isNaN(val)) total += val;
    });

    const formatter = new Intl.NumberFormat('vi-VN');
    document.getElementById('totalRevenue').textContent = formatter.format(total) + ' VNĐ';
});
</script>
@endsection
