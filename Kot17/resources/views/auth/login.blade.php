<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចូលប្រើប្រាស់ - កុដិ១៧</title>

    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            background:
                linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)),
                url("{{ asset('assets/images/unnamed.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .login-container {
            max-width: 420px;
            width: 92%;
        }

        /* Glassmorphism Card */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.45);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .card-header {
            background: linear-gradient(45deg, #ff6b00, #ff8c33);
            color: white;
            text-align: center;
            padding: 34px 20px 28px;
            border: none;
        }

        /* Logo */
        .logo-wrap {
            width: 96px;
            height: 96px;
            border-radius: 22px;
            background: rgb(255, 255, 255);
            display: grid;
            place-items: center;
            margin: 0 auto 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .logo-img {
            width: 78px;
            height: 78px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.25));
        }

        .card-header h3 {
            font-size: 1.75rem;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            color: #ff6b00;
            border-right: none;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.15);
            background-color: #fff;
        }

        .btn-login {
            background: linear-gradient(45deg, #ff6b00, #ff8c33);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.30);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #e66000, #ff6b00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.40);
            color: white;
        }

        .footer-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.82);
            text-align: center;
            margin-top: 18px;
            font-weight: 300;
        }

        /* Error styling */
        .alert-danger {
            border-radius: 12px;
            border: none;
            background-color: rgba(220, 53, 69, 0.10);
            color: #dc3545;
        }

        /* Link hover (you had class hover-orange but no style) */
        .hover-orange:hover {
            color: #ff6b00 !important;
            text-decoration: underline !important;
        }

        /* Small tweak for better spacing on very small screens */
        @media (max-width: 360px) {
            .card-header { padding: 28px 16px 22px; }
            .logo-wrap { width: 86px; height: 86px; border-radius: 20px; }
            .logo-img { width: 70px; height: 70px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <div class="logo-wrap">
                <img
                    src="{{ asset('assets/images/logo_kot17.png') }}"
                    alt="Logo កុដិ ១៧"
                    class="logo-img"
                >
            </div>

            <h3 class="mb-1 fw-bold">កុដិ ១៧</h3>
            <p class="mb-0 opacity-75 small">ប្រព័ន្ធគ្រប់គ្រងរបស់ កុដិលេខ១៧ វត្តបទុមវតីរាជវរារាម</p>
        </div>

        <div class="card-body p-4 p-md-5">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-muted">អ៊ីមែល ឬ ឈ្មោះអ្នកប្រើ</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>

                        <!-- ✅ changed from type="email" to type="text" so username works -->
                        <input
                            type="text"
                            name="email"
                            class="form-control"
                            placeholder="បញ្ចូលអ៊ីមែល ឬ ឈ្មោះអ្នកប្រើ"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">លេខសម្ងាត់</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 mb-4">
                    ចូលប្រើប្រាស់ <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <a href="#" class="text-decoration-none text-muted small hover-orange">ភ្លេចលេខសម្ងាត់?</a>
                </div>
            </form>
        </div>
    </div>

    <p class="footer-text">រក្សាសិទ្ធិដោយ កុដិ១៧ © ២០២៦</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
