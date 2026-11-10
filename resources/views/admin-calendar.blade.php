
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Calendar | M&E Rental</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .subtitle {
            color: #666;
            margin: 0;
        }

        .back-button,
        .month-button {
            display: inline-block;
            padding: 10px 16px;
            background: #263b80;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .summary {
            background: white;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px #0000000d;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            background: white;
            padding: 18px;
            border-radius: 10px 10px 0 0;
        }

        .calendar-header h2 {
            margin: 0;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            background: white;
            border: 1px solid #ddd;
        }

        .weekday {
            padding: 12px 5px;
            text-align: center;
            font-weight: bold;
            background: #263b80;
            color: white;
        }

        .day {
            min-height: 145px;
            padding: 8px;
            border: 1px solid #eee;
            background: white;
            overflow-wrap: anywhere;
        }

        .day.other-month {
            background: #f5f5f5;
            color: #aaa;
        }

        .day.today {
            background: #fffbea;
        }

        .day-number {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .booking {
            padding: 7px;
            margin-bottom: 6px;
            border-radius: 5px;
            font-size: 12px;
            line-height: 1.5;
            background: #e8edf8;
            border-left: 4px solid #263b80;
        }

        .booking strong {
            display: block;
        }

        .pending {
            border-left-color: #d99b00;
            background: #fff4d6;
        }

        .approved {
            border-left-color: #16834a;
            background: #e0f5e9;
        }

        .rejected {
            border-left-color: #c0392b;
            background: #fde8e7;
        }

        .cancelled {
            border-left-color: #777;
            background: #eeeeee;
        }

        .empty {
            color: #999;
            font-size: 11px;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 18px;
            background: white;
            border: 1px solid #eee;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }

        .legend span {
            font-size: 13px;
        }

        @media (max-width: 700px) {
            body {
                padding: 10px;
            }

            .day {
                min-height: 110px;
                padding: 4px;
            }

            .booking {
                font-size: 10px;
                padding: 4px;
            }

            .weekday {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Booking Calendar</h1>
            <p class="subtitle">View customer bookings by event date.</p>
        </div>

        <a href="/admin/dashboard" class="back-button">
            Back to Dashboard
        </a>
    </div>

    <div class="summary">
        <strong>Total bookings this month:</strong>
        {{ $totalBookings }}
    </div>

    <div class="calendar-header">

        <a href="{{ url('/admin/calendar') }}?month={{ $previousMonth }}"
           class="month-button">
            &laquo; Previous
        </a>

        <h2>{{ $start->format('F Y') }}</h2>

        <a href="{{ url('/admin/calendar') }}?month={{ $nextMonth }}"
           class="month-button">
            Next &raquo;
        </a>

    </div>

    <div class="calendar">

        @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $weekday)
            <div class="weekday">
                {{ $weekday }}
            </div>
        @endforeach

        @foreach ($calendarDays as $calendarDay)

            @php
                $date = $calendarDay['date'];

                $dayClass = '';

                if (!$date->isSameMonth($start)) {
                    $dayClass = 'other-month';
                } elseif ($date->isToday()) {
                    $dayClass = 'today';
                }
            @endphp

            <div class="day {{ $dayClass }}">

                <div class="day-number">
                    {{ $date->format('j') }}
                </div>

                @forelse ($calendarDay['bookings'] as $booking)

                    @php
                        $statusClass = strtolower($booking->status);
                    @endphp

                    <div class="booking {{ $statusClass }}">

                        <strong>
                            Booking #{{ $booking->id }}
                        </strong>

                        {{ $booking->customer_name }}

                        <br>

                        {{ $booking->package }}

                        <br>

                        <strong>
                            {{ $booking->status }}
                        </strong>

                    </div>

                @empty

                    @if ($date->isSameMonth($start))
                        <span class="empty">No bookings</span>
                    @endif

                @endforelse

            </div>

        @endforeach

    </div>

    <div class="legend">
        <span>🟨 Pending</span>
        <span>🟩 Approved</span>
        <span>🟥 Rejected</span>
        <span>⬜ Cancelled</span>
    </div>

</div>

</body>
</html>
