
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Add-ons | M&E Rental</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 25px;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #263b80;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back {
            background: #555;
        }

        .edit {
            background: #16834a;
        }

        .delete {
            background: #c0392b;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            background: #e0f5e9;
            color: #176b3b;
        }

        .error {
            background: #fde8e7;
            color: #a52620;
        }

        .table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #263b80;
            color: white;
        }

        .available {
            color: #16834a;
            font-weight: bold;
        }

        .unavailable {
            color: #c0392b;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .actions form {
            margin: 0;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 25px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Manage Add-ons & Equipment</h1>
            <p>Manage additional rental equipment and services.</p>
        </div>

        <div>
            <a href="/admin/dashboard" class="button back">
                Dashboard
            </a>

            <a href="/admin/addons/create" class="button">
                + Add New Add-on
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="message">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="message error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Add-on Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($addons as $addon)
                    <tr>
                        <td>{{ $addon->id }}</td>
                        <td>{{ $addon->name }}</td>
                        <td>{{ $addon->description ?: 'No description' }}</td>
                        <td>₱{{ number_format((float) $addon->price, 2) }}</td>

                        <td>
                            @if ($addon->is_available)
                                <span class="available">Available</span>
                            @else
                                <span class="unavailable">Unavailable</span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a href="/admin/addons/{{ $addon->id }}/edit"
                                   class="button edit">
                                    Edit
                                </a>

                                <form action="/admin/addons/{{ $addon->id }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this add-on?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="button delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">
                            No add-ons have been added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
