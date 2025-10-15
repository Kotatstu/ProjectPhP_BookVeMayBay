@extends('layouts.admin')

@section('title', 'Danh sách người dùng')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Danh sách người dùng</h2>

    <!-- Table to display users -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y H:i') }}</td>
                        <td>
                            
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
