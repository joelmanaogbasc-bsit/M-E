<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | M&E Rental System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            color: #333;
        }

        header {
            background: #243b64;
            color: white;
            padding: 20px 7%;
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
            gap: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 5px;
        }

        nav a:hover {
            background: #3b5685;
        }

        .logout-form {
            margin: 0;
        }

        main {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            background: #243b64;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            margin: 5px 5px 5px 0;
        }

        .button:hover {
            background: #3b5685;
        }

        .logout {
            background: #a52a2a;
        }

        .logout:hover {
            background: #821f1f;
        }

        @media (max-width: 600px) {
            header {
                padding: 18px 5%;
            }

            nav {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System</h2>

    <nav>
    @unless(request()->is('/'))
        <a href="{{ url('/') }}">Home</a>
    @endunless

    <a href="{{ url('/packages') }}">View Packages</a>
    <a href="{{ url('/booking') }}">Book Now</a>
    <a href="{{ url('/my-bookings') }}">My Bookings</a>
    <a href="{{ url('/account/settings') }}">Account Settings</a>

    <form action="{{ url('/logout') }}" method="POST" class="logout-form">
        @csrf
        <button type="submit" class="button logout">Logout</button>
    </form>
</nav>

</header>

<main>
    <div class="card">

        <h1>Welcome, {{ $user->name }}!</h1>

        <p>You have successfully logged in to your account.</p>

        <p>
            <strong>Email:</strong>
            {{ $user->email }}
        </p>

        <hr>

        <h3>What would you like to do?</h3>

        <a href="{{ url('/packages') }}" class="button">
            View Packages
        </a>

        <a href="{{ url('/booking') }}" class="button">
            Book Now
        </a>

        <a href="{{ url('/my-bookings') }}" class="button">
            My Bookings
        </a>

        <a href="{{ url('/account/settings') }}" class="button">
            Account Settings
        </a>

    </div>
</main>

</body>
</html>
