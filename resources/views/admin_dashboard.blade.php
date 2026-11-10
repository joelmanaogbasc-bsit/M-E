<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | M&E Rental System</title>

    <style>
        * { box-sizing: border-box; }

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

        header h2 { margin: 0; }

        .logout button {
            background: #fff;
            color: #243b64;
            border: 0;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        h1 { color: #243b64; }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        .card h3 {
            margin-top: 0;
            color: #666;
            font-size: 16px;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #243b64;
        }

        .money {
            font-size: 25px;
            font-weight: bold;
            color: #198754;
            overflow-wrap: anywhere;
        }

        .approved-money {
            color: #198754;
        }

        .pending-money {
            color: #b77900;
        }

        .card-description {
            color: #888;
            font-size: 12px;
            margin-bottom: 0;
        }

        .button {
            display: inline-block;
            background: #243b64;
            color: white;
            text-decoration: none;
            padding: 13px 18px;
            border-radius: 6px;
            margin-top: 10px;
            margin-right: 8px;
        }

        .button:hover {
            background: #36598f;
        }

        .package-button {
            background: #198754;
        }

        .package-button:hover {
            background: #146c43;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .management-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .management-links a {
            color: #243b64;
            text-decoration: none;
            background: #eef2f8;
            padding: 11px 15px;
            border-radius: 6px;
        }

        .management-links a:hover {
            background: #dce5f2;
        }

        .recent-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-top: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        .recent-section h2 {
            color: #243b64;
            margin-top: 0;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f3f5f9;
            color: #243b64;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .approved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .rejected {
            background: #f8d7da;
            color: #842029;
        }

        .cancelled {
            background: #e2e3e5;
            color: #41464b;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 25px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px auto;
            }

            .welcome, .recent-section {
                padding: 18px;
            }

            .money {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System | Admin</h2>

    <form class="logout" action="{{ url('/logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>

<div class="container">

    <h1>Admin Dashboard</h1>

    <div class="welcome">
        <h2>Welcome, {{ Auth::user()->name }}!</h2>
        <p>Manage customer bookings and monitor your rental system.</p>

        <a href="{{ url('/admin/bookings') }}" class="button">
            Manage Bookings
        </a>

        <a href="{{ url('/admin/packages') }}" class="button package-button">
            Manage Packages
        </a>

        <div class="management-links">
            <a href="{{ url('/admin/calendar') }}">
                Booking Calendar
            </a>

            <a href="{{ url('/admin/addons') }}">
                Manage Add-ons & Equipment
            </a>
        </div>
    </div>

    <!-- BOOKING STATISTICS -->
    <div class="cards">

        <div class="card">
            <h3>Total Bookings</h3>
            <div class="number">{{ $totalBookings }}</div>
        </div>

        <div class="card">
            <h3>Pending Bookings</h3>
            <div class="number">{{ $pendingBookings }}</div>
        </div>

        <div class="card">
            <h3>Approved Bookings</h3>
            <div class="number">{{ $approvedBookings }}</div>
        </div>

        <div class="card">
            <h3>Rejected Bookings</h3>
            <div class="number">{{ $rejectedBookings }}</div>
        </div>

        <div class="card">
            <h3>Cancelled Bookings</h3>
            <div class="number">{{ $cancelledBookings }}</div>
        </div>

    </div>

    <!-- REVENUE AND PAYMENT SUMMARY -->
    <h2 style="color: #243b64;">Payment Summary</h2>

    <div class="cards">

        <div class="card">
            <h3>Total Booking Value</h3>

            <div class="money">
                ₱{{ number_format($totalBookingValue, 2) }}
            </div>

            <p class="card-description">
                All submitted bookings, including cancelled and rejected.
            </p>
        </div>

        <div class="card">
            <h3>Approved Booking Value</h3>

            <div class="money approved-money">
                ₱{{ number_format($approvedBookingValue, 2) }}
            </div>

            <p class="card-description">
                Total value of approved bookings.
            </p>
        </div>

        <div class="card">
            <h3>Pending Booking Value</h3>

            <div class="money pending-money">
                ₱{{ number_format($pendingBookingValue, 2) }}
            </div>

            <p class="card-description">
                Total value awaiting admin approval.
            </p>
        </div>

    </div>

    <!-- RECENT BOOKINGS -->
    <div class="recent-section">

        <h2>Recent Bookings</h2>
        <p>Latest 5 customer bookings in the system.</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Event Date</th>
                        <th>Total Payment</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentBookings as $booking)
                        <tr>
                            <td>
                                {{ $booking->user->name ?? $booking->customer_name }}
                            </td>

                            <td>{{ $booking->package }}</td>

                            <td>
                                {{ $booking->event_date
                                    ? \Carbon\Carbon::parse($booking->event_date)->format('M d, Y')
                                    : 'N/A' }}
                            </td>

                            <td>
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </td>

                            <td>
                                <span class="status {{ strtolower($booking->status) }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                No bookings available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ url('/admin/bookings') }}" class="button">
            View All Bookings
        </a>

    </div>

</div>

</body>
</html>
