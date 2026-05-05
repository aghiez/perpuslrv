@extends('layouts.perpustakaan')

@section('title', 'Masuk')
@section('page-title', 'Masuk / Daftar')

@section('content')
<div class="page login-page">
    <div class="login-card">
        <div class="login-logo">
            <div class="lc-mark">P</div>
            <div>
                <h3>Perpustakaan Digital</h3>
                <p>SMKN 2 Pekalongan</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="login-tabs">
            <button class="{{ !request()->routeIs('register') ? 'on' : '' }}"
                    onclick="window.location='{{ route('login') }}'">Masuk</button>
            <button class="{{ request()->routeIs('register') ? 'on' : '' }}"
                    onclick="window.location='{{ route('register') }}'">Daftar</button>
        </div>

        @if(request()->routeIs('register'))
        {{-- Form Daftar --}}
        <form method="POST" action="{{ route('register') }}" class="login-form">
            @csrf
            <label>Nama Lengkap
                <input type="text" name="nama" value="{{ old('nama') }}"
                       placeholder="Ahmad Santoso" required />
                @error('nama')<span class="form-err">{{ $message }}</span>@enderror
            </label>
            <label>NIS
                <input type="text" name="nis" value="{{ old('nis') }}"
                       placeholder="Contoh: 123456" required />
                @error('nis')<span class="form-err">{{ $message }}</span>@enderror
            </label>
            <label>Email Sekolah
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="nama@smkn2pkl.sch.id" required />
                @error('email')<span class="form-err">{{ $message }}</span>@enderror
            </label>
            <label>Password
                <input type="password" name="password"
                       placeholder="Min. 8 karakter" required />
                @error('password')<span class="form-err">{{ $message }}</span>@enderror
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password" required />
            </label>
            <button type="submit" class="btn btn-p btn-full">Daftar Sekarang</button>
        </form>

        @else
        {{-- Form Masuk --}}
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <label>NIS / NIP
                <input type="text" name="nis" value="{{ old('nis') }}"
                       placeholder="Contoh: 123456" required autofocus />
                @error('nis')<span class="form-err">{{ $message }}</span>@enderror
            </label>
            <label>Password
                <input type="password" name="password"
                       placeholder="••••••••" required />
            </label>
            <label style="flex-direction:row;align-items:center;gap:8px;font-weight:400;">
                <input type="checkbox" name="remember" /> Ingat saya
            </label>
            <button type="submit" class="btn btn-p btn-full">Masuk</button>
            <p class="form-hint">Lupa password? Hubungi petugas perpustakaan.</p>
        </form>
        @endif
    </div>
</div>
@endsection
