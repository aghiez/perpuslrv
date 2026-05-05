@extends('layouts.perpustakaan')

@section('title', $buku->judul)
@section('page-title', 'Detail Buku')

@section('content')
<div class="page">
    <a href="{{ url()->previous() }}" class="back-btn">← Kembali</a>

    <div class="detail-layout">
        {{-- Kiri: Cover --}}
        <div class="det-left">
            <div class="cover-xl" style="background:{{ $buku->cover_color }}">
                <span>{{ mb_substr($buku->judul, 0, 1) }}</span>
            </div>
            <div style="text-align:center">
                <span class="status-pill {{ $buku->available ? 'sp-a' : 'sp-u' }}">
                    {{ $buku->available ? '● Tersedia' : '● Sedang Dipinjam' }}
                </span>
            </div>

            @auth
                @if($buku->available)
                <form method="POST" action="{{ route('peminjaman.store') }}" style="margin-top:8px">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}" />
                    <button type="submit" class="btn btn-p btn-full">+ Pinjam Buku Ini</button>
                </form>
                @else
                <button class="btn btn-p btn-full btn-muted" disabled style="margin-top:8px">
                    + Masukkan Antrian
                </button>
                @endif
                <button class="btn btn-g btn-full" style="margin-top:6px">♡ Simpan ke Daftar Bacaan</button>
            @else
                <a href="{{ route('login') }}" class="btn btn-p btn-full" style="margin-top:8px">
                    Masuk untuk Meminjam
                </a>
            @endauth
        </div>

        {{-- Kanan: Info --}}
        <div class="det-right">
            <span class="badge b-cat">{{ $buku->kategori }}</span>
            <h2 class="det-title">{{ $buku->judul }}</h2>
            <p class="det-author">oleh {{ $buku->pengarang }}</p>

            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="s {{ $i <= round($buku->rating) ? 'on' : '' }}">★</span>
                @endfor
                <span class="cnt">{{ $buku->rating }} ({{ $buku->rating_count }} ulasan)</span>
            </div>

            <div class="meta-grid">
                @foreach([
                    ['Tahun Terbit', $buku->tahun_terbit],
                    ['Penerbit',     $buku->penerbit],
                    ['Halaman',      $buku->jumlah_halaman . ' hal'],
                    ['ISBN',         $buku->isbn],
                ] as [$k, $v])
                <div class="meta-item">
                    <span class="meta-k">{{ $k }}</span>
                    <span class="meta-v">{{ $v }}</span>
                </div>
                @endforeach
            </div>

            <p class="det-desc">{{ $buku->deskripsi }}</p>

            {{-- Ulasan --}}
            <h3 class="reviews-h">Ulasan ({{ $buku->ulasan->count() }})</h3>

            @forelse($buku->ulasan as $ulasan)
            <div class="review-item">
                <div class="rev-top">
                    <strong>{{ $ulasan->anggota->nama }}</strong>
                    <span class="rev-stars">
                        {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                    </span>
                    <span class="rev-date">{{ $ulasan->created_at->format('d M Y') }}</span>
                </div>
                <p>{{ $ulasan->komentar }}</p>
            </div>
            @empty
            <p style="color:var(--text3);font-size:13.5px;">Belum ada ulasan.</p>
            @endforelse

            {{-- Form tambah ulasan --}}
            @auth
            <div class="add-review">
                <h4>Tulis Ulasan</h4>
                <form method="POST" action="{{ route('buku.ulasan', $buku) }}">
                    @csrf
                    <div class="star-pick" id="star-pick">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="spk" data-val="{{ $i }}"
                                onclick="setRating({{ $i }})">★</button>
                        @endfor
                        <input type="hidden" name="rating" id="rating-input" value="0" />
                    </div>
                    <textarea name="komentar" placeholder="Bagikan pendapatmu…"
                              rows="3">{{ old('komentar') }}</textarea>
                    <button type="submit" class="btn btn-p">Kirim Ulasan</button>
                </form>
            </div>
            @endauth
        </div>
    </div>

    {{-- Rekomendasi --}}
    @if($rekomendasi->count() > 0)
    <div class="sec" style="margin-top:36px">
        <div class="sec-head">
            <h3>Buku Serupa</h3>
        </div>
        <div class="book-grid">
            @foreach($rekomendasi as $b)
                @include('perpustakaan.partials.book-card', ['buku' => $b])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.spk').forEach((btn, i) => {
        btn.classList.toggle('on', i < val);
    });
}
</script>
@endpush
