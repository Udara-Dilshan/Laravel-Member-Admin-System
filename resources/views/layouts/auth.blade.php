<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Member & Admin Management') }} - @yield('title', 'Login')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(59,130,246,.35), transparent 35%),
                radial-gradient(circle at bottom right, rgba(15,23,42,.55), transparent 32%),
                linear-gradient(135deg, #0f172a 0%, #1d4ed8 50%, #0ea5e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-shell {
            width: 100%;
            max-width: 1120px;
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(2, 6, 23, 0.35);
        }

        .auth-copy {
            color: #e2e8f0;
            padding: 3rem;
            background: linear-gradient(155deg, rgba(15,23,42,.8), rgba(29,78,216,.65));
        }

        .auth-card {
            background: #fff;
            padding: 3rem;
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            background: rgba(255,255,255,.14);
            color: #fff;
            font-size: .875rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-shell row g-0">
        <div class="col-lg-6 auth-copy d-flex flex-column justify-content-between">
            <div>
                <div class="auth-badge"><i class="bi bi-shield-lock"></i> Secure access</div>
                <h1 class="display-6 fw-bold mb-3">Member & Admin Management System</h1>
                <p class="lead text-white-50 mb-0">
                    Clean authentication, secure admin approvals, and a responsive interface designed for beginners.
                </p>
            </div>
            <div class="pt-5 text-white-50 small">
                Laravel 9 ready UI
            </div>
        </div>
        <div class="col-lg-6 auth-card">
            @include('partials.alerts')
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>