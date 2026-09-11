<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        return view('tasks.create', ['task_list_id' => $request->query('task_list_id')]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_list_id' => ['required', 'integer'],
            'judul' => ['required', 'string', 'max:255'],
        ]);

        Task::create($validated);

        return redirect()->to('/tasks')->with('success', 'Tugas berhasil ditambahkan.');
    }
}
