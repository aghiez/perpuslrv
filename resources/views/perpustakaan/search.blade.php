@extends('layouts.perpustakaan')

@section('title', 'Pencarian')
@section('page-title', 'Pencarian Buku')

@section('content')
<div class="page">
    <div class="page-hd">
        <h2>Pencarian Buku</h2>
    </div>

    {{-- Big Search --}}
    <div class="big-search-wrap">
        <form method="GET" action="{{ route('cari') }}" class="big-search">
            <input type="text" name="q"
                   placeholder="Ketik judul, pengarang, atau kategori…"
                   value="{{ request('q') }}" autofocus />
            <button type="submit" class="btn btn-p">Cari</button>
        </form>
    </div>

    @if(request()->filled('q'))
        <p class="result-cnt">
            {{ $results->total() }} hasil untuk
            "<strong>{{ request('q') }}</strong>"
        </p>

        @if($results->count() > 0)
            <div class="book-grid">
                @foreach($results as $buku)
                    @include('perpustakaan.partials.book-card', ['buku' => $buku])
                @endforeach
            </div>
            <div style="margin-top:24px;">
                {{ $results->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-st">
                <p>Buku tidak ditemukan. Coba kata kunci lain.</p>
                <a href="{{ route('cari') }}" class="btn btn-g">Reset Pencarian</a>
            </div>
        @endif
    @endif
</div>
@endsection
