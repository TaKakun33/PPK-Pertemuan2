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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Tambahkan baris user_id ini
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->unsignedBigInteger('task_list_id');
            $table->string('judul');
            $table->timestamps();
            
            // Opsional: jika Anda sudah punya tabel task_lists, sekalian buat foreign key-nya:
            // $table->foreign('task_list_id')->references('id')->on('task_lists')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};