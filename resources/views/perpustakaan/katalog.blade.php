@extends('layouts.perpustakaan')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')
<div class="page catalog-page">
    <div class="page-hd">
        <h2>Katalog Buku</h2>
        <p class="page-sub">Menampilkan {{ $bukus->total() }} buku</p>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('katalog') }}" class="cat-toolbar" id="catalog-form">
        <div class="search-bar">
            <span class="search-icon">⌕</span>
            <input type="text" name="q" placeholder="Cari judul atau pengarang…"
                   value="{{ request('q') }}" />
        </div>
        <div class="tbr">
            <label class="avail-tog">
                <input type="checkbox" name="tersedia" value="1"
                       {{ request('tersedia') ? 'checked' : '' }}
                       onchange="this.form.submit()" />
                Tersedia saja
            </label>
            <select name="sort" class="sort-sel" onchange="this.form.submit()">
                <option value="rating" {{ request('sort','rating')==='rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="judul"  {{ request('sort')==='judul'  ? 'selected' : '' }}>Judul A–Z</option>
                <option value="tahun"  {{ request('sort')==='tahun'  ? 'selected' : '' }}>Tahun Terbaru</option>
            </select>
        </div>
    </form>

    {{-- Kategori chips --}}
    <div class="cat-chips">
        @foreach(['Semua','Fiksi','Sastra','Sains','Pelajaran','Pengembangan Diri','Teknologi','Sejarah'] as $cat)
        <a href="{{ route('katalog', array_merge(request()->except('kategori','page'), $cat !== 'Semua' ? ['kategori' => $cat] : [])) }}"
           class="chip {{ request('kategori', 'Semua') === $cat ? 'on' : '' }}">
            {{ $cat }}
        </a>
        @endforeach
    </div>

    {{-- Book grid --}}
    @if($bukus->count() > 0)
    <div class="book-grid">
        @foreach($bukus as $buku)
            @include('perpustakaan.partials.book-card', ['buku' => $buku])
        @endforeach
    </div>

    {{-- Pagination --}}
    <div style="margin-top:24px;">
        {{ $bukus->withQueryString()->links() }}
    </div>

    @else
    <div class="empty-st">
        <p>Tidak ada buku yang ditemukan.</p>
        <a href="{{ route('katalog') }}" class="btn btn-g">Reset Filter</a>
    </div>
    @endif
</div>
@endsection
