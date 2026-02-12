<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        return Pegawai::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pegawai',
            'no_hp' => 'nullable',
            'alamat' => 'nullable'
        ]);

        return Pegawai::create($data);
    }

    public function show(Pegawai $pegawai)
    {
        return $pegawai;
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $pegawai->update($request->all());
        return $pegawai;
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return response()->json(['message' => 'deleted']);
    }
}
