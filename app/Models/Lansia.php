<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lansia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik','nama','jk','kecamatan','desa',
        'jenis_bantuan','tahun','sumber_dana',
    ];
}
