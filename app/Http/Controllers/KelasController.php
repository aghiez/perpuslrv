<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\ProgramKeahlian;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('programKeahlian')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programKeahlian = ProgramKeahlian::orderBy('nama')->get();
        return view('admin.kelas.create', compact('programKeahlian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
            'program_keahlian_id' => 'nullable|exists:program_keahlians,id',
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'program_keahlian_id' => $request->program_keahlian_id,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelas = Kelas::with('programKeahlian')->findOrFail($id);
        return view('admin.kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $programKeahlian = ProgramKeahlian::orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kelas', 'programKeahlian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $id,
            'program_keahlian_id' => 'nullable|exists:program_keahlians,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama' => $request->nama,
            'program_keahlian_id' => $request->program_keahlian_id,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
