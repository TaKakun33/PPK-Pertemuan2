@extends('layouts.app')

@section('title', 'Beranda - JARA')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Selamat datang, {{ Auth::user()->name }}!</h1>
            <p class="page-subtitle">Sistem Manajemen Tugas & Kolaborasi Tim</p>
        </div>
        <div>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Buat Tugas Baru</a>
        </div>
    </div>

    <div class="card">
        <h2 style="font-size: 18px; margin-bottom: 8px;">Informasi Akun</h2>
        <p class="text-muted">
            Anda sedang masuk sebagai <span class="role-badge {{ Auth::user()->role }}">{{ Auth::user()->role }}</span> ({{ Auth::user()->email }}).
        </p>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <a href="{{ route('tasks.index') }}" class="btn btn-primary">
                📋 Lihat Semua Tugas
            </a>
            <a href="{{ route('tasks.create') }}" class="btn btn-secondary">
                ➕ Tambah Tugas Baru
            </a>
        </div>
    </div>
</div>
@endsection