<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas - JARA</title>
</head>
<body>
    <h1>Tambah Tugas Baru</h1>

    <form method="POST" action="/tasks">
        @csrf

        <p>
            <label>ID Daftar Tugas</label>
            <input type="number" name="task_list_id" value="{{ old('task_list_id', $task_list_id ?? '') }}" required>
        </p>

        <p>
            <label>Nama Tugas</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required>
        </p>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <button type="submit">Simpan Tugas</button>
        <a href="/tasks">Kembali</a>
    </form>
</body>
</html>