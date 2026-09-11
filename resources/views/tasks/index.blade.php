<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas - JARA</title>
</head>
<body>
    <h1>Daftar Tugas</h1>

    <a href="/tasks/create">+ Tambah Tugas</a>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <ul>
        @forelse ($tasks as $task)
            <li>[Daftar {{ $task->task_list_id }}] {{ $task->judul }}</li>
        @empty
            <li>Belum ada tugas.</li>
        @endforelse
    </ul>
</body>
</html>