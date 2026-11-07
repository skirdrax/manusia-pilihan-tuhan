<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apotek Sembuh') | Apotek Kelompok 13</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/apotek.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">+</span>
                <span><strong>sembuh</strong><small>APOTEK & KLINIK</small></span>
            </a>
            <div class="nav-label">OPERASIONAL</div>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon">◧</span> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
                    <span class="nav-icon">▦</span> Data obat
                </a>
                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                    <span class="nav-icon">↗</span> Penjualan
                </a>
            </nav>
            <div class="sidebar-bottom">
                <span class="status-dot"></span>
                <span>Database apotek aktif</span>
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <div class="breadcrumb">Apotek <span>/</span> @yield('section', 'Ringkasan')</div>
                <div class="topbar-meta"><span class="today-mark"></span>{{ now()->translatedFormat('l, d F Y') }}</div>
            </header>
            <section class="page-content">
                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error" role="alert">
                        <strong>Periksa kembali data yang dimasukkan.</strong>
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </section>
        </main>
    </div>
    <script>
        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm(form.dataset.confirm)) event.preventDefault();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>