<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | M&E Rental System</title>

    <style>
        * {
            box-sizing: border-box;
        }

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

        .register-container {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #243b64;
            margin-bottom: 8px;
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
            color: #333;
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
            cursor: pointer;
            font-weight: bold;
        }

        .register-button {
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

        .register-button:hover {
            background: #172846;
        }

        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #243b64;
            text-decoration: none;
            font-weight: bold;
        }

        .message {
            background: #ffe8e8;
            color: #a00000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .success {
            background: #e5f8e9;
            color: #17652a;
        }

        .field-error {
            color: #b00020;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="register-container">

    <h1>Create Account</h1>
    <p class="subtitle">M&E Rental System</p>

    @if ($errors->any())
        <div class="message">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf

        <label for="name">Full Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Enter your full name"
            maxlength="255"
            required
        >

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter your email"
            maxlength="255"
            required
        >

        <label for="password">Password</label>
        <div class="password-wrapper">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="At least 8 characters"
                minlength="8"
                required
            >
            <button
                type="button"
                class="toggle-password"
                onclick="togglePassword('password', this)"
            >Show</button>
        </div>

        <label for="password_confirmation">Confirm Password</label>
        <div class="password-wrapper">
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Re-enter your password"
                minlength="8"
                required
            >
            <button
                type="button"
                class="toggle-password"
                onclick="togglePassword('password_confirmation', this)"
            >Show</button>
        </div>

        <button type="submit" class="register-button">
            Register
        </button>
    </form>

    <div class="links">
        Already have an account?
        <a href="{{ url('/login') }}">Login</a>
        <br><br>
        <a href="{{ url('/') }}">Back to Home</a>
    </div>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'Hide';
        } else {
            input.type = 'password';
            button.textContent = 'Show';
        }
    }
</script>

</body>
</html>