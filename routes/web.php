<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskCollaborationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
});
// Rute untuk Modul Kolaborasi & Status Tugas (P3)
Route::middleware('auth')->group(function () {
    Route::get('/tasks/{id}/collaboration', [TaskCollaborationController::class, 'show'])->name('tasks.collaboration.show');
    Route::post('/tasks/{id}/collaborators', [TaskCollaborationController::class, 'addCollaborator'])->name('tasks.collaborators.add');
    Route::delete('/tasks/{taskId}/collaborators/{userId}', [TaskCollaborationController::class, 'removeCollaborator'])->name('tasks.collaborators.remove');
    Route::patch('/tasks/{id}/status', [TaskCollaborationController::class, 'updateStatus'])->name('tasks.status.update');

    // FR-13 / FR-14 / FR-15: Hapus daftar tugas milik owner
    Route::delete('/task-lists/{ownerId}/{taskListId}', [TaskListController::class, 'destroy'])->name('task-lists.destroy');
});

Route::get('/tasks', function () {
    $user = auth()->user();
    $tasks = Task::where('user_id', $user->id)
        ->orWhereHas('collaborators', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->latest()
        ->get();

    return view('tasks.index', ['tasks' => $tasks]);
})->middleware('auth')->name('tasks.index'); // <--- TAMBAHKAN INI

Route::get('/tasks/create', [TaskController::class, 'create'])->middleware('auth')->name('tasks.create'); // Sekalian ditambahkan namanya agar rapi
Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth')->name('tasks.store'); // Sekalian ditambahkan namanya agar rapi
