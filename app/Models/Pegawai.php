<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = ['nama', 'email', 'no_hp', 'alamat'];

    public function attendances()
    {
        return $this->hasMany(Kehadiran::class);
    }
}
