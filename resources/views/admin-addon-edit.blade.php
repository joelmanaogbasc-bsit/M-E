
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Add-on | M&E Rental</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 25px;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button, .back {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 17px;
            background: #16834a;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .back {
            background: #555;
        }

        .errors {
            color: #b42318;
            background: #fde8e7;
            padding: 12px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Add-on</h1>
    <p>Update the equipment or additional service details.</p>

    @if ($errors->any())
        <div class="errors">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="/admin/addons/{{ $addon->id }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Add-on Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $addon->name) }}"
            required
        >

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
        >{{ old('description', $addon->description) }}</textarea>

        <label for="price">Rental Price (₱)</label>
        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $addon->price) }}"
            min="0"
            step="0.01"
            required
        >

        <label for="is_available">Availability</label>
        <select id="is_available" name="is_available" required>
            <option value="1"
                {{ (string) old('is_available', (int) $addon->is_available) === '1' ? 'selected' : '' }}>
                Available
            </option>

            <option value="0"
                {{ (string) old('is_available', (int) $addon->is_available) === '0' ? 'selected' : '' }}>
                Unavailable
            </option>
        </select>

        <button type="submit">Update Add-on</button>

        <a href="/admin/addons" class="back">Cancel</a>
    </form>

</div>

</body>
</html>
