{{--
    Partial: Book Card
    Variabel: $buku (App\Models\Buku)
--}}
<div class="book-card" onclick="window.location='{{ route('buku.detail', $buku) }}'">
    <div class="book-cover-wrap">
        <div class="book-cover" style="background:{{ $buku->cover_color }}">
            <span class="cover-ltr">{{ mb_substr($buku->judul, 0, 1) }}</span>
        </div>
        <span class="avail-dot {{ $buku->available ? 'yes' : 'no' }}"></span>
    </div>
    <div class="book-body">
        <span class="book-cat-lbl">{{ $buku->kategori }}</span>
        <h4 class="book-title-sm">{{ $buku->judul }}</h4>
        <p class="book-author-sm">{{ $buku->pengarang }}</p>

        {{-- Stars --}}
        <div class="stars">
            @for($i = 1; $i <= 5; $i++)
                <span class="s {{ $i <= round($buku->rating) ? 'on' : '' }}">★</span>
            @endfor
            <span class="cnt">{{ $buku->rating }} ({{ $buku->rating_count }})</span>
        </div>

        @auth
            @if($buku->available)
            <form method="POST" action="{{ route('peminjaman.store') }}"
                  onclick="event.stopPropagation()">
                @csrf
                <input type="hidden" name="buku_id" value="{{ $buku->id }}" />
                <button type="submit" class="borrow-btn">Pinjam</button>
            </form>
            @else
            <button class="borrow-btn q" disabled>Antri</button>
            @endif
        @else
            <a href="{{ route('login') }}" class="borrow-btn"
               onclick="event.stopPropagation()">Pinjam</a>
        @endauth
    </div>
</div>
