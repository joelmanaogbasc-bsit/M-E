<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - M&E Rental System</title>

    <style>
        * {
            box-sizing: border-box;
        }

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        header h2 {
            margin: 0;
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        nav a {
            color: white;
            text-decoration: none;
        }

        nav a:hover {
            color: #facc15;
        }

        .container {
            max-width: 750px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px #00000012;
        }

        h1, h2 {
            color: #172554;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            margin-top: 22px;
            padding: 12px 22px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #2563eb;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #172554;
            color: white;
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System</h2>

    <nav>
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ url('/my-bookings') }}">My Bookings</a>
        <a href="{{ url('/account/settings') }}">Account Settings</a>
    </nav>
</header>

<div class="container">

    <h1>Account Settings</h1>
    <p>Manage your personal information and password.</p>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h2>Personal Information</h2>

        <form action="{{ url('/account/settings') }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', auth()->user()->name) }}"
                required
                maxlength="255"
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', auth()->user()->email) }}"
                required
                maxlength="255"
            >

            <label for="phone">Phone Number</label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone', auth()->user()->phone) }}"
                maxlength="20"
            >

            <button type="submit">Save Profile Changes</button>
        </form>
    </div>

    <div class="card">
        <h2>Change Password</h2>

        <form action="{{ url('/account/password') }}" method="POST">
            @csrf
            @method('PUT')

            <label for="current_password">Current Password</label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                required
            >

            <label for="password">New Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="8"
            >

            <label for="password_confirmation">Confirm New Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                minlength="8"
            >

            <button type="submit">Change Password</button>
        </form>
    </div>

</div>

<footer>
    <p>&copy; {{ date('Y') }} M&E Rental System. All rights reserved.</p>
</footer>

</body>
</html>
