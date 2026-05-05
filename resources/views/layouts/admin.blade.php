<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin — @yield('title', 'Dashboard') | Perpustakaan SMKN 2 Pekalongan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/perpustakaan.css') }}" />
    @stack('styles')
</head>
<body class="{{ session('dark_mode') ? 'dark' : '' }}">

<div class="admin-layout">

    {{-- ── ADMIN SIDEBAR ── --}}
    <aside class="admin-sidebar">
        <div class="a-logo">
            <div class="logo-mark">P</div>
            <span>Admin Panel</span>
        </div>

        @php
        $adminNav = [
            ['route'=> 'home',                 'label' => 'Lihat Website', 'icon' => '⌂'],
            ['route' => 'admin.dashboard',         'label' => 'Dashboard',    'icon' => '◈'],
            ['route' => 'admin.buku.index',         'label' => 'Kelola Buku',  'icon' => '◫'],
            ['route' => 'admin.peminjaman.index',   'label' => 'Peminjaman',   'icon' => '⇄'],
            ['route' => 'admin.program-keahlian.index', 'label' => 'Program Keahlian', 'icon' => '🎓'],
            ['route' => 'admin.kelas.index',            'label' => 'Kelas',           'icon' => '🏫'],
            ['route' => 'admin.anggota.index',      'label' => 'Anggota',      'icon' => '👥'],
        ];
        @endphp

        @foreach($adminNav as $item)
        <a href="{{ route($item['route']) }}"
           class="admin-nav-btn {{ request()->routeIs($item['route']) ? 'on' : '' }}">
            <span class="admin-nav-icon">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach

        <div style="margin-top:auto;padding-top:16px;border-top:1px solid rgba(255,255,255,.1);display:flex;flex-direction:column;gap:8px;">
            <a href="{{ route('home') }}" class="admin-nav-btn">
                <span class="admin-nav-icon">⌂</span> Lihat Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%;justify-content:center;">
                    ⏻ Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── ADMIN MAIN ── --}}
    <div class="admin-main">
        <div class="admin-topbar">
            <h2>@yield('title', 'Dashboard')</h2>
            <span style="font-size:13px;color:var(--text2);">
                👤 {{ auth()->user()->nama }}
                <span style="background:var(--blue-lt);color:var(--blue);padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;margin-left:6px;">
                    {{ strtoupper(auth()->user()->role) }}
                </span>
            </span>
            <form method="POST" action="{{ route('toggle.dark') }}" style="margin:0;margin-left:10px;">
                @csrf
                <button type="submit" class="dark-btn">{{ session('dark_mode') ? '☀' : '☾' }}</button>
            </form>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="toast toast-ok" id="flash-toast" style="position:fixed;top:20px;right:20px;bottom:auto;">
            ✓ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="toast toast-warn" id="flash-toast" style="position:fixed;top:20px;right:20px;bottom:auto;">
            ✕ {{ session('error') }}
        </div>
        @endif

        <div class="admin-content">
            @yield('content')
        </div>
    </div>

</div>

<script>
const toast = document.getElementById('flash-toast');
if (toast) setTimeout(() => toast.remove(), 4000);
</script>
@stack('scripts')
</body>
</html>
