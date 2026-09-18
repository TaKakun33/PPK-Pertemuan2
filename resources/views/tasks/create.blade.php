@extends('layouts.app')

@section('title', 'Tambah Tugas Baru - JARA')

@section('content')
<div class="page-container" style="max-width: 580px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Tugas Baru</h1>
            <p class="page-subtitle">Buat tugas baru untuk dikerjakan secara mandiri atau bersama tim</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <strong style="display: block; margin-bottom: 6px;">Periksa kembali isian Anda:</strong>
            <ul style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="form-group">
                <label for="task_list_id">Daftar Tugas (Kategori)</label>
                @if ($taskLists->isEmpty())
                    <div class="alert alert-error" style="margin-bottom: 12px;">
                        Anda belum punya daftar tugas. Silakan
                        <a href="{{ route('task-lists.index') }}" style="font-weight: 700;">buat daftar tugas</a>
                        terlebih dahulu sebelum menambahkan tugas.
                    </div>
                @else
                    <select id="task_list_id" name="task_list_id" class="form-control" required>
                        <option value="">-- Pilih Daftar Tugas --</option>
                        @foreach ($taskLists as $list)
                            <option value="{{ $list->id }}" {{ old('task_list_id', $task_list_id ?? '') == $list->id ? 'selected' : '' }}>
                                {{ $list->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Tidak menemukan daftar yang sesuai?
                        <a href="{{ route('task-lists.index') }}">Buat daftar tugas baru</a>.
                    </small>
                @endif
            </div>

            <div class="form-group">
                <label for="judul">Judul Tugas</label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    class="form-control" 
                    placeholder="Contoh: Menyusun Modul Praktikum" 
                    value="{{ old('judul') }}" 
                    required
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="prioritas">Prioritas</label>
                    <select id="prioritas" name="prioritas" class="form-control" required>
                        <option value="Rendah" {{ old('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="Sedang" {{ old('prioritas', 'Sedang') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Tinggi" {{ old('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tenggat_waktu">Tenggat Waktu</label>
                    <input 
                        type="date" 
                        id="tenggat_waktu" 
                        name="tenggat_waktu" 
                        class="form-control" 
                        value="{{ old('tenggat_waktu') }}" 
                        required
                    >
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;" {{ $taskLists->isEmpty() ? 'disabled' : '' }}>
                    💾 Simpan Tugas
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
