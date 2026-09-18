<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        // FR-01: daftar tugas (kategori) yang ditampilkan adalah milik user sendiri
        $taskLists = $request->user()->taskLists()->latest()->get();

        return view('tasks.create', [
            'task_list_id' => $request->query('task_list_id'),
            'taskLists' => $taskLists,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_list_id' => [
                'required',
                Rule::exists('task_lists', 'id')->where('user_id', $request->user()->id),
            ],
            'judul' => 'required|string|max:255',
            // FR-03: setiap tugas wajib punya prioritas dan tenggat waktu
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'tenggat_waktu' => 'required|date',
        ]);

        // Langsung buat task melalui relasi user
        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat!');
    }

    public function index()
    {
        // Mengambil task milik user yang sedang login, ditambah task
        // kolaborasi di mana user ini ikut terlibat.
        $user = auth()->user();

        $tasks = Task::with(['owner', 'taskList'])
            ->where('user_id', $user->id)
            ->orWhereHas('collaborators', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->latest()
            ->get();

        return view('tasks.index', compact('tasks'));
    }
}
