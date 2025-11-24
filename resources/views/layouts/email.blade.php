<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif, Arial, Helvetice;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        /* Container */
        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        /* Header */
        .header {
            background-color: #030712; /* Dark background for header */
            padding: 20px;
            text-align: center;
        }
        
        .header img {
            max-width: 150px;
            height: auto;
        }
        
        /* Content */
        .content {
            padding: 30px;
        }
        
        .content h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }
        
        .content p {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }
        
        /* Button */
        .button {
            display: inline-block;
            background-color: #d9463c; /* A red color that fits the brand */
            color: #ffffff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
        }
        
        /* Footer */
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
        
        .footer p {
            margin: 0;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ config('app.url') }}">
                <img src="{{ asset('images/Logo.png') }}" alt="{{ config('app.name', 'Grit Fitness') }}">
            </a>
        </div>
        
        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
        
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Cipta Dilindungi.</p>
        <p>Grit Fitness Center, Jakarta, Indonesia</p>
    </div>
</body>
</html>
