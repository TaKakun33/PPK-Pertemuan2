<?php

namespace Tests\Feature;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskListCreationTest extends TestCase
{
    use RefreshDatabase;

    /** FR-10: user yang membuat daftar otomatis jadi owner */
    public function test_user_yang_membuat_daftar_otomatis_menjadi_owner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/task-lists', [
            'nama' => 'Tugas Kuliah',
            'deskripsi' => 'Semester 5',
        ]);

        $response->assertRedirect(route('task-lists.index'));

        $this->assertDatabaseHas('task_lists', [
            'nama' => 'Tugas Kuliah',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('task_list_user', [
            'task_list_id' => TaskList::first()->id,
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }

    /** FR-11: jika salah satu langkah gagal, semuanya di-rollback */
    public function test_pembuatan_daftar_dirollback_jika_salah_satu_langkah_gagal(): void
    {
        $user = User::factory()->create();

        // Paksa langkah ke-2 (insert keanggotaan) gagal
        DB::listen(function ($query) {
            if (str_contains(strtolower($query->sql), 'insert into "task_list_user"')
                || str_contains(strtolower($query->sql), 'insert into `task_list_user`')) {
                throw new \RuntimeException('Simulasi kegagalan penetapan owner');
            }
        });

        $this->actingAs($user)->post('/task-lists', [
            'nama' => 'Tugas Kantor',
        ]);

        // Langkah 1 wajib ikut dibatalkan
        $this->assertDatabaseCount('task_lists', 0);
        $this->assertDatabaseCount('task_list_user', 0);
    }

    /** FR-12: pengguna yang belum login ditolak */
    public function test_guest_tidak_bisa_membuat_daftar_tugas(): void
    {
        $response = $this->post('/task-lists', [
            'nama' => 'Tugas Kuliah',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('task_lists', 0);
    }

    /** NFR-05: input tidak valid ditolak */
    public function test_nama_daftar_wajib_diisi(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/task-lists', [
            'nama' => '',
        ]);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('task_lists', 0);
    }

    /** NFR-05: payload SQL injection diperlakukan sebagai teks biasa */
    public function test_input_sql_injection_tidak_merusak_database(): void
    {
        $user = User::factory()->create();
        $payload = "Tugas'); DROP TABLE task_lists;--";

        $this->actingAs($user)->post('/task-lists', ['nama' => $payload]);

        $this->assertDatabaseHas('task_lists', ['nama' => $payload]);
        $this->assertDatabaseCount('task_lists', 1);
    }
}