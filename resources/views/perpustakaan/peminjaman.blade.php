@extends('layouts.perpustakaan')

@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman & Pengembalian')

@section('content')
<div class="page">
    <div class="page-hd">
        <h2>Peminjaman & Pengembalian</h2>
        <p class="page-sub">Akun: {{ auth()->user()->nama }} ({{ auth()->user()->nis }})</p>
    </div>

    {{-- Summary --}}
    <div class="loan-summary">
        <div class="loan-stat c-amber">
            <strong>{{ $pending->count() }}</strong>
            <span>Menunggu Persetujuan</span>
        </div>
        <div class="loan-stat c-blue">
            <strong>{{ $aktif->where('status','dipinjam')->count() }}</strong>
            <span>Sedang Dipinjam</span>
        </div>
        <div class="loan-stat c-red">
            <strong>{{ $aktif->where('status','terlambat')->count() }}</strong>
            <span>Terlambat</span>
        </div>
        <div class="loan-stat c-green">
            <strong>{{ $riwayat->where('status','dikembalikan')->count() }}</strong>
            <span>Dikembalikan</span>
        </div>
    </div>

    {{-- Alert pending --}}
    @if($pending->count() > 0)
    <div style="background:#fef9c3;border:1.5px solid #fde047;border-radius:10px;padding:14px 18px;margin-bottom:18px;font-size:13.5px;color:#a16207;">
        ⏳ <strong>{{ $pending->count() }}</strong> permintaan peminjaman sedang menunggu persetujuan petugas perpustakaan.
    </div>
    @endif

    {{-- Tabs --}}
    <div class="loan-tabs-bar" id="loan-tabs">
        <button class="on" onclick="showTab('pending', this)">
            ⏳ Menunggu ({{ $pending->count() }})
        </button>
        <button onclick="showTab('aktif', this)">
            📚 Aktif ({{ $aktif->count() }})
        </button>
        <button onclick="showTab('riwayat', this)">
            ✓ Riwayat ({{ $riwayat->count() }})
        </button>
    </div>

    {{-- Tab: Pending --}}
    <div id="tab-pending" class="loan-table-wrap">
        <table class="loan-table">
            <thead>
                <tr>
                    <th>ID</th><th>Judul Buku</th><th>Pengarang</th>
                    <th>Tgl Minta</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $p)
                <tr>
                    <td class="loan-id">{{ $p->kode_peminjaman }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->buku->pengarang }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td><span class="ls s-pending">menunggu persetujuan</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text3);padding:20px;">
                        Tidak ada permintaan yang menunggu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tab: Aktif --}}
    <div id="tab-aktif" class="loan-table-wrap" style="display:none">
        <table class="loan-table">
            <thead>
                <tr>
                    <th>ID</th><th>Judul Buku</th><th>Pengarang</th>
                    <th>Tgl Pinjam</th><th>Jatuh Tempo</th>
                    <th>Status</th><th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aktif as $p)
                <tr>
                    <td class="loan-id">{{ $p->kode_peminjaman }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->buku->pengarang }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_jatuh_tempo->format('d M Y') }}</td>
                    <td><span class="ls ls-{{ $p->status }}">{{ $p->status }}</span></td>
                    <td>
                        @if($p->denda > 0)
                            <span class="denda">Rp {{ number_format($p->denda,0,',','.') }}</span>
                        @else — @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text3);padding:20px;">
                        Tidak ada peminjaman aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tab: Riwayat --}}
    <div id="tab-riwayat" class="loan-table-wrap" style="display:none">
        <table class="loan-table">
            <thead>
                <tr>
                    <th>ID</th><th>Judul Buku</th><th>Pengarang</th>
                    <th>Tgl Pinjam</th><th>Tgl Kembali</th>
                    <th>Status</th><th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $p)
                <tr>
                    <td class="loan-id">{{ $p->kode_peminjaman }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->buku->pengarang }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d M Y') ?? '—' }}</td>
                    <td><span class="ls ls-{{ $p->status }}">{{ $p->status }}</span></td>
                    <td style="font-size:12px;color:var(--text3);">
                        @if($p->status === 'ditolak')
                            {{ $p->alasan_tolak ?? 'Ditolak petugas' }}
                        @elseif($p->denda > 0)
                            <span class="denda">Denda: Rp {{ number_format($p->denda,0,',','.') }}</span>
                        @else — @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text3);padding:20px;">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script>
function showTab(tab, btn) {
    ['pending','aktif','riwayat'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? '' : 'none';
    });
    document.querySelectorAll('#loan-tabs button').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
}
</script>
@endpush
