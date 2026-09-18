<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskCollaborationController;
use App\Http\Controllers\TaskListController;

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
});

Route::get('/tasks', function () {
    $user = auth()->user();
    $tasks = \App\Models\Task::where('user_id', $user->id)
        ->orWhereHas('collaborators', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->latest()
        ->get();
    return view('tasks.index', ['tasks' => $tasks]);
})->middleware('auth')->name('tasks.index'); // <--- TAMBAHKAN INI

Route::get('/tasks/create', [TaskController::class, 'create'])->middleware('auth')->name('tasks.create'); // Sekalian ditambahkan namanya agar rapi
Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth')->name('tasks.store'); // Sekalian ditambahkan namanya agar rapi
// FR-12: seluruh rute daftar tugas dilindungi middleware 'auth'.
// Permintaan dari pengguna yang belum login akan ditolak (redirect ke /login).
Route::middleware('auth')->group(function () {
    Route::get('/task-lists', [TaskListController::class, 'index'])->name('task-lists.index');
    Route::get('/task-lists/create', [TaskListController::class, 'create'])->name('task-lists.create');
    Route::post('/task-lists', [TaskListController::class, 'store'])->name('task-lists.store');
});
 