<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Tambahkan 'user_id' di sini 👇
    protected $fillable = [
        'task_list_id',
        'judul',
        'prioritas',
        'tenggat_waktu',
        'status',
        'user_id',
    ];

    protected $casts = [
        'tenggat_waktu' => 'date',
    ];

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // FR-01: relasi ke daftar tugas (kategori) tempat tugas ini berada
    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'task_list_id');
    }

    // Accessor untuk nama kategori tugas berdasarkan task_list_id.
    // Diambil dari relasi taskList (dibuat sendiri oleh user, FR-01).
    // Fallback ke kategori lama tetap dipertahankan agar data lama
    // (yang dibuat sebelum fitur task_lists ada) tetap tampil wajar.
    public function getCategoryNameAttribute()
    {
        if ($this->taskList) {
            return $this->taskList->nama;
        }

        $legacyCategories = [
            1 => 'Tugas Kuliah',
            2 => 'Tugas Kantor',
            3 => 'Proyek Pribadi',
            4 => 'Organisasi',
            5 => 'Lainnya',
        ];

        return $legacyCategories[$this->task_list_id] ?? ('Kategori #' . $this->task_list_id);
    }

    // Bantuan untuk FR-07: tugas yang tenggatnya sudah lewat dan belum selesai
    public function getIsOverdueAttribute()
    {
        return $this->tenggat_waktu
            && $this->tenggat_waktu->isPast()
            && $this->status !== 'Selesai';
    }
}