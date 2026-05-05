@extends('layouts.admin')
@section('title', 'Edit Buku')

@section('content')

<div style="margin-bottom:18px;">
    <a href="{{ route('admin.buku.index') }}" class="back-btn">← Kembali ke Daftar Buku</a>
</div>

<div class="admin-form-card">
    <h3>Edit Buku — <em style="font-weight:400;color:var(--text2);">{{ $buku->judul }}</em></h3>

    <form method="POST" action="{{ route('admin.buku.update', $buku) }}">
        @csrf @method('PUT')
        <div class="form-grid">

            <div class="form-group full">
                <label>Judul Buku <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required />
                @error('judul')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Pengarang <span style="color:#ef4444">*</span></label>
                <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required />
                @error('pengarang')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Kategori <span style="color:#ef4444">*</span></label>
                <select name="kategori" required>
                    @foreach(['Fiksi','Sastra','Sains','Pelajaran','Pengembangan Diri','Teknologi','Sejarah','Bisnis','Lainnya'] as $cat)
                    <option value="{{ $cat }}" {{ old('kategori', $buku->kategori) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('kategori')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Penerbit <span style="color:#ef4444">*</span></label>
                <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required />
                @error('penerbit')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Tahun Terbit <span style="color:#ef4444">*</span></label>
                <input type="number" name="tahun_terbit"
                       value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                       min="1900" max="{{ date('Y') }}" required />
                @error('tahun_terbit')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>ISBN <span style="color:#ef4444">*</span></label>
                <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}" required />
                @error('isbn')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jumlah Halaman <span style="color:#ef4444">*</span></label>
                <input type="number" name="jumlah_halaman"
                       value="{{ old('jumlah_halaman', $buku->jumlah_halaman) }}" min="1" required />
                @error('jumlah_halaman')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Stok Total <span style="color:#ef4444">*</span></label>
                <input type="number" name="stok"
                       value="{{ old('stok', $buku->stok) }}" min="0" required />
                <span style="font-size:11.5px;color:var(--text3);margin-top:2px;">
                    Stok tersedia saat ini: {{ $buku->stok_tersedia }}
                </span>
                @error('stok')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Lokasi Rak</label>
                <input type="text" name="lokasi_rak"
                       value="{{ old('lokasi_rak', $buku->lokasi_rak) }}"
                       placeholder="Contoh: Rak A-1" />
            </div>

            <div class="form-group">
                <label>Warna Cover</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <div style="width:36px;height:36px;border-radius:6px;background:{{ $buku->cover_color }};border:1px solid var(--border);flex-shrink:0;"></div>
                    <input type="text" name="cover_color" id="coverColorInput"
                           value="{{ old('cover_color', $buku->cover_color) }}" style="flex:1;" />
                </div>
            </div>

            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                @error('deskripsi')<span class="form-err">{{ $message }}</span>@enderror
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn-simpan">💾 Simpan Perubahan</button>
            <a href="{{ route('admin.buku.index') }}" class="btn btn-g">Batal</a>
            <form method="POST" action="{{ route('admin.buku.destroy', $buku) }}"
                  style="margin-left:auto;"
                  onsubmit="return confirm('Yakin hapus buku ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-hapus">✕ Hapus Buku Ini</button>
            </form>
        </div>
    </form>
</div>

@endsection
