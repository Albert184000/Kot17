<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ភ្លេចលេខសម្ងាត់ - កុដិ១៧</title>

    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body{
            font-family:'Kantumruy Pro',sans-serif;
            background:
                linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url("{{ asset('assets/images/unnamed.jpg') }}");
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            background-attachment:fixed;
            min-height:100vh;
            margin:0;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px 0;
        }
        .login-container{max-width:420px;width:92%;}
        .card{
            border-radius:24px;
            border:1px solid rgba(255,255,255,0.22);
            background:rgba(255,255,255,0.93);
            backdrop-filter:blur(10px);
            -webkit-backdrop-filter:blur(10px);
            box-shadow:0 20px 40px rgba(0,0,0,0.45);
            overflow:hidden;
        }

        /* ✅ HEADER 3 COLORS + WAVES */
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
            background:repeating-linear-gradient(
                -12deg,
                rgba(255,255,255,.18) 0 14px,
                rgba(0,0,0,.10) 14px 28px
            );
            opacity:.20;mix-blend-mode:soft-light;filter:blur(0.6px);
            pointer-events:none;
        }

        .logo-wrap{
            width:96px;height:96px;border-radius:22px;
            background:rgba(255,255,255,0.95);
            display:grid;place-items:center;
            margin:0 auto 14px;
            box-shadow:0 14px 35px rgba(0,0,0,0.25);
        }
        .logo-img{width:78px;height:78px;object-fit:contain;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.25));}

        .form-label{font-weight:600;font-size:.9rem;}
        .input-group-text{background:#f8f9fa;color:#dc3545;border-right:none;}
        .form-control{
            border-radius:12px;padding:12px 15px;
            border:1px solid #e0e0e0;font-size:.95rem;
            transition:all .25s ease;
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
        .alert-danger,.alert-success{border-radius:12px;border:none;}
        .alert-success{background-color:rgba(25,135,84,0.12);color:#198754;}
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

            <h3 class="fw-bold mb-1">ភ្លេចលេខសម្ងាត់</h3>
            <p class="small opacity-75 mb-0">
                បញ្ចូល Email របស់អ្នក ដើម្បីទទួលតំណភ្ជាប់ Reset
            </p>
        </div>

        <div class="card-body p-4 p-md-5">

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label text-muted">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="example@gmail.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                    <div class="form-text small text-muted mt-2">
                        * ប្រសិនបើ Email ត្រឹមត្រូវ ប្រព័ន្ធនឹងផ្ញើ link reset ទៅកាន់ Email នោះ
                    </div>
                </div>

                <button class="btn btn-login w-100 mb-3" type="submit">
                    ផ្ញើតំណភ្ជាប់ Reset <i class="bi bi-arrow-right ms-2"></i>
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
