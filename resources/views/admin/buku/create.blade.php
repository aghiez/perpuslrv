@extends('layouts.admin')
@section('title', 'Tambah Buku Baru')

@section('content')

<div style="margin-bottom:18px;">
    <a href="{{ route('admin.buku.index') }}" class="back-btn">← Kembali ke Daftar Buku</a>
</div>

<div class="admin-form-card">
    <h3>Tambah Buku Baru</h3>

    <form method="POST" action="{{ route('admin.buku.store') }}">
        @csrf
        <div class="form-grid">

            <div class="form-group full">
                <label>Judul Buku <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       placeholder="Contoh: Laskar Pelangi" required />
                @error('judul')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Pengarang <span style="color:#ef4444">*</span></label>
                <input type="text" name="pengarang" value="{{ old('pengarang') }}"
                       placeholder="Contoh: Andrea Hirata" required />
                @error('pengarang')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Kategori <span style="color:#ef4444">*</span></label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Fiksi','Sastra','Sains','Pelajaran','Pengembangan Diri','Teknologi','Sejarah','Bisnis','Lainnya'] as $cat)
                    <option value="{{ $cat }}" {{ old('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('kategori')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Penerbit <span style="color:#ef4444">*</span></label>
                <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                       placeholder="Contoh: Gramedia" required />
                @error('penerbit')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Tahun Terbit <span style="color:#ef4444">*</span></label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}"
                       min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" required />
                @error('tahun_terbit')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>ISBN <span style="color:#ef4444">*</span></label>
                <input type="text" name="isbn" value="{{ old('isbn') }}"
                       placeholder="978-xxx-xxx-xxx-x" required />
                @error('isbn')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jumlah Halaman <span style="color:#ef4444">*</span></label>
                <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}"
                       min="1" placeholder="300" required />
                @error('jumlah_halaman')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Stok <span style="color:#ef4444">*</span></label>
                <input type="number" name="stok" value="{{ old('stok', 1) }}"
                       min="0" required />
                @error('stok')<span class="form-err">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Lokasi Rak</label>
                <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak') }}"
                       placeholder="Contoh: Rak A-1" />
            </div>

            <div class="form-group">
                <label>Warna Cover (CSS Color)</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" id="colorPicker" style="width:40px;height:36px;border-radius:6px;border:1px solid var(--border);padding:2px;cursor:pointer;background:var(--sand);" />
                    <input type="text" name="cover_color" id="coverColorInput"
                           value="{{ old('cover_color', 'oklch(60% .12 200)') }}"
                           placeholder="oklch(60% .12 200)" style="flex:1;" />
                </div>
            </div>

            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          placeholder="Tuliskan sinopsis atau deskripsi singkat buku…">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<span class="form-err">{{ $message }}</span>@enderror
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn-simpan">💾 Simpan Buku</button>
            <a href="{{ route('admin.buku.index') }}" class="btn btn-g">Batal</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
const picker = document.getElementById('colorPicker');
const input  = document.getElementById('coverColorInput');
picker.addEventListener('input', e => { input.value = e.target.value; });
</script>
@endpush
