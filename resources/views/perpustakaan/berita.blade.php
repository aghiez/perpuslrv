@extends('layouts.perpustakaan')

@section('title', 'Berita & Pengumuman')
@section('page-title', 'Berita & Pengumuman')

@section('content')
<div class="page">
    <div class="page-hd">
        <h2>Berita & Pengumuman</h2>
    </div>

    {{-- Filter kategori --}}
    <div class="cat-chips">
        @foreach(['Semua','Koleksi','Kegiatan','Pengumuman','Informasi'] as $cat)
        <a href="{{ route('berita.index', $cat !== 'Semua' ? ['kategori' => $cat] : []) }}"
           class="chip {{ request('kategori', 'Semua') === $cat ? 'on' : '' }}">
            {{ $cat }}
        </a>
        @endforeach
    </div>

    {{-- Daftar berita --}}
    <div class="news-list">
        @forelse($berita as $item)
        <a href="{{ route('berita.show', $item->slug) }}"
           class="news-list-item" style="text-decoration:none;color:inherit;">
            <div class="nli-left">
                <span class="news-cat">{{ $item->kategori }}</span>
                <h3>{{ $item->judul }}</h3>
                <p>{{ Str::limit($item->isi, 100) }}</p>
                <span class="news-date">
                    {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                </span>
            </div>
            <span class="news-arr">→</span>
        </a>
        @empty
        <div class="empty-st">
            <p>Belum ada berita yang dipublikasikan.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($berita->hasPages())
    <div style="margin-top:24px;">
        {{ $berita->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
