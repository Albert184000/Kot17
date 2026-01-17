<!DOCTYPE html>
<html lang="km">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>ចូលប្រើប្រាស់ - កុដិ១៧</title>

  <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
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
      min-height:100svh;
      margin:0;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px 12px;
    }

    .login-container{
      width:min(92%, 440px);
      text-align:center;
    }

    .card{
      border-radius:24px;
      border:1px solid rgba(255,255,255,0.22);
      background:rgba(255,255,255,0.93);
      backdrop-filter:blur(10px);
      -webkit-backdrop-filter:blur(10px);
      box-shadow:0 20px 40px rgba(0,0,0,0.45);
      overflow:hidden;
      text-align:left;
    }

    .card-header{
      position:relative;
      color:#fff;
      text-align:center;
      padding:34px 20px 26px;
      border:none;
      overflow:hidden;
      background: linear-gradient(
        to bottom,
        #1f3fa3 0%, #1f3fa3 33.33%,
        #f4d41c 33.33%, #f4d41c 66.66%,
        #c62828 66.66%, #c62828 100%
      );
    }

    .card-header > *{ position:relative; z-index:2; }

    .card-header::before{
      content:"";
      position:absolute;
      inset:-25%;
      z-index:1;
      background:
        radial-gradient(120% 80% at 15% 25%, rgba(255,255,255,.35), transparent 55%),
        radial-gradient(120% 80% at 85% 60%, rgba(0,0,0,.28), transparent 60%),
        radial-gradient(110% 70% at 35% 95%, rgba(255,255,255,.22), transparent 60%);
      opacity:.65;
      mix-blend-mode:soft-light;
      filter:blur(0.4px);
      transform:rotate(-2deg);
      pointer-events:none;
    }

    .card-header::after{
      content:"";
      position:absolute;
      inset:-25%;
      z-index:1;
      background: repeating-linear-gradient(
        -12deg,
        rgba(255,255,255,.18) 0 14px,
        rgba(0,0,0,.10) 14px 28px
      );
      opacity:.18;
      mix-blend-mode:soft-light;
      filter:blur(0.7px);
      pointer-events:none;
    }

    .logo-wrap{
      width:92px;height:92px;border-radius:22px;
      background:rgba(255,255,255,0.97);
      display:grid;place-items:center;
      margin:0 auto 12px;
      box-shadow:0 14px 35px rgba(0,0,0,0.25);
    }
    .logo-img{ width:74px;height:74px;object-fit:contain; filter:drop-shadow(0 4px 8px rgba(0,0,0,0.25)); }

    .card-title{
      font-size:1.65rem;
      font-weight:800;
      margin:0;
      letter-spacing:.5px;
    }
    .card-sub{
      margin:6px 0 0;
      font-size:.82rem;
      opacity:.85;
      line-height:1.35;
    }

    .card-body{ padding:22px; }
    @media (min-width:768px){ .card-body{ padding:28px 34px; } }

    .form-label{ font-weight:700; font-size:.9rem; color:#6b7280; }
    .input-group-text{
      background:#f8f9fa;
      color:#dc3545;
      border-right:none;
      border-radius:12px 0 0 12px !important;
      width:44px;
      justify-content:center;
    }
    .form-control{
      border-radius:0 12px 12px 0 !important;
      padding:12px 14px;
      border:1px solid #e5e7eb;
      font-size:.95rem;
      transition:all .2s ease;
    }
    .form-control:focus{
      border-color:#dc3545;
      box-shadow:0 0 0 4px rgba(220,53,69,0.18);
    }

    .btn-login{
      background:linear-gradient(45deg,#dc3545,#ff6b00);
      border:none;
      border-radius:14px;
      padding:13px 14px;
      font-weight:800;
      letter-spacing:.5px;
      color:#fff;
      box-shadow:0 10px 22px rgba(220,53,69,0.22);
      transition:transform .2s ease, box-shadow .2s ease;
    }
    .btn-login:hover{
      transform:translateY(-1px);
      box-shadow:0 14px 28px rgba(220,53,69,0.32);
      color:#fff;
    }

    .link-soft{
      display:inline-flex;
      gap:6px;
      align-items:center;
      justify-content:center;
      font-weight:700;
      font-size:.9rem;
      color:#6b7280;
      text-decoration:none;
    }
    .link-soft:hover{ color:#dc3545; text-decoration:underline; }

    .footer-text{
      margin-top:14px;
      font-size:.85rem;
      color:rgba(255,255,255,0.82);
      text-align:center;
      font-weight:300;
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
        <h3 class="card-title">កុដិ ១៧</h3>
        <p class="card-sub">ប្រព័ន្ធគ្រប់គ្រងកុដិលេខ ១៧ វត្តបទុមវតីរាជវរារាម</p>
      </div>

      <div class="card-body">
        {{-- ✅ show messages --}}
        @if(session('success'))
          <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger rounded-3 mb-3">
            <ul class="mb-0 small">
              @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label">អ៊ីមែល ឬ ឈ្មោះអ្នកប្រើ</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">លេខសម្ងាត់</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
          </div>

          <button type="submit" class="btn btn-login w-100 mb-3">
            ចូលប្រើប្រាស់ <i class="bi bi-arrow-right ms-2"></i>
          </button>

          <div class="text-center">
            <a href="{{ route('password.request') }}" class="link-soft">
              ភ្លេចលេខសម្ងាត់? <i class="bi bi-question-circle"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <p class="footer-text">រក្សាសិទ្ធិដោយ កុដិ ១៧ © ២០២៦</p>
  </div>
</body>
</html>
