@extends('layouts.admin')
@section('title', 'Program Keahlian')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-family:var(--ff-serif);font-size:22px;">Program Keahlian</h2>
        <p style="color:var(--text2);font-size:13px;margin-top:3px;">Total {{ $programKeahlian->total() }} program keahlian terdaftar</p>
    </div>
    <a href="{{ route('admin.program-keahlian.create') }}" class="btn-simpan">+ Tambah Program Keahlian Baru</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Program Keahlian</th>
                <th>Nama Pendek</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programKeahlian as $p)
            <tr>
                <td style="color:var(--text3);font-size:12px;">{{ $loop->iteration }}</td>
                <td>
                    <div style="font-weight:600;font-size:13.5px;">{{ $p->nama }}</div>
                </td>
                <td>
                    <div style="font-weight:600;font-size:13.5px;">{{ $p->singkatan }}</div>
                </td>
                <td>
                    <a href="{{ route('admin.program-keahlian.edit', $p) }}" class="btn-edit">✎ Edit</a>
                    <form method="POST" action="{{ route('admin.program-keahlian.destroy', $p) }}"
                          onsubmit="return confirm('Hapus program keahlian {{ addslashes($p->nama) }}?')"
                          style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus">🗑 Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center;color:var(--text3);padding:24px;">
                    Belum ada program keahlian.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($programKeahlian->hasPages())
<div style="margin-top:20px;">{{ $programKeahlian->withQueryString()->links() }}</div>
@endif
@endsection
