<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package | M&E Rental System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        header {
            background: #172554;
            color: white;
            padding: 20px 8%;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 12px #00000012;
        }

        h1 {
            color: #172554;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button, .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
        }

        button {
            background: #2563eb;
            width: 100%;
        }

        .back-button {
            background: #64748b;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System - Admin</h2>
</header>

<div class="container">

    <h1>Edit Package</h1>
    <p>Update the package information below.</p>

    @if($errors->any())
        <div class="error">
            <strong>Please check the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/admin/packages/' . $package->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Package Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $package->name) }}"
            required
            maxlength="255"
        >

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
        >{{ old('description', $package->description) }}</textarea>

        <label for="price">Price (₱)</label>
        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $package->price) }}"
            min="0"
            step="0.01"
            required
        >

        <label for="duration">Duration</label>
        <input
            type="text"
            id="duration"
            name="duration"
            value="{{ old('duration', $package->duration) }}"
        >

        <label for="image">Image URL</label>
        <input
            type="url"
            id="image"
            name="image"
            value="{{ old('image', $package->image) }}"
        >

        <label for="is_available">Availability</label>
        <select id="is_available" name="is_available" required>
            <option value="1"
                {{ old('is_available', $package->is_available ? '1' : '0') == '1' ? 'selected' : '' }}>
                Available
            </option>

            <option value="0"
                {{ old('is_available', $package->is_available ? '1' : '0') == '0' ? 'selected' : '' }}>
                Unavailable
            </option>
        </select>

        <button type="submit">Update Package</button>
    </form>

    <a href="{{ url('/admin/packages') }}" class="back-button">
        Back to Packages
    </a>

</div>

</body>
</html>