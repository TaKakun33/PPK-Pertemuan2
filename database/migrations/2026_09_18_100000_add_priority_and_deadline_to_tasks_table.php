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
        Schema::table('tasks', function (Blueprint $table) {
            // FR-03: setiap tugas harus punya prioritas dan tenggat waktu
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])
                  ->default('Sedang')
                  ->after('judul');

            $table->date('tenggat_waktu')->nullable()->after('prioritas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['prioritas', 'tenggat_waktu']);
        });
    }
};
