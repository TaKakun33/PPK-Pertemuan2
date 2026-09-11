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
            'task_list_id' => 'required',
            'judul' => 'required',
        ]);

        // Langsung buat task melalui relasi user
        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat!');
    }

    public function index()
    {
        // Mengambil task milik user yang sedang login
        $tasks = auth()->user()->tasks; 
        
        // Pastikan Anda sudah membuat file view: resources/views/tasks/index.blade.php
        return view('tasks.index', compact('tasks'));
    }
}
