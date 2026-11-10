<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&E Rental System</title>

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

        .logout-form {
            display: inline;
            margin: 0;
        }

        .logout-button {
            background: #dc2626;
            color: white;
            padding: 9px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .hero {
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            background: #facc15;
            color: #172554;
            padding: 13px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .packages {
            padding: 50px 8%;
            text-align: center;
        }

        .package-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .package {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 250px;
            box-shadow: 0 3px 12px #00000012;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #172554;
            color: white;
        }

        @media (max-width: 600px) {
            .hero h1 {
                font-size: 30px;
            }

            header {
                padding: 20px 5%;
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

    <a href="{{ url('/packages') }}">Packages</a>
    <a href="{{ url('/contact') }}">Contact Us</a>

    @auth
        @if (auth()->user()->role === 'admin')
            @unless(request()->is('admin/dashboard'))
                <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            @endunless
        @else
            @unless(request()->is('dashboard'))
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @endunless
        @endif

        <form action="{{ url('/logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    @else
        <a href="{{ url('/login') }}">Login</a>
        <a href="{{ url('/register') }}">Register</a>
    @endauth
</nav>

</header>

<section class="hero">
    <h1>Welcome to M&E Rental System</h1>

    <p>Find the right rental package for your next event.</p>

    <a href="{{ url('/packages') }}" class="button">
        Explore Packages
    </a>
</section>

<section class="packages" id="packages">
    <h2>Our Rental Packages</h2>

    <div class="package-list">
        <div class="package">
            <h3>Basic Setup</h3>
            <p>A simple setup for your event needs.</p>
        </div>

        <div class="package">
            <h3>Class A Setup</h3>
            <p>An upgraded setup for your event.</p>
        </div>

        <div class="package">
            <h3>Class B Setup</h3>
            <p>A premium setup for your event.</p>
        </div>
    </div>
</section>

<footer>
    <p>&copy; {{ date('Y') }} M&E Rental System. All rights reserved.</p>
</footer>

</body>
</html>
