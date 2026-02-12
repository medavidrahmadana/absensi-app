<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = ['nama', 'email', 'no_hp', 'alamat', 'tanggal_masuk'];

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }
}
