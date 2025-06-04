<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar, atau App\Http\Controllers\Admin jika di subfolder
use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class AdminMajorController extends Controller
{
    /**
     * Tampilkan daftar semua jurusan.
     */
    public function index()
    {
        $majors = Major::orderBy('name')->paginate(10);
        // Path view ini harus cocok dengan struktur folder Anda, misal: resources/views/dashboard/admin/majors/index.blade.php
        return view('dashboard.admin.majors.index', compact('majors'));
    }

    /**
     * Tampilkan form untuk membuat jurusan baru.
     */
    public function create()
    {
        // Path view ini harus cocok dengan struktur folder Anda
        return view('dashboard.admin.majors.create');
    }

    /**
     * Simpan jurusan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
                'code' => 'required|string|max:50|unique:majors,code', // VALIDASI UNTUK CODE
                'name' => 'required|string|max:255|unique:majors,name',
                'description' => 'nullable|string',
            ]);


        Major::create($request->all());

        // DIUBAH: Menghapus 'dashboard.' dari nama rute
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail jurusan tertentu.
     */
    public function show(Major $major)
    {
        // Path view ini harus cocok dengan struktur folder Anda
        return view('dashboard.admin.majors.show', compact('major'));
    }

    /**
     * Tampilkan form untuk mengedit jurusan.
     */
    public function edit(Major $major)
    {
        // Path view ini harus cocok dengan struktur folder Anda
        return view('dashboard.admin.majors.edit', compact('major'));
    }

    /**
     * Perbarui jurusan di database.
     */
    public function update(Request $request, Major $major)
    {
        $request->validate([
                'code' => 'required|string|max:50|unique:majors,code,' . $major->id, // VALIDASI UNTUK CODE (unique diupdate)
                'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
                'description' => 'nullable|string',
            ]);

        $major->update($request->all());

        // INI SUDAH BENAR: Menggunakan 'admin.majors.index'
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil diperbarui!');
    }

    /**
     * Hapus jurusan dari database.
     */
    public function destroy(Major $major)
    {
        $major->delete();
        // INI SUDAH BENAR: Menggunakan 'admin.majors.index'
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil dihapus!');
    }
}
