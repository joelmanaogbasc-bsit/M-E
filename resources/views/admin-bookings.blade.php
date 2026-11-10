<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | M&E Rental System</title>

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
        }

        header h2 { margin: 0; }

        .container {
            max-width: 1800px;
            margin: 35px auto;
            padding: 0 20px;
        }

        h1 { color: #243b64; }

        .button {
            display: inline-block;
            background: #243b64;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            margin-right: 8px;
        }

        .export-button { background: #198754; }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        .filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filters input,
        .filters select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .filters input {
            flex: 1;
            min-width: 220px;
        }

        .filters select {
            min-width: 180px;
            background: white;
        }

        .reset-button {
            background: #6c757d;
            color: white;
            border: 0;
            padding: 12px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1900px;
        }

        th, td {
            padding: 13px;
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
            background: #fff0c2;
            color: #795600;
            font-size: 13px;
        }

        .approved,
        .paid {
            background: #e2f5e8;
            color: #17652a;
        }

        .rejected {
            background: #fde7e7;
            color: #a32222;
        }

        .cancelled {
            background: #e2e3e5;
            color: #41464b;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .actions form { margin: 0; }

        .actions button {
            border: 0;
            padding: 9px 12px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .approve { background: #218838; }
        .reject { background: #c0392b; }

        .message {
            padding: 12px;
            margin-bottom: 18px;
            background: #e5f8e9;
            color: #17652a;
            border-radius: 6px;
        }

        .error-message {
            background: #f8d7da;
            color: #842029;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        .results-count {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .notes-cell {
            max-width: 220px;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .payment-cell {
            min-width: 210px;
            line-height: 1.7;
        }

        .addon-list {
            color: #666;
            font-size: 13px;
        }

        .no-addons {
            color: #888;
            font-size: 13px;
        }

        .total-payment {
            display: block;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            color: #198754;
            font-weight: bold;
            font-size: 16px;
        }

        .payment-verification {
            min-width: 230px;
            line-height: 1.8;
        }

        .payment-form {
            margin-top: 12px;
        }

        .payment-form input {
            width: 100%;
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 8px;
        }

        .paid-button {
            background: #198754;
            color: white;
            border: 0;
            padding: 9px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .paid-date {
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .filters { flex-direction: column; }

            .filters input,
            .filters select,
            .reset-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System | Admin</h2>
</header>

<div class="container">

    <h1>All Customer Bookings</h1>

    <a href="{{ url('/admin/dashboard') }}" class="button">
        Back to Admin Dashboard
    </a>

    <a href="{{ url('/admin/bookings/export') }}" class="button export-button">
        Export Bookings (CSV)
    </a>

    @if (session('success'))
        <div class="message">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="message error-message">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message error-message">
            Please check the form and try again.
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="table-box">

        <div class="filters">
            <input
                type="text"
                id="searchInput"
                placeholder="Search name, email, phone, package, or ID..."
            >

            <select id="statusFilter">
                <option value="">All Booking Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <button type="button" class="reset-button" onclick="resetFilters()">
                Reset Filters
            </button>
        </div>

        <div class="results-count" id="resultsCount">
            Showing {{ $bookings->count() }} bookings
        </div>

        @if ($bookings->isEmpty())

            <div class="empty">
                <h3>No Bookings Found</h3>
                <p>There are currently no customer bookings.</p>
            </div>

        @else

            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Package</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Payment Summary</th>
                        <th>Payment Details</th>
                        <th>Special Requests / Notes</th>
                        <th>Booking Status</th>
                        <th>Booking Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="booking-row"
                            data-status="{{ $booking->status }}">

                            <td>#{{ $booking->id }}</td>

                            <td>{{ $booking->customer_name }}</td>

                            <td>{{ $booking->email }}</td>

                            <td>{{ $booking->phone ?: 'N/A' }}</td>

                            <td>{{ $booking->package }}</td>

                            <td>
                                {{ $booking->event_date
                                    ? $booking->event_date->format('M d, Y')
                                    : 'N/A' }}
                            </td>

                            <td>{{ $booking->event_location }}</td>

                            <td class="payment-cell">

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
                                                - ₱{{ number_format(
                                                    $addon->pivot->price * $addon->pivot->quantity,
                                                    2
                                                ) }}
                                            </div>
                                        @endforeach
                                    </div>

                                @else

                                    <div class="no-addons">
                                        No add-ons selected
                                    </div>

                                @endif

                                <span class="total-payment">
                                    TOTAL: ₱{{ number_format($booking->total_amount, 2) }}
                                </span>

                            </td>

                            <td class="payment-verification">

                                <div>
                                    <strong>Method:</strong>
                                    {{ $booking->payment_method ?? 'Not selected' }}
                                </div>

                                <div>
                                    <strong>Payment:</strong>

                                    @php
                                        $paymentStatus = $booking->payment_status ?? 'Pending';
                                    @endphp

                                    <span class="payment-status {{ strtolower($paymentStatus) === 'paid' ? 'paid' : '' }}">
                                        {{ $paymentStatus }}
                                    </span>
                                </div>

                                @if ($booking->payment_reference)
                                    <div>
                                        <strong>Reference:</strong>
                                        {{ $booking->payment_reference }}
                                    </div>
                                @endif

                                @if ($booking->paid_at)
                                    <div class="paid-date">
                                        Paid on:
                                        {{ $booking->paid_at->format('M d, Y h:i A') }}
                                    </div>
                                @endif

                                @if ($paymentStatus !== 'Paid' && !in_array($booking->status, ['Rejected', 'Cancelled']))

                                    <form
                                        action="{{ url('/admin/bookings/' . $booking->id . '/payment') }}"
                                        method="POST"
                                        class="payment-form"
                                        onsubmit="return confirm('Have you verified the actual payment? Mark this booking as Paid?');"
                                    >
                                        @csrf

                                        <input
                                            type="text"
                                            name="payment_reference"
                                            placeholder="GCash reference (optional)"
                                            maxlength="255"
                                        >

                                        <button type="submit" class="paid-button">
                                            Mark as Paid
                                        </button>
                                    </form>

                                @elseif ($paymentStatus === 'Paid')

                                    <strong>Payment Verified</strong>

                                @else

                                    <span>Not available</span>

                                @endif

                            </td>

                            <td class="notes-cell">
                                {{ $booking->notes ?: 'No special requests' }}
                            </td>

                            <td>
                                <span class="status
                                    {{ $booking->status === 'Approved' ? 'approved' : '' }}
                                    {{ $booking->status === 'Rejected' ? 'rejected' : '' }}
                                    {{ $booking->status === 'Cancelled' ? 'cancelled' : '' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>

                            <td>
                                @if ($booking->status === 'Pending')

                                    <div class="actions">

                                        <form
                                            action="{{ url('/admin/bookings/' . $booking->id . '/status') }}"
                                            method="POST">
                                            @csrf

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="Approved">

                                            <button type="submit" class="approve">
                                                Approve
                                            </button>
                                        </form>

                                        <form
                                            action="{{ url('/admin/bookings/' . $booking->id . '/status') }}"
                                            method="POST">
                                            @csrf

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="Rejected">

                                            <button type="submit" class="reject">
                                                Reject
                                            </button>
                                        </form>

                                    </div>

                                @elseif ($booking->status === 'Cancelled')

                                    <span>Cancelled by Customer</span>

                                @else

                                    <span>Already processed</span>

                                @endif
                            </td>

                        </tr>
                    @endforeach

                    <tr id="noResults" style="display: none;">
                        <td colspan="12" class="empty">
                            No matching bookings found.
                        </td>
                    </tr>
                </tbody>
            </table>

        @endif

    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resultsCount = document.getElementById('resultsCount');

    function filterBookings() {
        const searchValue = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;
        const rows = document.querySelectorAll('.booking-row');

        let visibleCount = 0;

        rows.forEach(function(row) {
            const rowText = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status;

            const matchesSearch = rowText.includes(searchValue);
            const matchesStatus =
                selectedStatus === '' || rowStatus === selectedStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noResults');

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? '' : 'none';
        }

        resultsCount.textContent =
            'Showing ' + visibleCount + ' of ' + rows.length + ' bookings';
    }

    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = '';
        filterBookings();
    }

    searchInput.addEventListener('input', filterBookings);
    statusFilter.addEventListener('change', filterBookings);
</script>

</body>
</html>
