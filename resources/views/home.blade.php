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

    {{-- Section: Tugas Belum Selesai --}}
    <div style="margin-top: 8px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tugas Yang Perlu Dikerjakan</h2>
        <p class="text-muted" style="margin-bottom: 16px;">Tugas yang belum selesai, diurutkan berdasarkan prioritas dan tenggat waktu</p>

        @if ($pendingTasks->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
                @foreach ($pendingTasks as $task)
                    @php
                        $borderColor = match($task->prioritas) {
                            'Tinggi' => '#ef4444',
                            'Sedang' => '#f59e0b',
                            default  => '#6366f1',
                        };
                        $prioBg = match($task->prioritas) {
                            'Tinggi' => 'background:#fee2e2; color:#991b1b;',
                            'Sedang' => 'background:#fef3c7; color:#92400e;',
                            default  => 'background:#e0e7ff; color:#3730a3;',
                        };
                        $statusBg = $task->status === 'Sedang Dikerjakan'
                            ? 'background:#dbeafe; color:#1e40af;'
                            : 'background:#fef3c7; color:#92400e;';
                        $isOverdue = $task->tenggat_waktu && $task->tenggat_waktu->isPast();
                    @endphp
                    <div style="
                        background: #fff;
                        border-radius: 12px;
                        border-left: 4px solid {{ $borderColor }};
                        padding: 20px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
                        display: flex;
                        flex-direction: column;
                        gap: 12px;
                        transition: transform 0.15s ease, box-shadow 0.15s ease;
                    " onmouseenter="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.12)';"
                       onmouseleave="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06)';">

                        {{-- Header: Judul + Prioritas --}}
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.4;">
                                {{ $task->judul }}
                            </h3>
                            <span style="
                                {{ $prioBg }}
                                font-size: 11px;
                                font-weight: 600;
                                padding: 3px 8px;
                                border-radius: 6px;
                                white-space: nowrap;
                            ">{{ $task->prioritas ?? '-' }}</span>
                        </div>

                        {{-- Kategori --}}
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 13px; color: #94a3b8;">📂</span>
                            <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                                {{ $task->taskList?->nama ?? 'Tanpa Kategori' }}
                            </span>
                        </div>

                        {{-- Tenggat Waktu --}}
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 13px; color: #94a3b8;">📅</span>
                            @if ($task->tenggat_waktu)
                                <span style="font-size: 13px; font-weight: 500; color: {{ $isOverdue ? '#dc2626' : '#475569' }};">
                                    {{ $task->tenggat_waktu->format('d M Y') }}
                                    @if ($isOverdue)
                                        <span style="
                                            background: #fef2f2;
                                            color: #dc2626;
                                            font-size: 11px;
                                            font-weight: 600;
                                            padding: 2px 6px;
                                            border-radius: 4px;
                                            margin-left: 4px;
                                        ">Terlambat</span>
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px; margin-left: 4px;">
                                            ({{ $task->tenggat_waktu->diffForHumans() }})
                                        </span>
                                    @endif
                                </span>
                            @else
                                <span style="font-size: 13px; color: #94a3b8;">Tidak ada tenggat</span>
                            @endif
                        </div>

                        {{-- Status + Aksi --}}
                        <div style="margin-top: auto; padding-top: 8px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9;">
                            <span style="
                                {{ $statusBg }}
                                font-size: 12px;
                                font-weight: 600;
                                padding: 4px 10px;
                                border-radius: 6px;
                                margin-top: 8px;
                            ">{{ $task->status }}</span>
                            <a href="{{ route('tasks.collaboration.show', $task->id) }}" style="
                                font-size: 12px;
                                font-weight: 600;
                                color: #4f46e5;
                                text-decoration: none;
                                padding: 4px 10px;
                                border-radius: 6px;
                                border: 1px solid #e0e7ff;
                                background: #f5f3ff;
                                margin-top: 8px;
                                transition: background 0.15s ease;
                            " onmouseenter="this.style.background='#e0e7ff'" onmouseleave="this.style.background='#f5f3ff'">
                                Kolaborasi →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card" style="text-align: center; padding: 32px;">
                <p style="font-size: 36px; margin-bottom: 8px;">🎉</p>
                <p style="font-size: 16px; font-weight: 600; color: #166534;">Semua tugas sudah selesai!</p>
                <p class="text-muted">Tidak ada tugas yang perlu dikerjakan saat ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection