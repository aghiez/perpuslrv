@extends('layouts.admin')
@section('title', 'Edit Kelas')

@section('content')

<div style="margin-bottom:18px;">
    <a href="{{ route('admin.kelas.index') }}" class="back-btn">← Kembali ke Daftar Kelas</a>
</div>

<div class="admin-form-card">
    <h3>Edit Kelas</h3>

    <form method="POST" action="{{ route('admin.kelas.update', $kelas->id) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">

            <div class="form-group full">
                <label>Nama Kelas <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $kelas->nama) }}"
                       placeholder="Contoh: X Pemasaran" required />
                @error('nama')<span class="form-err">{{ $message }}</span>@enderror
            </div>
            <div class="form-group full">
                <label>Program Keahlian</label>
                <select name="program_keahlian_id" style="margin-top:8px;">
                    <option value="">-- Pilih Program Keahlian (Opsional) --</option>
                    @foreach($programKeahlian as $pk)
                        <option value="{{ $pk->id }}" {{ old('program_keahlian_id', $kelas->program_keahlian_id) == $pk->id ? 'selected' : '' }}>
                            {{ $pk->nama }}
                        </option>
                    @endforeach
                </select>
                @error('program_keahlian_id')<span class="form-err">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-simpan">💾 Update Kelas</button>
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-g">Batal</a>
        </div>
    </form>
</div>

@endsection
