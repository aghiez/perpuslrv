@extends('layouts.admin')
@section('title', 'Kelas')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-family:var(--ff-serif);font-size:22px;">Kelas</h2>
        <p style="color:var(--text2);font-size:13px;margin-top:3px;">Total {{ $kelas->total() }} kelas terdaftar</p>
    </div>
    <a href="{{ route('admin.kelas.create') }}" class="btn-simpan">+ Tambah Kelas Baru</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Program Keahlian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kelas as $k)
            <tr>
                <td style="color:var(--text3);font-size:12px;">{{ $loop->iteration }}</td>
                <td>
                    <div style="font-weight:600;font-size:13.5px;">{{ $k->nama }}</div>
                </td>
                <td>
                    <div style="font-size:13px;">{{ $k->programKeahlian->nama ?? 'Tidak ada program keahlian' }}</div>
                </td>
                <td>
                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn-edit">✎ Edit</a>
                    <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}"
                          onsubmit="return confirm('Hapus kelas {{ addslashes($k->nama) }}?')"
                          style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus">🗑 Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;color:var(--text3);padding:24px;">
                    Belum ada kelas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($kelas->hasPages())
<div style="margin-top:20px;">{{ $kelas->withQueryString()->links() }}</div>
@endif
@endsection
