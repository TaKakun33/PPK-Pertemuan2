<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Tambahkan 'user_id' di sini 👇
    protected $fillable = ['task_list_id', 'judul', 'status', 'user_id'];

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}