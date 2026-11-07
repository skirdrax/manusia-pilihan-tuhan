<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Obat Herbal Kelompok 3') | Kelompok 3</title>
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
                <span class="brand-mark">🌿</span>
                <span><strong>Obat Herbal</strong><small>KELOMPOK 3</small></span>
            </a>
            
            <div class="nav-label">OPERASIONAL HERBAL</div>
            <nav class="main-nav" aria-label="Navigasi operasional">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon">◧</span> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
                    <span class="nav-icon">🌿</span> Data Obat Herbal
                </a>
                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                    <span class="nav-icon">↗</span> Kasir & Penjualan
                </a>
            </nav>

            <div class="nav-label" style="margin-top: 24px;">LOGISTIK & DISTRIBUSI</div>
            <nav class="main-nav" aria-label="Navigasi logistik">
                <a class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}" href="{{ route('paket.index') }}">
                    <span class="nav-icon">📦</span> Manajemen Paket
                </a>
                <a class="nav-link {{ request()->routeIs('gis.*') ? 'active' : '' }}" href="{{ route('gis.index') }}">
                    <span class="nav-icon">🗺</span> Peta GIS Pengiriman
                </a>
            </nav>

            <div class="sidebar-bottom" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="status-dot"></span>
                    <span>Database kel3 aktif</span>
                </div>
                @auth
                    <div style="padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: #94a3b8;">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #f87171; font-size: 0.75rem; cursor: pointer; text-decoration: underline;">Keluar</button>
                        </form>
                    </div>
                @endauth
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <div class="breadcrumb">Obat Herbal <span>/</span> @yield('section', 'Ringkasan')</div>
                <div class="topbar-meta"><span class="today-mark"></span>{{ now()->translatedFormat('l, d F Y') }}</div>
            </header>
            <section class="page-content">
                @if (session('success'))
                    <div class="alert alert-success" role="status" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 6px; background: #e8f3ed; color: #176b53; border: 1px solid #c2e2d3; font-weight: 500;">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error" role="alert" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 6px; background: #fbefe8; color: #bc693f; border: 1px solid #f6d8c8; font-weight: 500;">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error" role="alert" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 6px; background: #fbefe8; color: #bc693f; border: 1px solid #f6d8c8;">
                        <strong>Periksa kembali data yang dimasukkan:</strong>
                        <ul style="margin: 6px 0 0 18px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
