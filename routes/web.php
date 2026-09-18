<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskCollaborationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Models\Task;
use App\Http\Controllers\DashboardController;;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    $user = auth()->user();
    $pendingTasks = \App\Models\Task::with('taskList')
        ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('collaborators', fn ($q) => $q->where('users.id', $user->id));
        })
        ->whereIn('status', ['Belum Dikerjakan', 'Sedang Dikerjakan'])
        ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
        ->orderBy('tenggat_waktu')
        ->get();

    return view('home', compact('pendingTasks'));
})->middleware('auth')->name('home');

// FR-07: Dashboard monitoring dengan data asli (bukan lagi dummy)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Rute untuk Modul Kolaborasi & Status Tugas (P3)
Route::middleware('auth')->group(function () {
    Route::get('/tasks/{id}/collaboration', [TaskCollaborationController::class, 'show'])->name('tasks.collaboration.show');
    Route::post('/tasks/{id}/collaborators', [TaskCollaborationController::class, 'addCollaborator'])->name('tasks.collaborators.add');
    Route::delete('/tasks/{taskId}/collaborators/{userId}', [TaskCollaborationController::class, 'removeCollaborator'])->name('tasks.collaborators.remove');
    Route::patch('/tasks/{id}/status', [TaskCollaborationController::class, 'updateStatus'])->name('tasks.status.update');
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
// FR-12: seluruh rute daftar tugas dilindungi middleware 'auth'.
// Permintaan dari pengguna yang belum login akan ditolak (redirect ke /login).
 
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // FR-01: User membuat & mengelola daftar tugas (kategori) sendiri
    // FR-12: seluruh rute daftar tugas dilindungi middleware 'auth'.
    // Permintaan dari pengguna yang belum login akan ditolak (redirect ke /login).
    Route::get('/task-lists', [TaskListController::class, 'index'])->name('task-lists.index');
    Route::get('/task-lists/create', [TaskListController::class, 'create'])->name('task-lists.create');
    Route::post('/task-lists', [TaskListController::class, 'store'])->name('task-lists.store');
    Route::delete('/task-lists/{id}', [TaskListController::class, 'destroy'])->name('task-lists.destroy');
});

// FR-08/FR-09: Admin mengelola akun User (khusus role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});
