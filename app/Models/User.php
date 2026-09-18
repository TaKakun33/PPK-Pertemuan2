<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Tugas-tugas di mana user ini menjadi kolaborator (FR-05)
    public function collaboratedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')->withTimestamps();
    }

    // Relasi: Tugas-tugas yang dibuat oleh user ini
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relasi: Daftar tugas (kategori) milik user ini (FR-01)
    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }

    // FR-08/FR-09: helper untuk cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
