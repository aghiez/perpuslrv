@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stats --}}
<div class="admin-stats">
    <div class="admin-stat-card">
        <strong>{{ $totalBuku }}</strong>
        <span>Total Koleksi Buku</span>
    </div>
    <div class="admin-stat-card">
        <strong>{{ $totalAnggota }}</strong>
        <span>Anggota Siswa</span>
    </div>
    <div class="admin-stat-card" style="{{ $menungguApprove > 0 ? 'border-color:#f59e0b;' : '' }}">
        <strong style="{{ $menungguApprove > 0 ? 'color:#f59e0b;' : '' }}">{{ $menungguApprove }}</strong>
        <span>Menunggu Persetujuan</span>
    </div>
    <div class="admin-stat-card">
        <strong>{{ $peminjamanAktif }}</strong>
        <span>Sedang Dipinjam</span>
    </div>
</div>

{{-- Alert jika ada yang menunggu --}}
@if($menungguApprove > 0)
<div style="background:#fef9c3;border:1.5px solid #fde047;border-radius:10px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <span style="font-size:13.5px;font-weight:600;color:#a16207;">
        ⚠ Ada <strong>{{ $menungguApprove }}</strong> permintaan peminjaman yang menunggu persetujuanmu!
    </span>
    <a href="{{ route('admin.peminjaman.index', ['tab' => 'pending']) }}" class="btn-approve">
        Proses Sekarang →
    </a>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Recent Peminjaman --}}
    <div class="admin-table-wrap">
        <div class="admin-table-head">
            <h3>Peminjaman Terbaru</h3>
            <a href="{{ route('admin.peminjaman.index') }}" class="see-all">Lihat Semua →</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPeminjaman as $p)
                <tr>
                    <td>{{ $p->anggota->nama }}</td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->buku->judul }}</td>
                    <td><span class="ls ls-{{ $p->status }}">{{ $p->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--text3);padding:16px;">Belum ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Quick Stats --}}
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="admin-table-wrap" style="padding:20px;">
            <h3 style="font-family:var(--ff-serif);font-size:16px;margin-bottom:14px;">Ringkasan Hari Ini</h3>
            @foreach([
                ['Buku Tersedia', $bukuTersedia, 'c-green'],
                ['Total Denda Belum Bayar', 'Rp '.number_format($totalDenda,0,',','.'), 'c-amber'],
            ] as [$label, $val, $col])
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                <span style="font-size:13px;color:var(--text2);">{{ $label }}</span>
                <strong class="{{ $col }}" style="font-size:14px;">{{ $val }}</strong>
            </div>
            @endforeach
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <a href="{{ route('admin.buku.create') }}" class="btn-simpan" style="justify-content:center;">+ Tambah Buku</a>
            <a href="{{ route('admin.peminjaman.index', ['tab'=>'pending']) }}" class="btn-approve" style="justify-content:center;">⚡ Pending ({{ $menungguApprove }})</a>
        </div>
    </div>

</div>

@endsection
