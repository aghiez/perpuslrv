@extends('layouts.admin')
@section('title', 'Kelola Buku')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-family:var(--ff-serif);font-size:22px;">Kelola Buku</h2>
        <p style="color:var(--text2);font-size:13px;margin-top:3px;">Total {{ $bukus->total() }} buku dalam koleksi</p>
    </div>
    <a href="{{ route('admin.buku.create') }}" class="btn-simpan">+ Tambah Buku Baru</a>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.buku.index') }}" style="margin-bottom:16px;">
    <div class="search-bar" style="max-width:360px;">
        <span class="search-icon">⌕</span>
        <input type="text" name="q" placeholder="Cari judul atau pengarang…"
               value="{{ request('q') }}" />
    </div>
</form>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Tersedia</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bukus as $buku)
            <tr>
                <td style="color:var(--text3);font-size:12px;">{{ $buku->id }}</td>
                <td>
                    <div style="font-weight:600;font-size:13.5px;">{{ $buku->judul }}</div>
                    <div style="font-size:11.5px;color:var(--text3);">{{ $buku->isbn }}</div>
                </td>
                <td>{{ $buku->pengarang }}</td>
                <td><span class="badge b-cat">{{ $buku->kategori }}</span></td>
                <td>{{ $buku->stok }}</td>
                <td>
                    <span class="{{ $buku->stok_tersedia > 0 ? 'ls ls-dipinjam' : 'ls ls-terlambat' }}">
                        {{ $buku->stok_tersedia }}
                    </span>
                </td>
                <td>★ {{ $buku->rating }}</td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.buku.edit', $buku) }}" class="btn-edit">✎ Edit</a>
                        <form method="POST" action="{{ route('admin.buku.destroy', $buku) }}"
                              onsubmit="return confirm('Hapus buku {{ addslashes($buku->judul) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus">✕ Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:var(--text3);padding:24px;">
                    Tidak ada buku ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($bukus->hasPages())
<div style="margin-top:20px;">{{ $bukus->withQueryString()->links() }}</div>
@endif

@endsection
