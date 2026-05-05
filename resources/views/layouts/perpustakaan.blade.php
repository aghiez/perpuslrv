<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Perpustakaan') — SMKN 2 Pekalongan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- CSS (salin dari prototype) --}}
    <link rel="stylesheet" href="{{ asset('css/perpustakaan.css') }}" />

    @stack('styles')
</head>
<body class="{{ session('dark_mode') ? 'dark' : '' }}">

<div id="app-shell" style="display:flex;height:100vh;overflow:hidden;">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar {{ session('sidebar_slim') ? 'slim' : '' }}" id="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <div class="logo-mark">P</div>
                <div class="logo-text">
                    <b>Perpustakaan</b>
                    <span>SMKN 2 Pekalongan</span>
                </div>
            </div>
        </div>

        <nav class="nav">
            @php
            $navItems = [
                ['route' => 'home',         'label' => 'Beranda',      'icon' => '⌂'],
                ['route' => 'katalog',       'label' => 'Katalog Buku', 'icon' => '◫'],
                ['route' => 'cari',          'label' => 'Pencarian',    'icon' => '⌕'],
                ['route' => 'peminjaman.index','label'=> 'Peminjaman',  'icon' => '⇄'],
                ['route' => 'berita.index',  'label' => 'Berita',       'icon' => '◈'],
                ['route' => 'tentang',       'label' => 'Tentang',      'icon' => '◉'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-btn {{ request()->routeIs($item['route']) ? 'on' : '' }}"
               title="{{ $item['label'] }}">
                <span class="nav-icon">{{ $item['icon'] }}</span>
                <span class="nav-lbl">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="sb-footer">
            @auth
                <a href="{{ route('peminjaman.index') }}" class="sb-login">
                    👤 {{ auth()->user()->nama }}
                </a>
            @else
                <a href="{{ route('login') }}" class="sb-login">Masuk / Daftar</a>
            @endauth
        </div>
    </aside>

    {{-- ── MAIN AREA ── --}}
    <div class="main-area">

        {{-- TOPBAR --}}
        <header class="topbar">
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <span class="topbar-title">@yield('page-title', 'Perpustakaan')</span>

            <form action="{{ route('cari') }}" method="GET" class="topbar-search">
                <span>⌕</span>
                <input type="text" name="q" placeholder="Cari buku…"
                       value="{{ request('q') }}" />
                <button type="submit">→</button>
            </form>

            <div class="topbar-right">
                {{-- Dark mode toggle --}}
                <form method="POST" action="{{ route('toggle.dark') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="dark-btn" title="Toggle dark mode">
                        {{ session('dark_mode') ? '☀' : '☾' }}
                    </button>
                </form>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-admin-pill">
                            ⚙ Admin
                        </a>
                    @endif
                    <a href="{{ route('peminjaman.index') }}" class="avatar-btn">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Keluar">
                            ⏻ Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-pill">Masuk</a>
                @endauth
            </div>
        </header>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="toast toast-ok" id="flash-toast">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="toast toast-warn" id="flash-toast">{{ session('error') }}</div>
        @endif

        {{-- PAGE CONTENT --}}
        <div class="page-wrap">
            @yield('content')
        </div>

    </div>{{-- end .main-area --}}
</div>{{-- end #app-shell --}}

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('slim');
}
// Auto-hide toast setelah 3 detik
const toast = document.getElementById('flash-toast');
if (toast) setTimeout(() => toast.remove(), 3000);
</script>

@stack('scripts')
</body>
</html>
