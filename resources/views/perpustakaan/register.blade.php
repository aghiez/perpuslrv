@extends('layouts.perpustakaan')

@section('title', 'Daftar Akun')
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
            <button onclick="window.location='{{ route('login') }}'">Masuk</button>
            <button class="on">Daftar</button>
        </div>

        {{-- Form Daftar --}}
        <form method="POST" action="{{ route('register') }}" class="login-form">
            @csrf

            <label>Nama Lengkap
                <input type="text" name="nama"
                       value="{{ old('nama') }}"
                       placeholder="Ahmad Santoso" required />
                @error('nama')
                    <span class="form-err">{{ $message }}</span>
                @enderror
            </label>

            <label>NIS
                <input type="text" name="nis"
                       value="{{ old('nis') }}"
                       placeholder="Contoh: 123456" required />
                @error('nis')
                    <span class="form-err">{{ $message }}</span>
                @enderror
            </label>

            <label>Email Sekolah
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@smkn2pkl.sch.id" required />
                @error('email')
                    <span class="form-err">{{ $message }}</span>
                @enderror
            </label>

            <label>Kelas
                <select name="kelas" style="margin-top:8px;">
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
            </label>

            <label>Program Keahlian
                <input type="text" name="jurusan"
                       value="{{ old('jurusan') }}"
                       placeholder="Contoh: Rekayasa Perangkat Lunak" />
                <select name="proggram_keahlian_id" style="margin-top:8px;">
                    <option value="">-- Pilih Program Keahlian --</option>
                    @foreach($programKeahlianList as $program)
                        <option value="{{ $program->id }}" {{ old('program_keahlian_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->nama }}
                        </option>
                    @endforeach
            </label>

            <label>Password
                <input type="password" name="password"
                       placeholder="Min. 8 karakter" required />
                @error('password')
                    <span class="form-err">{{ $message }}</span>
                @enderror
            </label>

            <label>Konfirmasi Password
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password" required />
            </label>

            <button type="submit" class="btn btn-p btn-full">
                Daftar Sekarang
            </button>

            <p class="form-hint">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:var(--blue)">Masuk di sini</a>
            </p>
        </form>
    </div>
</div>
@endsection
