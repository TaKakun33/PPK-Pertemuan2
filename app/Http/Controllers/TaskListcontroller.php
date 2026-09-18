<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TaskListController extends Controller
{
    /** Daftar semua list milik / diikuti user yang login */
    public function index(Request $request)
    {
        $user = $request->user();

        $taskLists = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('task-lists.index', compact('taskLists'));
    }

    /** Form pembuatan daftar tugas */
    public function create()
    {
        return view('task-lists.create');
    }

    /**
     * FR-10: buat daftar tugas, pembuat otomatis jadi owner.
     * FR-11 / NFR-06: seluruh langkah dijalankan dalam satu transaksi.
     * FR-12 / NFR-07: otorisasi ditangani middleware auth + StoreTaskListRequest.
     */
    public function store(StoreTaskListRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            $taskList = DB::transaction(function () use ($data, $user) {
                // Langkah 1: buat daftar tugas
                $taskList = TaskList::create([
                    'nama' => $data['nama'],
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'user_id' => $user->id,   // penetapan owner pada tabel utama
                ]);

                // Langkah 2: catat keanggotaan owner di tabel pivot
                $taskList->members()->attach($user->id, ['role' => 'owner']);

                // Jika langkah 2 gagal (exception), langkah 1 ikut di-rollback.
                return $taskList;
            });
        } catch (Throwable $e) {
            Log::error('Gagal membuat daftar tugas', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Daftar tugas gagal dibuat. Tidak ada data yang tersimpan.');
        }

        return redirect()
            ->route('task-lists.index')
            ->with('success', "Daftar tugas \"{$taskList->nama}\" berhasil dibuat.");
    }

    /**
     * FR-13/FR-14/FR-15: Hapus daftar tugas.
     * Hanya owner yang boleh menghapus.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $taskList = TaskList::findOrFail($id);

        // Pastikan hanya owner yang bisa hapus
        if ($taskList->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus daftar tugas ini.');
        }

        try {
            DB::transaction(function () use ($taskList) {
                // Hapus relasi pivot dulu
                $taskList->members()->detach();
                // Hapus daftar tugas
                $taskList->delete();
            });
        } catch (Throwable $e) {
            Log::error('Gagal menghapus daftar tugas', [
                'task_list_id' => $taskList->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus daftar tugas.');
        }

        return redirect()
            ->route('task-lists.index')
            ->with('success', "Daftar tugas \"{$taskList->nama}\" berhasil dihapus.");
    }
}