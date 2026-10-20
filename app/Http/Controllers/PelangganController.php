<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    // Menampilkan form Tambah Pelanggan
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    // Menerima data dari form (Langkah 3 modul: debug dulu pakai dd)
    public function store(Request $request)
    {


		//dd($request->all())
		
		$data['first_name'] = $request->first_name;
		$data['last_name'] = $request->last_name;
		$data['birthday'] = $request->birthday;
		$data['gender'] = $request->gender;
		$data['email'] = $request->email;
		$data['phone'] = $request->phone;
		
		Pelanggan::create($data);
		
		return redirect()->route('pelanggan.index')->with('success','Penambahan Data Berhasil!');
}
}