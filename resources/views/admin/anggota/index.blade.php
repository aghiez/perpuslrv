@extends('layouts.admin')
@section('title', 'Manajemen Anggota')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-family:var(--ff-serif);font-size:22px;">Manajemen Anggota</h2>
        <p style="color:var(--text2);font-size:13px;margin-top:3px;">Total {{ $anggota->total() }} anggota terdaftar</p>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NIS / NIP</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $a)
            <tr>
                <td style="color:var(--text3);font-size:12px;">{{ $a->id }}</td>
                <td>
                    <div style="font-weight:600;font-size:13.5px;">{{ $a->nama }}</div>
                    @if($a->kelas)
                    <div style="font-size:11.5px;color:var(--text3);">{{ $a->kelas }}</div>
                    @endif
                </td>
                <td style="font-family:monospace;font-size:12.5px;">
                    {{ $a->nis ?? $a->nip ?? '—' }}
                </td>
                <td>{{ $a->email }}</td>
                <td>
                    <span class="badge {{ in_array($a->role,['admin','staff']) ? 'b-unavail' : 'b-cat' }}">
                        {{ $a->role }}
                    </span>
                </td>
                <td>
                    <span class="ls {{ $a->aktif ? 'ls-dikembalikan' : 'ls-terlambat' }}">
                        {{ $a->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text3);">
                    {{ $a->created_at->format('d M Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:var(--text3);padding:24px;">
                    Belum ada anggota.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($anggota->hasPages())
<div style="margin-top:20px;">{{ $anggota->withQueryString()->links() }}</div>
@endif

@endsection
