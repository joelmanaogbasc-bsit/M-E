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

        .header-content {
            max-width: 1200px;
            margin: auto;
        }

        header h2 {
            margin: 0 0 15px;
        }

        header nav {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        header a:hover {
            color: #93c5fd;
        }

        main {
            max-width: 1250px;
            margin: auto;
            padding: 45px 25px;
        }

        .page-heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .page-heading h1 {
            color: #172554;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-heading p {
            color: #64748b;
        }

        .package-list {
            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(270px, 1fr)
            );
            gap: 25px;
            align-items: stretch;
        }

        .package {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #00000012;
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
        }

        .package img,
        .no-image {
            width: 100%;
            height: 190px;
            border-radius: 9px;
            object-fit: cover;
        }

        .no-image {
            background: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #64748b;
        }

        .package h2 {
            color: #172554;
            text-align: center;
            font-size: 23px;
            margin: 20px 0;
        }

        /* DESCRIPTION */
        .description-section {
            flex: 1;
            text-align: left;
            margin-bottom: 15px;
        }

        .description-title {
            color: #172554;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .description {
            color: #374151;
            font-size: 14px;
            line-height: 1.8;
            white-space: pre-line;
            overflow-wrap: anywhere;
            text-align: left;

            /* Show only 6 lines initially */
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 6;
            line-clamp: 6;
            overflow: hidden;
        }

        /* EXPANDED DESCRIPTION */
        .description.expanded {
            display: block;
            overflow: visible;
        }

        /* VIEW MORE BUTTON */
        .view-more {
            background: none;
            border: none;
            color: #2563eb;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            padding: 8px 0;
            margin-top: 5px;
        }

        .view-more:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .price {
            font-size: 25px;
            font-weight: bold;
            color: #2563eb;
            text-align: center;
            margin: 15px 0 8px;
        }

        .duration {
            color: #64748b;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .button {
            display: block;
            background: #2563eb;
            color: white;
            padding: 13px 20px;
            border-radius: 7px;
            text-decoration: none;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-top: auto;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .empty {
            background: white;
            padding: 40px 20px;
            border-radius: 12px;
            text-align: center;
            grid-column: 1 / -1;
            box-shadow: 0 3px 12px #00000012;
        }

        .empty h2 {
            color: #172554;
        }

        .empty p {
            color: #64748b;
        }

        @media (max-width: 768px) {
            main {
                padding: 30px 18px;
            }

            .page-heading h1 {
                font-size: 27px;
            }

            .package-list {
                grid-template-columns: repeat(
                    auto-fit,
                    minmax(240px, 1fr)
                );
            }
        }

        @media (max-width: 520px) {
            header {
                padding: 18px 6%;
            }

            header h2 {
                font-size: 21px;
            }

            main {
                padding: 25px 15px;
            }

            .page-heading h1 {
                font-size: 25px;
            }

            .package-list {
                grid-template-columns: 1fr;
            }

            .package img,
            .no-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="header-content">
        <h2>M&E Rental System</h2>

        <nav>
            <a href="/">Home</a>
            <a href="/packages">Packages</a>
        </nav>
    </div>
</header>

<main>

    <div class="page-heading">
        <h1>Our Rental Packages</h1>
        <p>Choose the perfect sound and lighting package for your event.</p>
    </div>

    <div class="package-list">

        @forelse($packages as $package)

            <div class="package">

                {{-- PACKAGE IMAGE --}}
                @if($package->image)
                    <img
                        src="{{ $package->image }}"
                        alt="{{ $package->name }}"
                        loading="lazy"
                    >
                @else
                    <div class="no-image">
                        No Package Image
                    </div>
                @endif

                {{-- PACKAGE NAME --}}
                <h2>{{ $package->name }}</h2>

                {{-- PACKAGE DESCRIPTION --}}
                <div class="description-section">

                    <div class="description-title">
                        Package Inclusions
                    </div>

                    <div class="description">
                        {{ $package->description ?? 'No description available.' }}
                    </div>

                    <button
                        type="button"
                        class="view-more"
                        onclick="toggleDescription(this)"
                        hidden
                    >
                        View More
                    </button>

                </div>

                {{-- PACKAGE PRICE --}}
                <p class="price">
                    ₱{{ number_format($package->price, 2) }}
                </p>

                {{-- PACKAGE DURATION --}}
                <p class="duration">
                    Additional Hour:
                    {{ $package->duration ?? 'Not specified' }}
                </p>

                {{-- BOOKING BUTTON --}}
                <a
                    href="{{ url('/booking') }}?package={{ urlencode($package->name) }}"
                    class="button"
                >
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

<script>
    // Show View More only when description exceeds 6 lines.
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.description-section').forEach(function (section) {

            const description = section.querySelector('.description');
            const button = section.querySelector('.view-more');

            if (description && button) {

                const originalHeight = description.clientHeight;

                description.classList.add('expanded');
                const fullHeight = description.scrollHeight;
                description.classList.remove('expanded');

                if (fullHeight > originalHeight + 2) {
                    button.hidden = false;
                }
            }
        });

    });

    // Expand or collapse an individual package description.
    function toggleDescription(button) {

        const description = button.previousElementSibling;

        description.classList.toggle('expanded');

        if (description.classList.contains('expanded')) {
            button.textContent = 'View Less';
        } else {
            button.textContent = 'View More';
        }
    }
</script>

</body>
</html>
