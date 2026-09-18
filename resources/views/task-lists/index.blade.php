@extends('layouts.app')

@section('title', 'Daftar Tugas Saya - JARA')

@section('content')
<div class="page-container" style="max-width: 720px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Daftar Tugas</h1>
            <p class="page-subtitle">Buat kategori/daftar tugas Anda sendiri, misal "Tugas Kuliah" atau "Tugas Kantor"</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            <strong>Perhatian:</strong> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; color: #0f172a;">+ Buat Daftar Tugas Baru</h3>
        <form method="POST" action="{{ route('task-lists.store') }}" style="display: flex; gap: 12px; align-items: flex-end;">
            @csrf
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label for="nama">Nama Daftar Tugas</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    placeholder="Contoh: Tugas Kuliah, Proyek Freelance, dll"
                    value="{{ old('nama') }}"
                    required
                >
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        @if ($taskLists->count() > 0)
            <div class="table-responsive" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Daftar Tugas</th>
                            <th>Jumlah Tugas</th>
                            <th style="text-align: right; width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taskLists as $index => $list)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $list->nama }}</strong></td>
                                <td>{{ $list->tasks_count }} tugas</td>
                                <td style="text-align: right;">
                                    <form action="{{ route('task-lists.destroy', [$list->user_id, $list->id]) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus daftar tugas ini?')"
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
                <p>Anda belum punya daftar tugas. Buat satu menggunakan form di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection
