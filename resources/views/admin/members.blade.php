@extends('layouts.admin')

@section('title', 'Danh sách thành viên nhóm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Danh sách thành viên nhóm</h2>

    <table class="table table-bordered table-hover align-middle shadow-sm">
        <thead class="table-primary">
            <tr class="text-center">
                <th>#</th>
                <th>Họ và Tên</th>
                <th>MSSV</th>
                <th>Mức độ đóng góp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $member['name'] }}</td>
                    <td class="text-center">{{ $member['mssv'] }}</td>
                    <td class="text-center">{{ $member['contribution'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
