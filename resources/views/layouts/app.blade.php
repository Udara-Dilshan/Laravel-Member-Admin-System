<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Member & Admin Management') }} - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #0f172a;
            --brand-2: #1d4ed8;
            --surface: #ffffff;
            --page: #f4f7fb;
        }

        body {
            background: radial-gradient(circle at top, #eef4ff 0%, var(--page) 44%, #eef2f8 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, var(--brand), #111827) !important;
        }

        .page-card,
        .stat-card,
        .table-card,
        .form-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .page-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .btn-soft {
            background: rgba(29, 78, 216, 0.1);
            color: var(--brand-2);
            border: 0;
        }

        .btn-soft:hover {
            background: rgba(29, 78, 216, 0.18);
            color: var(--brand-2);
        }

        .nav-link.active {
            font-weight: 600;
        }

        .avatar-pill {
            background: rgba(255,255,255,.12);
            border-radius: 999px;
            padding: .45rem .8rem;
        }

        .table thead th {
            white-space: nowrap;
        }

        .image-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: .85rem;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .image-thumb:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }

        .preview-box {
            width: 100%;
            height: 170px;
            border: 2px dashed #d1d5db;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8fafc;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">
            <i class="bi bi-shield-lock me-1"></i>
            Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('members.*')) active @endif" href="{{ route('members.index') }}">Members</a>
                </li>
                @if(auth()->user()?->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admins.*') || request()->routeIs('otp.*')) active @endif" href="{{ route('admins.index') }}">Admins</a>
                    </li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-2 text-white">
                <span class="avatar-pill small">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="ms-1">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid px-3 px-lg-4 py-4">
    @include('partials.alerts')
    @yield('content')
</main>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-dark text-white">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-dark">
                <img id="imagePreviewModalImage" src="" alt="Preview" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('click', function (event) {
        const previewTrigger = event.target.closest('[data-image-preview]');
        if (previewTrigger) {
            const src = previewTrigger.getAttribute('data-image-preview');
            const image = document.getElementById('imagePreviewModalImage');
            image.src = src;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('imagePreviewModal')).show();
        }
    });
</script>
@stack('scripts')
</body>
</html>