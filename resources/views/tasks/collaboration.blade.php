<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kolaborasi & Status Tugas</title>
</head>
<body>

    @if(session('success'))
        <p style="color: green;"><strong>[SUKSES]</strong> {{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;"><strong>[ERROR]</strong> {{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif


    <h1>Detail Tugas: {{ $task->nama_tugas }}</h1>
    <p><strong>Prioritas:</strong> {{ $task->prioritas ?? '-' }}</p>
    <p><strong>Tenggat Waktu:</strong> {{ $task->tenggat_waktu ?? '-' }}</p>
    <p><strong>Pemilik Tugas:</strong> {{ $task->owner->name ?? 'User ID: ' . $task->user_id }}</p>

    <hr>

    <h2>Ubah Status Tugas (FR-06)</h2>
    <p>Status Saat Ini: <strong>{{ $task->status }}</strong></p>

    <form action="{{ route('tasks.status.update', $task->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <label for="status">Pilih Status Baru:</label>
        <select name="status" id="status">
            <option value="Belum Dikerjakan" {{ $task->status == 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
            <option value="Sedang Dikerjakan" {{ $task->status == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
            <option value="Selesai" {{ $task->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit">Simpan Perubahan Status</button>
    </form>

    <hr>


    <h2>Kolaborasi Tim (FR-05)</h2>

    <h3>Tambah Kolaborator Baru</h3>
    <form action="{{ route('tasks.collaborators.add', $task->id) }}" method="POST">
        @csrf
        <label for="user_id">Pilih Pengguna:</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Pilih User --</option>
            @foreach($availableUsers as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <button type="submit">Tambahkan ke Tugas</button>
    </form>

    <br>


    <h3>Daftar Kolaborator Saat Ini</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($task->collaborators as $index => $collab)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $collab->name }}</td>
                    <td>{{ $collab->email }}</td>
                    <td>
                        <form action="{{ route('tasks.collaborators.remove', [$task->id, $collab->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kolaborator ini dari tugas?')">Hapus Kolaborator</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada kolaborator yang ditambahkan ke tugas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
