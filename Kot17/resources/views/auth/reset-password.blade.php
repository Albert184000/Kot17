<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កំណត់លេខសម្ងាត់ថ្មី - កុដិ១៧</title>

    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body{
            font-family:'Kantumruy Pro',sans-serif;
            background:
                linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url("{{ asset('assets/images/unnamed.jpg') }}");
            background-size:cover;background-position:center;background-repeat:no-repeat;
            background-attachment:fixed;min-height:100vh;margin:0;
            display:flex;align-items:center;justify-content:center;padding:20px 0;
        }
        .login-container{max-width:420px;width:92%;}
        .card{
            border-radius:24px;border:1px solid rgba(255,255,255,0.22);
            background:rgba(255,255,255,0.93);
            backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
            box-shadow:0 20px 40px rgba(0,0,0,0.45);
            overflow:hidden;
        }
        .card-header{
            position:relative;color:#fff;text-align:center;
            padding:36px 20px 30px;border:none;overflow:hidden;
            background:linear-gradient(to bottom,
                #1f3fa3 0%, #1f3fa3 33.33%,
                #f4d41c 33.33%, #f4d41c 66.66%,
                #c62828 66.66%, #c62828 100%
            );
        }
        .card-header > *{position:relative;z-index:2;}
        .card-header::before{
            content:"";position:absolute;inset:-25%;z-index:1;
            background:
                radial-gradient(120% 80% at 15% 25%, rgba(255,255,255,.35), transparent 55%),
                radial-gradient(120% 80% at 85% 60%, rgba(0,0,0,.28), transparent 60%),
                radial-gradient(110% 70% at 35% 95%, rgba(255,255,255,.22), transparent 60%);
            opacity:.65;mix-blend-mode:soft-light;filter:blur(0.3px);
            transform:rotate(-2deg);pointer-events:none;
        }
        .card-header::after{
            content:"";position:absolute;inset:-25%;z-index:1;
            background:repeating-linear-gradient(-12deg,
                rgba(255,255,255,.18) 0 14px,
                rgba(0,0,0,.10) 14px 28px
            );
            opacity:.20;mix-blend-mode:soft-light;filter:blur(0.6px);
            pointer-events:none;
        }
        .logo-wrap{
            width:96px;height:96px;border-radius:22px;
            background:rgba(255,255,255,0.95);
            display:grid;place-items:center;margin:0 auto 14px;
            box-shadow:0 14px 35px rgba(0,0,0,0.25);
        }
        .logo-img{width:78px;height:78px;object-fit:contain;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.25));}

        .form-label{font-weight:600;font-size:.9rem;}
        .input-group-text{background:#f8f9fa;color:#dc3545;border-right:none;}
        .form-control{
            border-radius:12px;padding:12px 15px;border:1px solid #e0e0e0;
            font-size:.95rem;transition:all .25s ease;
        }
        .form-control:focus{border-color:#dc3545;box-shadow:0 0 0 4px rgba(220,53,69,0.2);}

        .btn-login{
            background:linear-gradient(45deg,#dc3545,#ff6b00);
            border:none;border-radius:12px;padding:14px;
            font-weight:700;letter-spacing:1px;color:#fff;
            box-shadow:0 6px 20px rgba(220,53,69,0.4);
            transition:all .25s ease;
        }
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 26px rgba(220,53,69,0.55);color:#fff;}

        .footer-text{font-size:.85rem;color:rgba(255,255,255,0.82);text-align:center;margin-top:18px;font-weight:300;}
        .alert-danger{border-radius:12px;background-color:rgba(220,53,69,0.1);border:none;}
        .hover-orange:hover{color:#dc3545 !important;text-decoration:underline;}

        @media (max-width:360px){
            .card-header{padding:28px 16px 22px;}
            .logo-wrap{width:86px;height:86px;}
            .logo-img{width:70px;height:70px;}
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <div class="logo-wrap">
                <img src="{{ asset('assets/images/logo_kot17.png') }}" class="logo-img" alt="Logo">
            </div>

            <h3 class="fw-bold mb-1">កំណត់លេខសម្ងាត់ថ្មី</h3>
            <p class="small opacity-75 mb-0">
                សូមបញ្ចូលលេខសម្ងាត់ថ្មីរបស់អ្នក
            </p>
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

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input
                            type="email"
                            name="email_display"
                            class="form-control"
                            value="{{ $email ?? old('email') }}"
                            disabled
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">លេខសម្ងាត់ថ្មី</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">បញ្ជាក់លេខសម្ងាត់</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button class="btn btn-login w-100 mb-3" type="submit">
                    រក្សាទុកលេខសម្ងាត់ថ្មី <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="small text-muted hover-orange">
                        ត្រឡប់ទៅចូលប្រើប្រាស់
                    </a>
                </div>
            </form>
        </div>
    </div>

    <p class="footer-text">រក្សាសិទ្ធិដោយ កុដិ ១៧ © ២០២៦</p>
</div>

</body>
</html>
