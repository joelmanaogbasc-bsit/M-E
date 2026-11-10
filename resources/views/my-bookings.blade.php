<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | M&E Rental System</title>

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
        }

        header h2 {
            margin: 0;
        }

        .container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .top h1 {
            color: #243b64;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            background: #243b64;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .cancel-button {
            background: #dc3545;
            padding: 8px 12px;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1250px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        th {
            background: #f3f5f9;
            color: #243b64;
        }

        .status,
        .payment-status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 13px;
            white-space: nowrap;
        }

        .status {
            background: #fff0c2;
            color: #795600;
        }

        .payment-status {
            background: #fff0c2;
            color: #795600;
        }

        .payment-status.paid {
            background: #d1e7dd;
            color: #0f5132;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #777;
        }

        .action-form {
            margin: 0;
        }

        .payment-details {
            min-width: 190px;
            line-height: 1.7;
        }

        .payment-details .addon-list {
            color: #666;
            font-size: 13px;
        }

        .payment-total {
            display: block;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            color: #198754;
            font-weight: bold;
            font-size: 16px;
        }

        .no-addons {
            color: #888;
            font-size: 13px;
        }

        .payment-method {
            font-weight: bold;
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System</h2>
</header>

<div class="container">

    <div class="top">
        <div>
            <h1>My Bookings</h1>
            <p>View and monitor your submitted bookings.</p>
        </div>

        <a href="{{ url('/dashboard') }}" class="button">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-box">

        @if ($bookings->isEmpty())

            <div class="empty">
                <h3>No Bookings Yet</h3>
                <p>You have not submitted any bookings.</p>

                <a href="{{ url('/packages') }}" class="button">
                    Browse Packages
                </a>
            </div>

        @else

            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Package</th>
                        <th>Event Date</th>
                        <th>Event Location</th>
                        <th>Payment Summary</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>

                            <td>{{ $booking->package }}</td>

                            <td>
                                {{ $booking->event_date->format('M d, Y') }}
                            </td>

                            <td>{{ $booking->event_location }}</td>

                            <td>
                                <div class="payment-details">

                                    <div>
                                        Package:
                                        ₱{{ number_format($booking->package_price, 2) }}
                                    </div>

                                    @if ($booking->addons->isNotEmpty())

                                        <div class="addon-list">
                                            <strong>Add-ons:</strong>

                                            @foreach ($booking->addons as $addon)
                                                <div>
                                                    {{ $addon->pivot->addon_name }}
                                                    ({{ $addon->pivot->quantity }}x)
                                                    - ₱{{ number_format($addon->pivot->price * $addon->pivot->quantity, 2) }}
                                                </div>
                                            @endforeach
                                        </div>

                                    @else

                                        <div class="no-addons">
                                            No add-ons selected
                                        </div>

                                    @endif

                                    <span class="payment-total">
                                        TOTAL: ₱{{ number_format($booking->total_amount, 2) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <span class="payment-method">
                                    {{ $booking->payment_method ?? 'Not selected' }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $paymentStatus = $booking->payment_status ?? 'Pending';
                                @endphp

                                <span class="payment-status {{ strtolower($paymentStatus) === 'paid' ? 'paid' : '' }}">
                                    {{ $paymentStatus }}
                                </span>
                            </td>

                            <td>
                                <span class="status">
                                    {{ $booking->status }}
                                </span>
                            </td>

                            <td>
                                @if ($booking->status === 'Pending')
                                    <form
                                        action="{{ url('/my-bookings/' . $booking->id . '/cancel') }}"
                                        method="POST"
                                        class="action-form"
                                        onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="button cancel-button"
                                        >
                                            Cancel Booking
                                        </button>
                                    </form>
                                @else
                                    No action available
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif

    </div>

</div>

</body>
</html>
