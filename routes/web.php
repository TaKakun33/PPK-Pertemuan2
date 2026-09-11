<?php

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

