<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $fillable = ['pegawai_id', 'tanggal', 'status'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
