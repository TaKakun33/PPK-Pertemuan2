<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $table = 'task_lists';

    protected $fillable = ['nama', 'deskripsi', 'user_id'];

    /** Pemilik daftar tugas (FR-10) */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Seluruh anggota: owner + kolaborator */
    public function members()
    {
        return $this->belongsToMany(User::class, 'task_list_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /** Tugas-tugas di dalam daftar ini */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_list_id');
    }

    /** Helper untuk pengecekan otorisasi (FR-12 / FR-15) */
    public function isOwnedBy(?User $user): bool
    {
        return $user !== null && (int) $this->user_id === (int) $user->id;
    }
}