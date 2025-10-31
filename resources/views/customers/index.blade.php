@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
    }
    .customer-container {
        padding: 80px 0; /* Tạo khoảng trống trên dưới */
        display: flex;
        justify-content: center;
    }
    .customer-card {
        width: 90%;
        max-width: 1100px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 30px;
    }
    .table th {
        background-color: #007bff;
        color: white;
        text-align: center;
    }
    .table td {
        vertical-align: middle;
        text-align: center;
    }
    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 25px;
        font-weight: 600;
    }
</style>

<div class="customer-container">
    <div class="customer-card">
        <h2><i class="bi bi-people-fill me-2"></i>Danh sách khách hàng</h2>

        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Trạng thái</th>
                    <th>Cấp độ</th>
                    <th>Điểm</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->CustomerID }}</td>
                        <td>{{ $customer->user->name ?? '-' }}</td>
                        <td>{{ $customer->user->email ?? '-' }}</td>
                        <td>{{ $customer->Phone ?? '-' }}</td>
                        <td>
                            @if($customer->is_member)
                                <span class="badge bg-success">Hội viên</span>
                            @else
                                <span class="badge bg-secondary">Chưa là hội viên</span>
                            @endif
                        </td>
                        <td>{{ $customer->loyaltyProgram->MembershipLevel ?? '-' }}</td>
                        <td class="fw-semibold text-primary">{{ $customer->loyaltyProgram->Points ?? '0' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-emoji-frown fs-4 d-block mb-2"></i>
                            Không có khách hàng nào để hiển thị.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
