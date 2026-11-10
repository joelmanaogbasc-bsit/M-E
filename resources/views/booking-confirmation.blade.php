<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Booking Confirmation | M&E Rental System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            margin: 0;
            padding: 30px 15px;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1, h2 {
            color: #243b64;
        }

        h2 {
            font-size: 20px;
            margin-top: 28px;
        }

        .success {
            background: #e5f8e9;
            color: #17652a;
            padding: 12px;
            border-radius: 6px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 5px;
            background: #fff0c2;
            color: #795600;
        }

        .payment-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 5px;
            background: #fff0c2;
            color: #795600;
        }

        .button {
            display: inline-block;
            background: #243b64;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }

        .detail {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            overflow-wrap: anywhere;
        }

        .order-box {
            margin-top: 25px;
            padding: 22px;
            background: #f8f9fc;
            border: 1px solid #e3e7ef;
            border-radius: 10px;
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-row span:last-child {
            text-align: right;
            font-weight: bold;
        }

        .section-label {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .addon-name {
            font-weight: bold;
        }

        .addon-description {
            color: #777;
            font-size: 12px;
            margin-top: 4px;
        }

        .subtotal {
            font-weight: bold;
            color: #333;
        }

        .total-payment {
            margin-top: 18px;
            padding: 18px;
            background: #fff2e8;
            border: 1px solid #ffd6bd;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .total-payment span:first-child {
            font-weight: bold;
            color: #333;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #e65c00;
            text-align: right;
        }

        .empty {
            color: #777;
            padding: 12px 0;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .total-amount {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Booking Confirmation</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <p>Your booking has been submitted successfully!</p>

    <h2>Booking Details</h2>

    <div class="detail">
        <strong>Booking ID:</strong> {{ $booking->id }}
    </div>

    <div class="detail">
        <strong>Customer Name:</strong> {{ $booking->customer_name }}
    </div>

    <div class="detail">
        <strong>Email:</strong> {{ $booking->email }}
    </div>

    <div class="detail">
        <strong>Phone:</strong> {{ $booking->phone }}
    </div>

    <div class="detail">
        <strong>Package:</strong> {{ $booking->package }}
    </div>

    <div class="detail">
        <strong>Event Date:</strong>
        {{ $booking->event_date->format('F d, Y') }}
    </div>

    <div class="detail">
        <strong>Event Location:</strong> {{ $booking->event_location }}
    </div>

    <div class="detail">
        <strong>Notes:</strong> {{ $booking->notes ?? 'None' }}
    </div>

    <div class="detail">
        <strong>Booking Status:</strong>
        <span class="status">{{ $booking->status }}</span>
    </div>

    <div class="detail">
        <strong>Payment Method:</strong>
        {{ $booking->payment_method ?? 'Not selected' }}
    </div>

    <div class="detail">
        <strong>Payment Status:</strong>
        <span class="payment-status">
            {{ $booking->payment_status ?? 'Pending' }}
        </span>
    </div>

    <h2>Order Summary</h2>

    <div class="order-box">

        <div class="section-label">PACKAGE</div>

        <div class="order-row">
            <span>{{ $booking->package }}</span>
            <span>
                ₱{{ number_format((float) $booking->package_price, 2) }}
            </span>
        </div>

        <div class="section-label" style="margin-top: 20px;">
            ADD-ONS & EQUIPMENT
        </div>

        @php
            $addonTotal = 0;
        @endphp

        @forelse ($booking->addons as $addon)

            @php
                $quantity = $addon->pivot->quantity;
                $price = (float) $addon->pivot->price;
                $subtotal = $price * $quantity;
                $addonTotal += $subtotal;
            @endphp

            <div class="order-row">
                <div>
                    <div class="addon-name">
                        {{ $addon->pivot->addon_name }}
                    </div>

                    <div class="addon-description">
                        ₱{{ number_format($price, 2) }}
                        × {{ $quantity }}
                    </div>
                </div>

                <span>
                    ₱{{ number_format($subtotal, 2) }}
                </span>
            </div>

        @empty

            <p class="empty">No add-ons selected.</p>

        @endforelse

        <div class="order-row subtotal">
            <span>Add-ons Subtotal</span>
            <span>₱{{ number_format($addonTotal, 2) }}</span>
        </div>

        <div class="total-payment">
            <span>TOTAL PAYMENT</span>

            <span class="total-amount">
                ₱{{ number_format((float) $booking->total_amount, 2) }}
            </span>
        </div>

        <p style="font-size: 12px; color: #777; margin-top: 12px;">
            Final rental amount based on the selected package and add-ons.
        </p>

    </div>

    <a href="{{ url('/dashboard') }}" class="button">
        Back to Dashboard
    </a>

</div>

</body>
</html>
