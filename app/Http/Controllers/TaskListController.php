<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    /**
     * FR-13: Hapus daftar tugas milik owner beserta seluruh tugas dan data
     * keanggotaan/kolaborator yang terkait.
     *
     * FR-14: Seluruh proses penghapusan dibungkus dalam database transaction
     * sehingga bersifat atomik. Jika salah satu proses gagal, semua di-rollback.
     *
     * FR-15: Hanya pemilik (owner) daftar tugas yang boleh menghapus.
     */
    public function destroy($ownerId, $taskListId)
    {
        $ownerId = (int) $ownerId;
        $taskListId = (int) $taskListId;

        // FR-15: Validasi ownership. Jangan mengandalkan ID dari frontend,
        // bandingkan dengan user yang sedang login.
        if ((int) Auth::id() !== $ownerId) {
            abort(403, 'Akses ditolak. Hanya pemilik daftar tugas yang dapat menghapus daftar ini.');
        }

        // FR-14: Mulai transaction.
        DB::beginTransaction();

        try {
            // Validasi ownership untuk memastikan daftar tugas milik user ini ada.
            $tasks = Task::where('user_id', $ownerId)
                ->where('task_list_id', $taskListId)
                ->get();

            if ($tasks->isEmpty()) {
                abort(404, 'Daftar tugas tidak ditemukan.');
            }

            // FR-13: Hapus seluruh tugas di dalam daftar tugas.
            // Data keanggotaan/kolaborator (tabel task_user) ikut terhapus
            // otomatis melalui ON DELETE CASCADE yang sudah didefinisikan pada
            // tabel task_user, sehingga urutan penghapusan tetap aman terhadap
            // foreign key constraint.
            $tasks->each->delete();

            // FR-14: Commit jika seluruh proses berhasil.
            DB::commit();

            return redirect()->route('tasks.index')
                ->with('success', 'Daftar tugas beserta seluruh tugas dan kolaboratornya berhasil dihapus!');
        } catch (\Throwable $e) {
            // FR-14: Rollback jika terjadi exception/error agar tidak ada
            // data yang terhapus sebagian.
            DB::rollBack();

            throw $e;
        }
    }
}
