<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // GET /login
    public function showLogin()
    {
        return view('perpustakaan.login');
    }

    // POST /login
    public function login(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login dengan NIS atau NIP
        $anggota = Anggota::where('nis', $request->nis)
                          ->orWhere('nip', $request->nis)
                          ->first();

        if (!$anggota || !Hash::check($request->password, $anggota->password)) {
            throw ValidationException::withMessages([
                'nis' => 'NIS/NIP atau password salah.',
            ]);
        }

        if (!$anggota->aktif) {
            throw ValidationException::withMessages([
                'nis' => 'Akun kamu tidak aktif. Hubungi petugas.',
            ]);
        }

        Auth::login($anggota, $request->boolean('remember'));

        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang, ' . $anggota->nama . '!');
    }

    // GET /daftar
    public function showRegister()
    {
        return view('perpustakaan.register');
    }

    // POST /daftar
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nis'      => 'required|string|unique:anggota,nis',
            'email'    => 'required|email|unique:anggota,email',
            'password' => 'required|string|min:8|confirmed',
            'kelas'    => 'nullable|string',
            'jurusan'  => 'nullable|string',
        ]);

        $anggota = Anggota::create([
            'nama'     => $request->nama,
            'nis'      => $request->nis,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'kelas'    => $request->kelas,
            'jurusan'  => $request->jurusan,
            'role'     => 'siswa',
        ]);

        Auth::login($anggota);

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    // POST /logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
