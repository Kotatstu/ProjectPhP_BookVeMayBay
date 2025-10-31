@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Bảng điều khiển quản trị</h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        <div class="col">
            <button id="btnUsers" class="btn btn-primary w-100 py-4">
                <i class="bi bi-people fs-2"></i><br> Quản lý người dùng
            </button>

            <!--Khắc chế bệnh ngu của javascript khi dùng blade-->
            <script>
            document.getElementById('btnUsers').onclick = function() {
                window.location.href = "{{ route('admin.users') }}";
            };
            </script>

        </div>
        <div class="col">
            <button id="btnFlights" class="btn btn-success w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-airplane fs-2"></i><br> Quản lý chuyến bay
            </button>

            <script>
            document.getElementById('btnFlights').onclick = function() {
                window.location.href = "{{ route('admin.flights') }}";
            };
            </script>

        </div>
        <div class="col">
            <button id="btnTickets" class="btn btn-warning w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-ticket-detailed fs-2"></i><br> Quản lý vé
            </button>

            <script>
            document.getElementById('btnTickets').onclick = function() {
                window.location.href = "{{ route('admin.tickets.index') }}";
            };
            </script>
        </div>
        

        <div class="col">
            <button id="btnPayment" class="btn btn-info w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-cash-coin fs-2"></i><br> Quản lý thanh toán
            </button>

            <script>
            document.getElementById('btnPayment').onclick = function() {
                window.location.href = "{{ route('admin.fares.index') }}";
            };
            </script>
        </div>
        <div class="col">
            <button class="btn btn-secondary w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-bar-chart-line fs-2"></i><br> Báo cáo thống kê
            </button>
        </div>
        <div class="col">
            <button id="btnMembers" class="btn btn-danger w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-gear fs-2"></i><br> Thành viên nhóm
            </button>

            <script>
            document.getElementById('btnMembers').onclick = function() {
                window.location.href = "{{ route('members') }}";
            };
            </script>
        </div>

        <div class="col"></div> 
        <div class="col">
            <button id="btnProfile" class="btn btn-outline-success w-100 py-4" onclick="window.location.href=''">
                <i class="bi bi-person-badge fs-2"></i><br> Hồ sơ người dùng
            </button>

            <script>
            document.getElementById('btnProfile').onclick = function() {
                window.location.href = "{{ route('user.info') }}";
            };
            </script>

        </div>
        <div class="col"></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush