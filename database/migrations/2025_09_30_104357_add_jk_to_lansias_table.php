<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lansias', function (Blueprint $table) {
            // Tambah kolom jk (L/P) setelah kolom nama
            if (! Schema::hasColumn('lansias', 'jk')) {
                $table->enum('jk', ['L','P'])->default('L')->after('nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lansias', function (Blueprint $table) {
            if (Schema::hasColumn('lansias', 'jk')) {
                $table->dropColumn('jk');
            }
        });
    }
};
