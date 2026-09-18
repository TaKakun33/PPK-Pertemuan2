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
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    // FR-01: Menampilkan semua daftar tugas milik user, sekaligus form tambah baru
    public function index()
    {
        $taskLists = auth()->user()
            ->taskLists()
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('task-lists.index', compact('taskLists'));
    }

    // FR-01: Membuat daftar tugas (kategori) baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        auth()->user()->taskLists()->create($validated);

        return redirect()->route('task-lists.index')->with('success', 'Daftar tugas berhasil dibuat!');
    }

    // Menghapus daftar tugas. Hanya boleh oleh pemiliknya dan hanya jika
    // sudah tidak ada tugas yang menggunakan daftar ini, supaya data tugas
    // yang sudah ada tidak kehilangan kategorinya secara tiba-tiba.
    public function destroy($id)
    {
        $taskList = auth()->user()->taskLists()->findOrFail($id);

        if ($taskList->tasks()->exists()) {
            return back()->with('error', 'Daftar tugas ini masih memiliki tugas di dalamnya dan tidak bisa dihapus.');
        }

        $taskList->delete();

        return back()->with('success', 'Daftar tugas berhasil dihapus.');
    }
}
