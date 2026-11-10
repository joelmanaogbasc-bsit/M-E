
<!DOCTYPE html>
<html>
<head>
    <title>Package Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        h1 { color: #222; }
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        a, button {
            padding: 9px 14px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }
        .add { background: #198754; }
        .edit { background: #0d6efd; }
        .delete { background: #dc3545; }
        .back { background: #6c757d; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th { background: #f1f1f1; }
        .success, .error {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .success {
            background: #d1e7dd;
            color: #0f5132;
        }
        .error {
            background: #f8d7da;
            color: #842029;
        }
        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        form { margin: 0; }
        .available { color: green; }
        .unavailable { color: red; }
    </style>
</head>
<body>

<div class="container">

    <div class="top">
        <h1>Package Management</h1>
        <div>
            <a href="/admin/dashboard" class="back">Dashboard</a>
            <a href="/admin/packages/create" class="add">+ Add Package</a>
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Package Name</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($packages as $package)
                <tr>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->name }}</td>
                    <td>₱{{ number_format($package->price, 2) }}</td>
                    <td>{{ $package->duration ?? 'Not specified' }}</td>
                    <td>
                        @if($package->is_available)
                            <span class="available">Available</span>
                        @else
                            <span class="unavailable">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="/admin/packages/{{ $package->id }}/edit"
                               class="edit">Edit</a>

                            <form action="/admin/packages/{{ $package->id }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this package?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No packages have been added yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

</body>
</html>
