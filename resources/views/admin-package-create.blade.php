
<!DOCTYPE html>
<html>
<head>
    <title>Add Package</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button, a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            color: white;
            background: #198754;
        }
        a { background: #6c757d; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">

    <h1>Add New Package</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/admin/packages" method="POST">
        @csrf

        <label>Package Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description') }}</textarea>

        <label>Price (₱)</label>
        <input type="number" name="price" step="0.01"
               min="0" value="{{ old('price') }}" required>

        <label>Duration</label>
        <input type="text" name="duration"
               placeholder="Example: 4 Hours"
               value="{{ old('duration') }}">

        <label>Image URL (Optional)</label>
        <input type="url" name="image"
               value="{{ old('image') }}"
               placeholder="https://example.com/image.jpg">

        <label>Availability</label>
        <select name="is_available" required>
            <option value="1" selected>Available</option>
            <option value="0">Unavailable</option>
        </select>

        <button type="submit">Save Package</button>
        <a href="/admin/packages">Cancel</a>
    </form>

</div>

</body>
</html>
