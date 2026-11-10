
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Packages | M&E Rental System</title>

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
        }

        header a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        main {
            padding: 40px 8%;
            text-align: center;
        }

        .package-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .package {
            background: white;
            width: 280px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px #00000012;
        }

        .package img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border-radius: 8px;
        }

        .package h2 {
            color: #172554;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
        }

        .duration {
            color: #666;
        }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .empty {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

<header>
    <h2>M&E Rental System</h2>
    <a href="/">Home</a>
    <a href="/packages">Packages</a>
</header>

<main>
    <h1>Our Rental Packages</h1>
    <p>Choose a rental package for your event.</p>

    <div class="package-list">

        @forelse($packages as $package)

            <div class="package">

                @if($package->image)
                    <img src="{{ $package->image }}"
                         alt="{{ $package->name }}">
                @else
                    <div style="background:#e5e7eb; padding:65px 10px; border-radius:8px;">
                        No Package Image
                    </div>
                @endif

                <h2>{{ $package->name }}</h2>

                <p>{{ $package->description ?? 'No description available.' }}</p>

                <p class="price">
                    ₱{{ number_format($package->price, 2) }}
                </p>

                <p class="duration">
                    Duration: {{ $package->duration ?? 'Not specified' }}
                </p>

                <a href="{{ url('/booking') }}?package={{ urlencode($package->name) }}"
                   class="button">
                    Book Now
                </a>

            </div>

        @empty

            <div class="empty">
                <h2>No Packages Available</h2>
                <p>Please check again later for available rental packages.</p>
            </div>

        @endforelse

    </div>
</main>

</body>
</html>
