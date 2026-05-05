@extends('layouts.perpustakaan')

@section('title', 'Beranda')
@section('page-title', 'Beranda')

@section('content')
<div class="page">

    {{-- ── HERO ── --}}
    <section class="hero">
        <div class="hero-text">
            <p class="eyebrow">Selamat Datang di</p>
            <h2 class="hero-h">Perpustakaan SMKN 2 Pekalongan</h2>
            <p class="hero-sub">Temukan ribuan buku, pinjam dengan mudah, dan perluas wawasanmu.</p>
            <div class="hero-btns">
                <a href="{{ route('katalog') }}" class="btn btn-p">Jelajahi Katalog</a>
                <a href="{{ route('cari') }}"    class="btn btn-g">Cari Buku</a>
            </div>
        </div>
        <div class="hero-vis">
            @foreach($featured->take(4) as $i => $buku)
            <div class="spine"
                 style="background:{{ $buku->cover_color }};
                        transform:rotate({{ ($loop->index - 1.5) * 6 }}deg) translateY({{ abs($loop->index - 1.5) * 8 }}px)">
                <span>{{ mb_substr($buku->judul, 0, 1) }}</span>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── STATISTIK ── --}}
    <div class="stats">
        @foreach([
            ['4.800+', 'Koleksi Buku'],
            ['1.200+', 'Anggota Aktif'],
            ['320',    'Dipinjam/Bulan'],
            ['98%',    'Kepuasan'],
        ] as [$val, $label])
        <div class="stat-card">
            <strong>{{ $val }}</strong>
            <span>{{ $label }}</span>
        </div>
        @endforeach
    </div>

    {{-- ── BUKU UNGGULAN ── --}}
    <div class="sec">
        <div class="sec-head">
            <h3>Buku Unggulan</h3>
            <a href="{{ route('katalog') }}" class="see-all">Lihat Semua →</a>
        </div>
        <div class="book-grid">
            @foreach($featured as $buku)
                @include('perpustakaan.partials.book-card', ['buku' => $buku])
            @endforeach
        </div>
    </div>

    {{-- ── RATING TERTINGGI ── --}}
    <div class="sec">
        <div class="sec-head">
            <h3>Rating Tertinggi</h3>
            <a href="{{ route('katalog', ['sort' => 'rating']) }}" class="see-all">Lihat Semua →</a>
        </div>
        <div class="book-grid">
            @foreach($topRated as $buku)
                @include('perpustakaan.partials.book-card', ['buku' => $buku])
            @endforeach
        </div>
    </div>

    {{-- ── BERITA ── --}}
    <div class="sec">
        <div class="sec-head">
            <h3>Berita & Pengumuman</h3>
            <a href="{{ route('berita.index') }}" class="see-all">Lihat Semua →</a>
        </div>
        <div class="news-row">
            @foreach($berita as $item)
            <a href="{{ route('berita.show', $item->slug) }}" class="news-card" style="text-decoration:none">
                <span class="news-cat">{{ $item->kategori }}</span>
                <h4>{{ $item->judul }}</h4>
                <p>{{ Str::limit($item->isi, 80) }}</p>
                <span class="news-date">{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
