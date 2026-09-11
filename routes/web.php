<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskCollaborationController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk Modul Kolaborasi & Status Tugas (P3)
Route::get('/tasks/{id}/collaboration', [TaskCollaborationController::class, 'show'])->name('tasks.collaboration.show');
Route::post('/tasks/{id}/collaborators', [TaskCollaborationController::class, 'addCollaborator'])->name('tasks.collaborators.add');
Route::delete('/tasks/{taskId}/collaborators/{userId}', [TaskCollaborationController::class, 'removeCollaborator'])->name('tasks.collaborators.remove');
Route::patch('/tasks/{id}/status', [TaskCollaborationController::class, 'updateStatus'])->name('tasks.status.update');

Route::get('/tasks', function () {
    return view('tasks.index', ['tasks' => \App\Models\Task::latest()->get()]);
});

Route::get('/tasks/create', [TaskController::class, 'create']);
Route::post('/tasks', [TaskController::class, 'store']);
