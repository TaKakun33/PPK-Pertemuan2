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
                <label for="task_list_id">Kategori Tugas</label>
                <select id="task_list_id" name="task_list_id" class="form-control" required>
                    <option value="">-- Pilih Kategori Tugas --</option>
                    <option value="1" {{ old('task_list_id', $task_list_id ?? '') == '1' ? 'selected' : '' }}>Tugas Kuliah</option>
                    <option value="2" {{ old('task_list_id', $task_list_id ?? '') == '2' ? 'selected' : '' }}>Tugas Kantor</option>
                    <option value="3" {{ old('task_list_id', $task_list_id ?? '') == '3' ? 'selected' : '' }}>Proyek Pribadi</option>
                    <option value="4" {{ old('task_list_id', $task_list_id ?? '') == '4' ? 'selected' : '' }}>Organisasi</option>
                    <option value="5" {{ old('task_list_id', $task_list_id ?? '') == '5' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <small class="text-muted">Pilih kategori daftar tugas yang sesuai.</small>
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

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
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