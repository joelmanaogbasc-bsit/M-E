<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | M&E Rental System</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #243b64;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 65px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-25%);
            border: none;
            background: none;
            color: #243b64;
            font-weight: bold;
            cursor: pointer;
        }

        .main-button {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            background: #243b64;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .main-button:hover {
            background: #172846;
        }

        .links {
            text-align: center;
            margin-top: 20px;
            line-height: 2;
        }

        a {
            color: #243b64;
            text-decoration: none;
            font-weight: bold;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error {
            background: #ffe8e8;
            color: #a00000;
        }

        .success {
            background: #e5f8e9;
            color: #17652a;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Welcome Back!</h1>
    <p class="subtitle">M&E Rental System</p>

    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter your email"
            required
        >

        <label for="password">Password</label>

        <div class="password-wrapper">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button
                type="button"
                class="toggle-password"
                onclick="togglePassword()"
            >Show</button>
        </div>

        <button type="submit" class="main-button">
            Login
        </button>
    </form>

    <div class="links">
        Don't have an account?
        <a href="{{ url('/register') }}">Register</a>

        <br>

        <a href="{{ url('/') }}">Back to Home</a>
    </div>

</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const button = document.querySelector('.toggle-password');

        if (password.type === 'password') {
            password.type = 'text';
            button.textContent = 'Hide';
        } else {
            password.type = 'password';
            button.textContent = 'Show';
        }
    }
</script>

</body>
</html>