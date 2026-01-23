<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MangUP - Tu tienda de manga y anime')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #FF6B6B;
            --color-secondary: #4ECDC4;
            --color-dark: #2C3E50;
            --color-light: #F8F9FA;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--color-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--color-primary) !important;
        }
        
        .navbar-brand span {
            color: var(--color-secondary);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--color-dark) !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--color-primary) !important;
        }
        
        .btn-cart {
            background-color: var(--color-primary);
            color: white;
            border: none;
            position: relative;
        }
        
        .btn-cart:hover {
            background-color: #ff5252;
            color: white;
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--color-secondary);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
        }
        
        /* Main Content */
        main {
            flex: 1;
        }
        
        /* Footer Styles */
        .footer {
            background-color: var(--color-dark);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: auto;
        }
        
        .footer h5 {
            color: var(--color-primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--color-secondary);
        }
        
        .footer-bottom {
            border-top: 1px solid #495057;
            padding-top: 1rem;
            margin-top: 2rem;
        }
        
        .social-links a {
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        
        .hero h1 {
            font-weight: 700;
            font-size: 3rem;
        }
        
        /* Cards */
        .card-product {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .card-product .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        
        .price {
            color: var(--color-primary);
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        
        .btn-outline-primary {
            color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header / Navbar -->
    @include('partials.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
