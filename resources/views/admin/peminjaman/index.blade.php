@extends('layouts.admin')
@section('title', 'Manajemen Peminjaman')

@section('content')

{{-- Tabs --}}
<div style="display:flex;gap:4px;background:var(--sand);padding:4px;border-radius:10px;width:fit-content;margin-bottom:20px;">
    @foreach(['pending'=>'⏳ Menunggu','aktif'=>'📚 Aktif','selesai'=>'✓ Selesai'] as $key=>$label)
    <a href="{{ route('admin.peminjaman.index', ['tab'=>$key]) }}"
       class="loan-tabs-bar {{ $tab===$key ? 'on' : '' }}"
       style="padding:7px 18px;border-radius:7px;font-size:13px;font-weight:600;color:var(--text2);text-decoration:none;transition:all .15s;{{ $tab===$key ? 'background:var(--card);color:var(--text);box-shadow:0 1px 4px rgba(0,0,0,.1);' : '' }}">
        {{ $label }}
        @if(isset($counts[$key]) && $counts[$key] > 0)
        <span style="background:{{ $key==='pending'?'#f59e0b':($key==='aktif'?'var(--blue)':'var(--text3)') }};color:#fff;border-radius:20px;padding:1px 7px;font-size:11px;margin-left:4px;">
            {{ $counts[$key] }}
        </span>
        @endif
    </a>
    @endforeach
</div>

<div class="admin-table-wrap">
    <div class="admin-table-head">
        <h3>
            @if($tab === 'pending') Permintaan Peminjaman — Menunggu Persetujuan
            @elseif($tab === 'aktif') Peminjaman Aktif
            @else Riwayat Selesai
            @endif
        </h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Minta</th>
                @if($tab !== 'pending')<th>Jatuh Tempo</th>@endif
                <th>Status</th>
                @if($tab === 'pending')<th>Stok</th>@endif
                @if($tab !== 'selesai')<th>Denda</th>@endif
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td class="loan-id">{{ $p->kode_peminjaman }}</td>
                <td>
                    <div style="font-weight:600;font-size:13px;">{{ $p->anggota->nama }}</div>
                    <div style="font-size:11.5px;color:var(--text3);">{{ $p->anggota->nis }}</div>
                </td>
                <td>
                    <div style="font-weight:600;font-size:13px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->buku->judul }}</div>
                    <div style="font-size:11.5px;color:var(--text3);">{{ $p->buku->pengarang }}</div>
                </td>
                <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                @if($tab !== 'pending')
                <td>{{ $p->tanggal_jatuh_tempo->format('d M Y') }}</td>
                @endif
                <td>
                    <span class="ls s-{{ $p->status }}">{{ $p->status }}</span>
                </td>
                @if($tab === 'pending')
                <td>
                    <span class="{{ $p->buku->stok_tersedia > 0 ? 'ls ls-dikembalikan' : 'ls ls-terlambat' }}">
                        {{ $p->buku->stok_tersedia }} tersedia
                    </span>
                </td>
                @endif
                @if($tab !== 'selesai')
                <td>
                    @if($p->denda > 0)
                        <span class="denda">Rp {{ number_format($p->denda,0,',','.') }}</span>
                    @else — @endif
                </td>
                @endif
                <td>
                    <div class="td-actions">
                        {{-- PENDING: approve atau tolak --}}
                        @if($p->status === 'pending')
                            <form method="POST" action="{{ route('admin.peminjaman.approve', $p) }}">
                                @csrf
                                <button type="submit" class="btn-approve"
                                        onclick="return confirm('Setujui peminjaman ini?')">
                                    ✓ Setujui
                                </button>
                            </form>
                            <button class="btn-tolak"
                                    onclick="document.getElementById('tolak-form-{{ $p->id }}').style.display='block'">
                                ✕ Tolak
                            </button>
                            {{-- Form tolak (tersembunyi) --}}
                            <div id="tolak-form-{{ $p->id }}"
                                 style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:none;align-items:center;justify-content:center;">
                                <div style="background:var(--card);border-radius:14px;padding:24px;width:400px;box-shadow:var(--shadow-lg);">
                                    <h4 style="font-family:var(--ff-serif);margin-bottom:12px;">Alasan Penolakan</h4>
                                    <form method="POST" action="{{ route('admin.peminjaman.tolak', $p) }}">
                                        @csrf
                                        <textarea name="alasan_tolak" rows="3"
                                                  style="width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border);background:var(--sand);font-family:var(--ff-sans);font-size:13.5px;color:var(--text);resize:vertical;"
                                                  placeholder="Contoh: Stok tidak mencukupi…"></textarea>
                                        <div style="display:flex;gap:8px;margin-top:12px;">
                                            <button type="submit" class="btn-hapus">Tolak Peminjaman</button>
                                            <button type="button" class="btn btn-g"
                                                    onclick="document.getElementById('tolak-form-{{ $p->id }}').style.display='none'">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        {{-- AKTIF: kembalikan --}}
                        @elseif(in_array($p->status, ['dipinjam', 'terlambat']))
                            <form method="POST" action="{{ route('admin.peminjaman.kembalikan', $p) }}"
                                  onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                @csrf
                                <button type="submit" class="btn-approve">↩ Kembalikan</button>
                            </form>

                        {{-- DITOLAK: tampilkan alasan --}}
                        @elseif($p->status === 'ditolak')
                            <span style="font-size:11.5px;color:var(--text3);">
                                {{ $p->alasan_tolak ?? '—' }}
                            </span>
                        @else
                            <span style="color:var(--text3);font-size:12px;">—</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;color:var(--text3);padding:24px;">
                    Tidak ada data peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($peminjaman->hasPages())
<div style="margin-top:20px;">{{ $peminjaman->withQueryString()->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
// Show modal tolak
function showTolak(id) {
    document.getElementById('tolak-form-' + id).style.display = 'flex';
}
</script>
@endpush
