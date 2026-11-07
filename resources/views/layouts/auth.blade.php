<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autentikasi' }} — Apotek Herbal Kelompok 3</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/apotek.css') }}">
    
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            background: #203b33;
            background-image: radial-gradient(circle at 50% 20%, #294c42 0%, #1a3029 100%);
        }
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            padding: 36px 32px;
            border: 1px solid #d4dfd9;
        }
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }
        .auth-brand .brand-mark {
            background: #176b53;
            color: #ffffff;
        }
        .auth-brand strong {
            font-size: 20px;
            color: #176b53;
        }
        .auth-brand small {
            color: #78827d;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #4b5953;
        }
        .form-group input {
            width: 100%;
            border-radius: 6px;
        }
        .auth-footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e7ebe7;
            text-align: center;
            font-size: 12px;
            color: #78827d;
        }
        .auth-footer a {
            color: #176b53;
            font-weight: 700;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-brand">
                <span class="brand-mark">+</span>
                <div>
                    <strong>Apotek Herbal</strong>
                    <small>KELOMPOK 3</small>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" style="margin-bottom: 20px; padding: 10px 14px; font-size: 12px; border-radius: 6px; background: #e8f3ed; color: #176b53; border: 1px solid #c2e2d3;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom: 20px; padding: 10px 14px; font-size: 12px; border-radius: 6px; background: #fbefe8; color: #bc693f; border: 1px solid #f6d8c8;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')

            <div class="auth-footer">
                &copy; {{ date('Y') }} Apotek Herbal Kelompok 3 &mdash; Database <code>kel3</code>
            </div>
        </div>
    </div>
</body>
</html>
