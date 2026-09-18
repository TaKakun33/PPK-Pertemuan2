<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // FR-07: Sistem harus menyediakan fitur monitoring agar User/Admin
    // dapat memantau perkembangan tugas dan tim, berdasarkan data asli.
    public function index()
    {
        $user = Auth::user();

        // Tugas yang relevan bagi user: milik sendiri atau tugas kolaborasi
        $myTasks = Task::with(['owner', 'taskList'])
            ->where('user_id', $user->id)
            ->orWhereHas('collaborators', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        $statusCounts = [
            'Belum Dikerjakan' => $myTasks->where('status', 'Belum Dikerjakan')->count(),
            'Sedang Dikerjakan' => $myTasks->where('status', 'Sedang Dikerjakan')->count(),
            'Selesai' => $myTasks->where('status', 'Selesai')->count(),
        ];

        $overdueTasks = $myTasks->filter(fn ($task) => $task->is_overdue)->sortBy('tenggat_waktu');

        $upcomingTasks = $myTasks
            ->filter(function ($task) {
                return $task->status !== 'Selesai'
                    && $task->tenggat_waktu
                    && ! $task->is_overdue
                    && $task->tenggat_waktu->diffInDays(now(), false) >= -7;
            })
            ->sortBy('tenggat_waktu')
            ->take(5);

        $recentTasks = $myTasks->sortByDesc('created_at')->take(5);

        // Ringkasan tim (kolaborasi): berapa tugas yang punya kolaborator,
        // dan progres penyelesaiannya, untuk memantau perkembangan tim.
        $collaborativeTasks = $myTasks->filter(fn ($task) => $task->collaborators->count() > 0);

        $teamSummary = [
            'total_tugas_tim' => $collaborativeTasks->count(),
            'selesai' => $collaborativeTasks->where('status', 'Selesai')->count(),
            'berjalan' => $collaborativeTasks->where('status', 'Sedang Dikerjakan')->count(),
        ];

        // Jika admin, tampilkan juga ringkasan seluruh sistem
        $systemSummary = null;
        if ($user->isAdmin()) {
            $allTasks = Task::all();
            $systemSummary = [
                'total_user' => User::count(),
                'total_tugas' => $allTasks->count(),
                'status' => [
                    'Belum Dikerjakan' => $allTasks->where('status', 'Belum Dikerjakan')->count(),
                    'Sedang Dikerjakan' => $allTasks->where('status', 'Sedang Dikerjakan')->count(),
                    'Selesai' => $allTasks->where('status', 'Selesai')->count(),
                ],
                'terlambat' => $allTasks->filter(fn ($task) => $task->is_overdue)->count(),
            ];
        }

        // Tugas yang belum selesai (untuk ditampilkan di beranda)
        $pendingTasks = $myTasks
            ->whereIn('status', ['Belum Dikerjakan', 'Sedang Dikerjakan'])
            ->sortBy('tenggat_waktu');

        return view('dashboard', [
            'statusCounts' => $statusCounts,
            'totalTugas' => $myTasks->count(),
            'overdueTasks' => $overdueTasks,
            'upcomingTasks' => $upcomingTasks,
            'recentTasks' => $recentTasks,
            'teamSummary' => $teamSummary,
            'systemSummary' => $systemSummary,
            'pendingTasks' => $pendingTasks,
        ]);
    }
}
