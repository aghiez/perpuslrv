<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramKeahlian;

class ProgramKeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programKeahlian = ProgramKeahlian::withCount('kelas')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.program-keahlian.index', compact('programKeahlian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program-keahlian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:program_keahlians,nama',
            'singkatan' => 'required|string|max:50|unique:program_keahlians,singkatan',
        ]);

        ProgramKeahlian::create([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
        ]);

        return redirect()->route('admin.program-keahlian.index')
            ->with('success', 'Program keahlian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $programKeahlian = ProgramKeahlian::findOrFail($id);
        return view('admin.program-keahlian.show', compact('programKeahlian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $programKeahlian = ProgramKeahlian::findOrFail($id);
        return view('admin.program-keahlian.edit', compact('programKeahlian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:program_keahlians,nama,' . $id,
            'singkatan' => 'required|string|max:50|unique:program_keahlians,singkatan,' . $id,
        ]);

        $programKeahlian = ProgramKeahlian::findOrFail($id);
        $programKeahlian->update([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
        ]);

        return redirect()->route('admin.program-keahlian.index')
            ->with('success', 'Program keahlian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $programKeahlian = ProgramKeahlian::findOrFail($id);
        $programKeahlian->delete();

        return redirect()->route('admin.program-keahlian.index')
            ->with('success', 'Program keahlian berhasil dihapus.');
    }
}
