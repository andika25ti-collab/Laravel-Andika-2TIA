<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('pelanggan_id')->get();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    // Menampilkan form Tambah Pelanggan
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    // Menyimpan pelanggan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthday' => ['required', 'date'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'email' => ['required', 'email', Rule::unique('pelanggan', 'email')],
            'phone' => ['required', 'numeric'],
        ]);

        Pelanggan::create($validated);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Penambahan data pelanggan berhasil!');
    }

    // Menampilkan detail pelanggan
    public function show(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    // Menampilkan form edit pelanggan
    public function edit(Pelanggan $pelanggan)
    {
        $dataPelanggan = $pelanggan;

        return view('admin.pelanggan.edit', compact('dataPelanggan'));
    }

    // Memperbarui data pelanggan
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthday' => ['required', 'date'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'email' => [
                'required',
                'email',
                Rule::unique('pelanggan', 'email')->ignore($pelanggan->getKey(), $pelanggan->getKeyName()),
            ],
            'phone' => ['required', 'numeric'],
        ]);

        $pelanggan->update($validated);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // Menghapus pelanggan
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus!');
    }
}