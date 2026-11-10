<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form | M&E Rental System</title>

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

        header a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
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
            text-align: center;
            color: #172554;
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .addon-option {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .addon-option input {
            width: auto;
            margin: 0;
        }

        .addon-option label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .addon-price {
            color: #2563eb;
            font-weight: bold;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-option input {
            width: auto;
            margin: 0;
        }

        .payment-option label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .payment-note {
            margin-top: 12px;
            padding: 12px;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.5;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }

        .message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<header>
    <h2>M&E Rental System</h2>
    <a href="/">Home</a>
    <a href="/packages">Packages</a>
</header>

<div class="container">
    <h1>Rental Booking Form</h1>
    <p>Enter your details and event information.</p>

    @if($errors->any())
        <div class="message">
            Please check your information and try again.
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/booking" method="POST">
        @csrf

        <label for="customer_name">Full Name</label>
        <input
            type="text"
            id="customer_name"
            name="customer_name"
            value="{{ old('customer_name', Auth::user()->name) }}"
            required
            maxlength="150"
        >

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', Auth::user()->email) }}"
            required
            maxlength="255"
        >

        <label for="phone">Contact Number</label>
        <input
            type="tel"
            id="phone"
            name="phone"
            value="{{ old('phone') }}"
            required
            maxlength="30"
        >

        <label for="package">Rental Package</label>
        <select id="package" name="package" required>
            <option value="">-- Choose a Package --</option>

            @foreach($packages as $package)
                <option
                    value="{{ $package->name }}"
                    {{ old('package', $selectedPackage) == $package->name ? 'selected' : '' }}
                >
                    {{ $package->name }} - ₱{{ number_format($package->price, 2) }}
                </option>
            @endforeach
        </select>

        @if($packages->isEmpty())
            <p class="error">
                No packages are currently available. Please check again later.
            </p>
        @endif

        <label>Add-ons & Equipment</label>

        @forelse($addons as $addon)
            <div class="addon-option">
                <input
                    type="checkbox"
                    id="addon_{{ $addon->id }}"
                    name="addons[]"
                    value="{{ $addon->id }}"
                    {{ in_array($addon->id, old('addons', [])) ? 'checked' : '' }}
                >

                <label for="addon_{{ $addon->id }}">
                    {{ $addon->name }}

                    <span class="addon-price">
                        - ₱{{ number_format($addon->price, 2) }}
                    </span>

                    @if($addon->description)
                        <br>
                        <small>{{ $addon->description }}</small>
                    @endif
                </label>
            </div>
        @empty
            <p>No additional equipment is currently available.</p>
        @endforelse

        <label>Payment Method</label>

        <div class="payment-option">
            <input
                type="radio"
                id="cash"
                name="payment_method"
                value="Cash"
                {{ old('payment_method') == 'Cash' ? 'checked' : '' }}
                required
            >
            <label for="cash">Cash</label>
        </div>

        <div class="payment-option">
            <input
                type="radio"
                id="gcash"
                name="payment_method"
                value="GCash"
                {{ old('payment_method') == 'GCash' ? 'checked' : '' }}
                required
            >
            <label for="gcash">GCash</label>
        </div>

        <div class="payment-note">
            Your payment status will initially be Pending.
            The administrator will verify your payment and update its status.
            Selecting GCash does not automatically process a payment.
        </div>

        <label for="event_date">Event Date</label>
        <input
            type="date"
            id="event_date"
            name="event_date"
            value="{{ old('event_date') }}"
            min="{{ date('Y-m-d') }}"
            required
        >

        <label for="event_location">Event Location</label>
        <input
            type="text"
            id="event_location"
            name="event_location"
            value="{{ old('event_location') }}"
            required
            maxlength="255"
        >

        <label for="notes">Additional Details</label>
        <textarea
            id="notes"
            name="notes"
            rows="4"
            placeholder="Enter any additional requests"
        >{{ old('notes') }}</textarea>

        <button type="submit" {{ $packages->isEmpty() ? 'disabled' : '' }}>
            Submit Booking
        </button>
    </form>
</div>

</body>
</html>
