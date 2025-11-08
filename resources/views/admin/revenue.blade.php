@extends('layouts.admin')

@section('title', 'Thống kê doanh thu')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">Thống kê doanh thu</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Doanh thu theo tháng</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
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
                            <td>{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <strong>Doanh thu theo hãng hàng không</strong>
        </div>
        <div class="card-body">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>Hãng hàng không</th>
                        <th>Doanh thu (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenueByAirline as $item)
                        <tr>
                            <td>{{ $item->AirlineName }}</td>
                            <td>{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            <strong>Doanh thu theo hạng vé</strong>
        </div>
        <div class="card-body">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>Hạng vé</th>
                        <th>Doanh thu (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenueByCabin as $item)
                        <tr>
                            <td>{{ $item->ClassName }}</td>
                            <td>{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


