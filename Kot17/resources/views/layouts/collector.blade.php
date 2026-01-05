<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Collector') - Kot17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Battambang', 'Khmer OS', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-stat {
            border-radius: 15px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .member-card {
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .member-card:active {
            transform: scale(0.98);
        }
        .member-card.paid {
            background-color: #d4edda;
            border-left: 5px solid #28a745;
        }
        .member-card.pending {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
        }
        .btn-collect {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: bold;
        }
        .badge-paid {
            background-color: #28a745;
            padding: 8px 15px;
            border-radius: 15px;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
            padding: 8px 15px;
            border-radius: 15px;
        }
        @media (max-width: 768px) {
            .card-stat {
                margin-bottom: 15px;
            }
            .btn-collect {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('collector.dashboard') }}">
                <i class="bi bi-building"></i> Kot17
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('collector.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> ទំព័រដើម
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('collector.collections.daily') }}">
                            <i class="bi bi-cash-coin"></i> ប្រមូលប្រាក់
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('collector.collections.history') }}">
                            <i class="bi bi-clock-history"></i> ប្រវត្តិ
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> ចាកចេញ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>
    @stack('scripts')
</body>
</html>