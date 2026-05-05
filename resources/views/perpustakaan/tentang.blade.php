@extends('layouts.perpustakaan')

@section('title', 'Tentang Perpustakaan')
@section('page-title', 'Tentang Perpustakaan')

@section('content')
<div class="page">

    {{-- Hero --}}
    <div class="tentang-hero">
        <h2>Tentang Perpustakaan</h2>
        <p>SMKN 2 Pekalongan</p>
    </div>

    <div class="tentang-content">

        {{-- Sejarah --}}
        <div class="tentang-block">
            <h3>Sejarah Singkat</h3>
            <p>Perpustakaan SMKN 2 Pekalongan berdiri sejak sekolah ini didirikan dan
            telah berkembang menjadi pusat literasi bagi seluruh warga sekolah.
            Dengan koleksi yang terus bertumbuh, perpustakaan berkomitmen mendukung
            kegiatan belajar mengajar.</p>
        </div>

        {{-- Visi Misi --}}
        <div class="tentang-block">
            <h3>Visi & Misi</h3>
            <p>Visi: Menjadi pusat sumber belajar yang inovatif dan inspiratif bagi
            seluruh warga SMKN 2 Pekalongan.</p>
            <ul style="margin-top:10px;">
                <li>Menyediakan koleksi buku dan sumber belajar yang beragam dan mutakhir.</li>
                <li>Menciptakan suasana perpustakaan yang nyaman dan kondusif untuk belajar.</li>
                <li>Mengembangkan budaya membaca di lingkungan sekolah.</li>
                <li>Memanfaatkan teknologi untuk memudahkan akses informasi.</li>
            </ul>
        </div>

        {{-- Jam Operasional --}}
        <div class="tentang-block">
            <h3>Jam Operasional</h3>
            <div class="jam-grid">
                @foreach([
                    ['Senin – Kamis', '07.30 – 14.00 WIB'],
                    ['Jumat',         '07.30 – 11.30 WIB'],
                    ['Sabtu',         '08.00 – 12.00 WIB'],
                    ['Minggu',        'Tutup'],
                ] as [$hari, $jam])
                <div class="jam-row">
                    <span class="jam-hari">{{ $hari }}</span>
                    <span class="jam-val">{{ $jam }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Kontak --}}
        <div class="tentang-block">
            <h3>Kontak & Lokasi</h3>
            <div class="kontak-grid">
                @foreach([
                    ['Alamat',  'Jl. Perintis Kemerdekaan No.1, Pekalongan, Jawa Tengah'],
                    ['Telepon', '(0285) 421-345'],
                    ['Email',   'perpustakaan@smkn2pkl.sch.id'],
                    ['Petugas', 'Ibu Hartini, S.Pd. (Kepala Perpustakaan)'],
                ] as [$k, $v])
                <div class="kontak-row">
                    <span class="kontak-k">{{ $k }}</span>
                    <span class="kontak-v">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
