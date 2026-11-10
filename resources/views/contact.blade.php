<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - M&E Rental System</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        header h2 {
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        nav a:hover {
            color: #facc15;
        }

        .contact-section {
            padding: 60px 20px;
            text-align: center;
        }

        .contact-section h1 {
            color: #172554;
            font-size: 36px;
        }

        .contact-section > p {
            color: #555;
            margin-bottom: 35px;
        }

        .contact-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 12px #00000012;
        }

        .contact-item {
            margin: 25px 0;
        }

        .contact-item h3 {
            color: #1e3a8a;
            margin-bottom: 8px;
        }

        .contact-item p,
        .contact-item a {
            color: #444;
            text-decoration: none;
            font-size: 16px;
        }

        .contact-item a:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #172554;
            color: white;
        }
    </style>
</head>
<body>

    <header>
        <h2>M&E Rental System</h2>

        <nav>
            <a href="/">Home</a>
            <a href="/packages">Packages</a>
            <a href="/contact">Contact Us</a>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </nav>
    </header>

    <section class="contact-section">
        <h1>Contact Us</h1>

        <p>
            Have questions about our rental packages or services?
            Feel free to contact us.
        </p>

        <div class="contact-container">

            <div class="contact-item">
                <h3>Business Name</h3>
                <p>M&E Lights and Sounds</p>
            </div>

            <div class="contact-item">
                <h3>Contact Number</h3>
                <p>
                    <a href="tel:09171234567">
                        0917-123-4567
                    </a>
                </p>
            </div>

            <div class="contact-item">
                <h3>Email Address</h3>
                <p>
                    <a href="mailto:hello@melightsandsound.ph">
                        hello@melightsandsound.ph
                    </a>
                </p>
            </div>

            <div class="contact-item">
                <h3>Business Location</h3>
                <p>Mapaniqui, Candaba, Pampanga</p>
            </div>

        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} M&E Rental System. All rights reserved.</p>
    </footer>

</body>
</html>
