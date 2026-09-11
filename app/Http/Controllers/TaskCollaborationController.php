<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCollaborationController extends Controller
{
    // Menampilkan halaman detail kolaborasi tugas
    public function show($id)
    {
        $task = Task::with(['collaborators', 'owner'])->findOrFail($id);
        
        // Ambil user lain yang bisa ditambahkan sebagai kolaborator (kecuali pemilik tugas)
        $availableUsers = User::where('id', '!=', $task->user_id)->get();

        return view('tasks.collaboration', compact('task', 'availableUsers'));
    }

    // FR-05: Menambahkan Kolaborator
    public function addCollaborator(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::findOrFail($id);

        // Jangan izinkan pemilik menambahkan dirinya sendiri sebagai kolaborator
        if ($task->user_id == $request->user_id) {
            return back()->with('error', 'Pemilik tugas tidak perlu ditambahkan sebagai kolaborator.');
        }

        // syncWithoutDetaching mencegah duplikasi data kolaborator
        $task->collaborators()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'Kolaborator berhasil ditambahkan!');
    }

    // Menghapus Kolaborator
    public function removeCollaborator($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $task->collaborators()->detach($userId);

        return back()->with('success', 'Kolaborator berhasil dihapus!');
    }

    // FR-06: Mengubah Status Tugas (Pemilik & Kolaborator yang Berhak)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Belum Dikerjakan,Sedang Dikerjakan,Selesai',
        ]);

        $task = Task::findOrFail($id);

        // Gunakan Auth::id(), jika belum pakai auth bisa fallback ke user_id pemilik
        $currentUserId = Auth::id() ?? 1;

        // Cek apakah user adalah pemilik ATAU kolaborator tugas tersebut
        $isOwner = ($task->user_id == $currentUserId);
        $isCollaborator = $task->collaborators()->where('user_id', $currentUserId)->exists();

        if (!$isOwner && !$isCollaborator) {
            return back()->with('error', 'Akses ditolak! Hanya pemilik dan kolaborator yang dapat mengubah status.');
        }

        // Simpan status baru
        $task->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status tugas berhasil diperbarui menjadi: ' . $request->status);
    }
}
