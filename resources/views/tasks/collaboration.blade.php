@extends('layouts.app')

@section('title', 'Kolaborasi & Status Tugas - JARA')

@section('content')
<div class="page-container">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('tasks.index') }}" style="color: #4f46e5; text-decoration: none; font-size: 14px; font-weight: 500;">
            ← Kembali ke Daftar Tugas
        </a>
    </div>

    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Tugas: {{ $task->judul }}</h1>
            <p class="page-subtitle">Kelola status pengerjaan dan anggota kolaborator tugas</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <strong>Perhatian:</strong> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Info Tugas -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="text-muted" style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Judul Tugas</span>
                <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 2px;">{{ $task->judul }}</h2>
            </div>
            <div>
                @php
                    $statusClass = 'status-belum';
                    if ($task->status === 'Sedang Dikerjakan') {
                        $statusClass = 'status-sedang';
                    } elseif ($task->status === 'Selesai') {
                        $statusClass = 'status-selesai';
                    }
                @endphp
                <span class="badge-status {{ $statusClass }}" style="font-size: 13px; padding: 6px 14px;">
                    Status: {{ $task->status ?? 'Belum Dikerjakan' }}
                </span>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <span class="text-muted" style="font-size: 12px; display: block;">Pemilik Tugas</span>
                <strong style="color: #334155;">{{ $task->owner->name ?? 'User ID: ' . $task->user_id }}</strong>
            </div>
            <div>
                <span class="text-muted" style="font-size: 12px; display: block;">Prioritas</span>
                <strong style="color: #334155;">{{ $task->prioritas ?? '-' }}</strong>
            </div>
            <div>
                <span class="text-muted" style="font-size: 12px; display: block;">Tenggat Waktu</span>
                <strong style="color: #334155;">{{ $task->tenggat_waktu ?? '-' }}</strong>
            </div>
            <div>
                <span class="text-muted" style="font-size: 12px; display: block;">Kategori Tugas</span>
                <strong style="color: #334155;">{{ $task->category_name }}</strong>
            </div>
        </div>
    </div>

    <!-- Grid: Ubah Status & Tambah Kolaborator -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <!-- FR-06: Ubah Status -->
        <div class="card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">
                🔄 Ubah Status Tugas 
            </h3>
            <p class="text-muted" style="margin-bottom: 16px;">Perbarui progress pengerjaan tugas ini</p>

            <form action="{{ route('tasks.status.update', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label for="status">Pilih Status Baru:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Belum Dikerjakan" {{ $task->status == 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                        <option value="Sedang Dikerjakan" {{ $task->status == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="Selesai" {{ $task->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Simpan Perubahan Status
                </button>
            </form>
        </div>

        <!-- FR-05: Tambah Kolaborator -->
        <div class="card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">
                👥 Tambah Kolaborator 
            </h3>
            <p class="text-muted" style="margin-bottom: 16px;">Undang rekan tim untuk bekerja sama</p>

            <form action="{{ route('tasks.collaborators.add', $task->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="user_id">Pilih Pengguna:</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($availableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                    + Tambahkan ke Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kolaborator Saat Ini -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">
                Daftar Kolaborator Tugas
            </h3>
            <p class="text-muted">Orang-orang yang memiliki akses dan ikut mengerjakan tugas ini</p>
        </div>

        @if($task->collaborators->count() > 0)
            <div class="table-responsive" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th style="text-align: right; width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($task->collaborators as $index => $collab)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $collab->name }}</strong>
                                    <span class="role-badge {{ $collab->role }}" style="margin-left: 6px;">{{ $collab->role }}</span>
                                </td>
                                <td>{{ $collab->email }}</td>
                                <td style="text-align: right;">
                                    <form action="{{ route('tasks.collaborators.remove', [$task->id, $collab->id]) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Hapus kolaborator ini dari tugas?')"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada kolaborator yang ditambahkan ke tugas ini.</p>
                <small class="text-muted">Gunakan formulir di atas untuk mengundang rekan tim Anda.</small>
            </div>
        @endif
    </div>
</div>
@endsection
