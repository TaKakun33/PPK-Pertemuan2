<?php

namespace App\Http\Controllers;

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
