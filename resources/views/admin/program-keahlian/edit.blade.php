@extends('layouts.admin')
@section('title', 'Edit Program Keahlian')

@section('content')

<div style="margin-bottom:18px;">
    <a href="{{ route('admin.program-keahlian.index') }}" class="back-btn">← Kembali ke Daftar Program Keahlian</a>
</div>

<div class="admin-form-card">
    <h3>Edit Program Keahlian</h3>

    <form method="POST" action="{{ route('admin.program-keahlian.update', $programKeahlian->id) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">

            <div class="form-group full">
                <label>Nama Program Keahlian <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $programKeahlian->nama) }}"
                       placeholder="Contoh: Pemasaran" required />
                @error('nama')<span class="form-err">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group full">
                <label>Nama Pendek <span style="color:#ef4444">*</span></label>
                <input type="text" name="singkatan" value="{{ old('singkatan', $programKeahlian->singkatan) }}"
                       placeholder="Contoh: PMR" required />
                @error('singkatan')<span class="form-err">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-simpan">💾 Update Program Keahlian</button>
            <a href="{{ route('admin.program-keahlian.index') }}" class="btn btn-g">Batal</a>
        </div>
    </form>
</div>

@endsection
