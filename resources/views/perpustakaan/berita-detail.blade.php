@extends('layouts.perpustakaan')

@section('title', $berita->judul)
@section('page-title', 'Berita')

@section('content')
<div class="page">
    <a href="{{ route('berita.index') }}" class="back-btn">← Kembali</a>

    <div class="news-detail">
        <span class="badge b-cat">{{ $berita->kategori }}</span>
        <h2>{{ $berita->judul }}</h2>
        <p class="news-meta">
            {{ $berita->published_at?->format('d M Y') ?? $berita->created_at->format('d M Y') }}
            · Perpustakaan SMKN 2 Pekalongan
        </p>
        <p class="news-body">{{ $berita->isi }}</p>
        <p class="news-body">
            Untuk informasi lebih lanjut, silakan hubungi petugas perpustakaan
            atau kunjungi langsung di jam operasional 07.30–14.00 WIB pada
            hari Senin–Jumat.
        </p>
    </div>
</div>
@endsection
