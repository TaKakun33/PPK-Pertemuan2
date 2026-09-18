<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * FR-01/FR-10: Membuat daftar tugas (kategori) baru; pembuat otomatis
     * ditetapkan sebagai pemilik (owner) lewat kolom user_id.
     *
     * FR-11/NFR-06: dijalankan dalam satu transaksi database. Jika langkah
     * pembuatan gagal, tidak ada perubahan sebagian yang tersimpan.
     *
     * FR-12/NFR-07: otorisasi (harus login) & validasi input ditangani oleh
     * StoreTaskListRequest, sebelum controller ini dipanggil.
     */
    public function store(StoreTaskListRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            DB::transaction(function () use ($validated, $user) {
                $user->taskLists()->create($validated);
            });
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Daftar tugas gagal dibuat. Tidak ada data yang tersimpan.');
        }

        return redirect()->route('task-lists.index')->with('success', 'Daftar tugas berhasil dibuat!');
    }

    /**
     * FR-13: Hapus daftar tugas milik owner beserta seluruh tugas dan data
     * keanggotaan/kolaborator yang terkait.
     *
     * FR-14: Seluruh proses penghapusan dibungkus dalam database transaction
     * sehingga bersifat atomik. Jika salah satu proses gagal, semua di-rollback.
     *
     * FR-15: Hanya pemilik (owner) daftar tugas yang boleh menghapus.
     */
    public function destroy($taskListId)
    {
        $taskList = TaskList::findOrFail($taskListId);

        // FR-15: Validasi ownership berdasarkan data di database, bukan
        // dari input URL yang bisa dimanipulasi.
        if (! $taskList->isOwnedBy(Auth::user())) {
            abort(403, 'Akses ditolak. Hanya pemilik daftar tugas yang dapat menghapus daftar ini.');
        }

        DB::beginTransaction();

        try {
            // FR-13: Hapus seluruh tugas di dalam daftar tugas (kalau ada).
            // Data keanggotaan/kolaborator (tabel task_user) ikut terhapus
            // otomatis melalui ON DELETE CASCADE yang sudah didefinisikan pada
            // tabel task_user, sehingga urutan penghapusan tetap aman terhadap
            // foreign key constraint. Daftar tugas yang belum punya tugas
            // sama sekali tetap harus bisa dihapus.
            Task::where('task_list_id', $taskList->id)->get()->each->delete();

            // FR-13: Hapus daftar tugas itu sendiri.
            $taskList->delete();

            // FR-14: Commit jika seluruh proses berhasil.
            DB::commit();

            return redirect()->route('task-lists.index')
                ->with('success', 'Daftar tugas beserta seluruh tugas dan kolaboratornya berhasil dihapus!');
        } catch (\Throwable $e) {
            // FR-14: Rollback jika terjadi exception/error agar tidak ada
            // data yang terhapus sebagian.
            DB::rollBack();

            throw $e;
        }
    }
}
