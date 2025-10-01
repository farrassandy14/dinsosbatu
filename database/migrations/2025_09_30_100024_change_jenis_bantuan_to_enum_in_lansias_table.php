<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Simpan sebagai kode pendek agar konsisten & aman panjangnya
        DB::statement("ALTER TABLE lansias 
            MODIFY jenis_bantuan ENUM('INSENTIF','PERMAKANAN') NOT NULL");
    }

    public function down(): void
    {
        // Kembalikan ke varchar jika di-rollback
        DB::statement("ALTER TABLE lansias 
            MODIFY jenis_bantuan VARCHAR(150) NOT NULL");
    }
};
