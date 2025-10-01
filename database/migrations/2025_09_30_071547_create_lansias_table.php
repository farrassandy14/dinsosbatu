<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('lansias', function (Blueprint $table) {
        $table->id();
        $table->string('nik', 16)->unique();
        $table->string('nama');
        $table->enum('jenis_kelamin', ['L','P']); // L=Laki-laki, P=Perempuan
        $table->enum('kecamatan', config('wilayah.kecamatan'));
        $table->string('desa'); // validasi kombinasi desa↔kecamatan di FormRequest
        $table->enum('jenis_bantuan', ['Insentif Rp 500.000/bulan','Insentif Permakanan']);
        $table->unsignedSmallInteger('tahun');
        $table->enum('sumber_dana', ['APBD','APBN']);
        $table->timestamps();
    });
}

};
