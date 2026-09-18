@extends('layouts.app')

@section('title', 'Daftar Tugas - JARA')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Daftar Tugas</h1>
            <p class="page-subtitle">Kelola semua tugas pribadi dan kolaborasi tim Anda</p>
        </div>
        <div>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Tambah Tugas Baru</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        @if ($tasks->count() > 0)
            <div class="table-responsive" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Judul Tugas</th>
                            <th>Kategori Tugas</th>
                            <th>Prioritas</th>
                            <th>Tenggat Waktu</th>
                            <th>Pemilik</th>
                            <th>Status</th>
                            <th style="text-align: right; width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $index => $task)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $task->judul }}</strong>
                                </td>
                                <td>
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">
                                        {{ $task->category_name }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $prioritasColor = match($task->prioritas) {
                                            'Tinggi' => 'background: #fee2e2; color: #991b1b;',
                                            'Rendah' => 'background: #f1f5f9; color: #475569;',
                                            default => 'background: #fef3c7; color: #92400e;',
                                        };
                                    @endphp
                                    <span class="badge" style="{{ $prioritasColor }}">{{ $task->prioritas ?? '-' }}</span>
                                </td>
                                <td>
                                    {{ $task->tenggat_waktu ? $task->tenggat_waktu->format('d M Y') : '-' }}
                                    @if ($task->is_overdue)
                                        <br><small style="color: #dc2626; font-weight: 600;">Terlambat</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->user_id === Auth::id())
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">Anda Sendiri</span>
                                    @else
                                        {{ $task->owner->name ?? 'User #' . $task->user_id }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-belum';
                                        if ($task->status === 'Sedang Dikerjakan') {
                                            $statusClass = 'status-sedang';
                                        } elseif ($task->status === 'Selesai') {
                                            $statusClass = 'status-selesai';
                                        }
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $task->status ?? 'Belum Dikerjakan' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('tasks.collaboration.show', $task->id) }}" class="btn btn-secondary btn-sm">
                                        👥 Kolaborasi & Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada tugas yang tersedia saat ini.</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">+ Buat Tugas Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection