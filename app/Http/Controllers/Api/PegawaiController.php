<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'email' => 'required|email|unique:pegawais,email',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'tanggal_masuk' => 'required|date'
        ]);

        return Pegawai::create($data);
    }

    public function show(Pegawai $pegawai)
    {
        return $pegawai;
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'nama' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('pegawais')->ignore($pegawai->id),
            ],
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'tanggal_masuk' => 'required|date'
        ]);

        $pegawai->update($data);

        return $pegawai;
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return response()->json(['message' => 'deleted']);
    }
}
