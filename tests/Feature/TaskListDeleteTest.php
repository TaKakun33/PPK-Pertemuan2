<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TaskListDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat owner beserta satu daftar tugas (kategori task_list_id) yang
     * berisi beberapa task dan beberapa membership/kolaborator.
     *
     * @return array{owner: User, collaborators: Collection, tasks: Collection}
     */
    private function createOwnerWithTaskList(int $taskListId = 1, int $taskCount = 3, int $collaboratorCount = 2): array
    {
        $owner = User::factory()->create();
        $collaborators = User::factory()->count($collaboratorCount)->create();

        $tasks = collect();
        for ($i = 0; $i < $taskCount; $i++) {
            $task = Task::create([
                'user_id' => $owner->id,
                'task_list_id' => $taskListId,
                'judul' => 'Tugas '.($i + 1),
                'status' => 'Belum Dikerjakan',
            ]);

            $task->collaborators()->attach($collaborators->pluck('id')->all());
            $tasks->push($task);
        }

        return [
            'owner' => $owner,
            'collaborators' => $collaborators,
            'tasks' => $tasks,
        ];
    }

    /**
     * Test 1 — Owner dapat menghapus task list beserta seluruh task dan
     * seluruh membership/kolaborator terkait.
     */
    public function test_owner_can_delete_task_list_with_all_tasks_and_memberships(): void
    {
        $data = $this->createOwnerWithTaskList();
        $owner = $data['owner'];

        $this->assertDatabaseCount('tasks', 3);
        $this->assertDatabaseCount('task_user', 6);

        $response = $this->actingAs($owner)->delete(route('task-lists.destroy', [$owner->id, 1]));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success');

        // Task list terhapus (tidak ada lagi task milik owner pada kategori tsb)
        $this->assertDatabaseMissing('tasks', ['user_id' => $owner->id, 'task_list_id' => 1]);
        // Seluruh task terkait terhapus
        $this->assertDatabaseCount('tasks', 0);
        // Seluruh membership/kolaborator terkait terhapus
        $this->assertDatabaseCount('task_user', 0);
    }

    /**
     * Test 2 — Non-owner tidak dapat menghapus task list milik orang lain.
     */
    public function test_non_owner_cannot_delete_task_list(): void
    {
        $data = $this->createOwnerWithTaskList();
        $owner = $data['owner'];
        $intruder = User::factory()->create();

        $response = $this->actingAs($intruder)->delete(route('task-lists.destroy', [$owner->id, 1]));

        $response->assertForbidden();

        // Task list tetap ada
        $this->assertDatabaseHas('tasks', ['user_id' => $owner->id, 'task_list_id' => 1]);
        // Task tetap ada
        $this->assertDatabaseCount('tasks', 3);
        // Membership/kolaborator tetap ada
        $this->assertDatabaseCount('task_user', 6);
    }

    /**
     * Test 3 — Atomic/rollback: jika salah satu proses penghapusan gagal,
     * seluruh perubahan di-rollback sehingga tidak ada data yang terhapus sebagian.
     */
    public function test_deletion_is_atomic_and_rolls_back_when_a_step_fails(): void
    {
        $data = $this->createOwnerWithTaskList();
        $owner = $data['owner'];

        // Simulasikan kegagalan pada proses penghapusan tugas.
        Task::deleting(function () {
            throw new \Exception('Simulasi kegagalan pada proses penghapusan.');
        });

        $this->withoutExceptionHandling();

        try {
            $this->actingAs($owner)->delete(route('task-lists.destroy', [$owner->id, 1]));
            $this->fail('Exception seharusnya dilemparkan ketika proses penghapusan gagal.');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Simulasi kegagalan', $e->getMessage());
        }

        // Transaction di-rollback: task list tetap ada
        $this->assertDatabaseHas('tasks', ['user_id' => $owner->id, 'task_list_id' => 1]);
        // Task tetap ada
        $this->assertDatabaseCount('tasks', 3);
        // Membership/kolaborator tetap ada
        $this->assertDatabaseCount('task_user', 6);
    }
}
