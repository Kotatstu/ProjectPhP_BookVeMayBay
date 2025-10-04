<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Users List</h1>

        <!-- Nút quay lại trang home -->
        <a href="/home" class="btn btn-secondary">
            ← Quay lại Home
        </a>
    </div>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Không có người dùng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
