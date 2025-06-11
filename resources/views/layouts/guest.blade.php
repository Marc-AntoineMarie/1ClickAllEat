<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS (CDN) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome pour icônes -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
            body {
                min-height: 100vh;
                background: linear-gradient(120deg, #fff 0%, #f8d7da 100%);
                font-family: 'Figtree', 'Segoe UI', Arial, sans-serif;
            }
            .auth-logo {
                font-size: 2rem;
                font-weight: 700;
                letter-spacing: 1px;
                color: #dc3545;
                font-family: 'Figtree', 'Segoe UI', Arial, sans-serif;
                margin-bottom: 0.5rem;
                text-shadow: none;
            }
            .auth-card {
                background: rgba(255,255,255,0.75);
                border-radius: 1rem;
                box-shadow: 0 8px 32px 0 rgba(220,53,69,0.10), 0 1.5px 12px 0 rgba(0,0,0,0.02);
                padding: 2rem 1.5rem 1.5rem 1.5rem;
                width: 100%;
                max-width: 370px;
                backdrop-filter: blur(7px);
                border: 1.5px solid rgba(220,53,69,0.12);
                margin-bottom: 1.2rem;
                animation: fadeInCard 0.9s cubic-bezier(.4,0,.2,1);
            }
            @keyframes fadeInCard {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .auth-card label,
            .auth-card .form-label,
            .auth-card .block {
                color: #b71c1c;
                font-weight: 500;
                font-size: 1rem;
            }
            .auth-card input[type="text"],
            .auth-card input[type="email"],
            .auth-card input[type="password"] {
                border-radius: 0.7rem;
                border: 1.5px solid #f8d7da;
                background: rgba(255,255,255,0.85);
                font-size: 1.07rem;
                padding: 0.7rem 1rem;
                transition: box-shadow 0.2s, border-color 0.2s;
            }
            .auth-card input:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 2px #dc354540;
                background: #fff;
            }
            .auth-card .form-check-input:checked {
                background-color: #dc3545;
                border-color: #dc3545;
            }
            .auth-card .form-check-input {
                box-shadow: none;
            }
            .auth-card .form-check-label {
                color: #b71c1c;
                font-weight: 500;
            }
            .auth-card .btn-primary, .auth-card .btn-danger, .auth-card .btn-main {
                background-color: #dc3545;
                border-color: #dc3545;
                border-radius: 2rem;
                padding: 0.7rem 2.2rem;
                font-size: 1.13rem;
                font-weight: 600;
                letter-spacing: 1px;
                box-shadow: 0 2px 8px 0 #dc354520;
                transition: background 0.18s, box-shadow 0.18s;
            }
            .auth-card .btn-primary:hover, .auth-card .btn-danger:hover, .auth-card .btn-main:hover {
                background-color: #c82333;
                border-color: #bd2130;
                box-shadow: 0 4px 16px 0 #dc354530;
            }
            .footer-auth {
                margin-top: 2.2rem;
                padding: 1.2rem 0 0.5rem 0;
                background: transparent;
                text-align: center;
                color: #888;
                font-size: 1rem;
            }
            @media (max-width: 600px) {
                .auth-card {
                    padding: 1.2rem 0.5rem 1rem 0.5rem;
                    max-width: 98vw;
                }
                .auth-logo {
                    font-size: 1.4rem;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

            <div class="mx-auto mt-5">
                {{ $slot }}
            </div>
            <footer class="footer-auth">
                <div class="container">
                    <div>
                        &copy; {{ date('Y') }} 1ClickAllEat. Tous droits réservés.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
