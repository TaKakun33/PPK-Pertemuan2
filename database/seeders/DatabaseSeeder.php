<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Idempotent: aman dijalankan berkali-kali tanpa error duplikat.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@jara.test'],
            ['name' => 'Admin JARA', 'password' => 'password123', 'role' => 'admin']
        );

        $user = User::firstOrCreate(
            ['email' => 'user@jara.test'],
            ['name' => 'User Biasa', 'password' => 'password123', 'role' => 'user']
        );

        $collaborator = User::firstOrCreate(
            ['email' => 'colab@jara.test'],
            ['name' => 'Kolaborator', 'password' => 'password123', 'role' => 'collaborator']
        );

        // FR-01: contoh daftar tugas (kategori) milik masing-masing user
        $kuliah = TaskList::firstOrCreate(['user_id' => $user->id, 'nama' => 'Tugas Kuliah']);
        $kantor = TaskList::firstOrCreate(['user_id' => $user->id, 'nama' => 'Tugas Kantor']);
        $adminList = TaskList::firstOrCreate(['user_id' => $admin->id, 'nama' => 'Administrasi Sistem']);

        // FR-03: contoh tugas dengan prioritas & tenggat waktu
        $task1 = Task::firstOrCreate(
            ['user_id' => $user->id, 'judul' => 'Menyusun Laporan Praktikum PBP'],
            [
                'task_list_id' => $kuliah->id,
                'prioritas' => 'Tinggi',
                'tenggat_waktu' => now()->addDays(2),
                'status' => 'Sedang Dikerjakan',
            ]
        );

        Task::firstOrCreate(
            ['user_id' => $user->id, 'judul' => 'Rekap Absensi Mingguan'],
            [
                'task_list_id' => $kantor->id,
                'prioritas' => 'Sedang',
                'tenggat_waktu' => now()->addDays(5),
                'status' => 'Belum Dikerjakan',
            ]
        );

        Task::firstOrCreate(
            ['user_id' => $admin->id, 'judul' => 'Audit Akun User Tidak Aktif'],
            [
                'task_list_id' => $adminList->id,
                'prioritas' => 'Rendah',
                'tenggat_waktu' => now()->subDays(1),
                'status' => 'Belum Dikerjakan',
            ]
        );

        // FR-05: contoh kolaborasi
        $task1->collaborators()->syncWithoutDetaching([$collaborator->id]);
    }
}
