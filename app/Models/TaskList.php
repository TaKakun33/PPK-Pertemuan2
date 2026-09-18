<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['user_id', 'nama'];

    // Pemilik daftar tugas
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Semua tugas yang masuk ke dalam daftar tugas ini
    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_list_id');
    }
}
