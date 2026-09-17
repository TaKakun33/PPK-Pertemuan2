@extends('layouts.app')

@section('title', 'Dashboard - JARA')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Aktivitas</h1>
            <p class="page-subtitle">Ringkasan pengerjaan dan status tugas</p>
        </div>
        <div>
            <a href="{{ route('tasks.index') }}" class="btn btn-primary">Lihat Semua Tugas</a>
        </div>
    </div>

    @php
        $sampleTasks = [
            ['title' => 'Menyusun Laporan Praktikum PBP', 'priority' => 'Tinggi', 'due_date' => '2026-09-20', 'status' => 'Sedang Dikerjakan'],
            ['title' => 'Integrasi Modul Kolaborasi Tugas', 'priority' => 'Tinggi', 'due_date' => '2026-09-22', 'status' => 'Sedang Dikerjakan'],
            ['title' => 'Membuat Dokumentasi SRS JARA', 'priority' => 'Sedang', 'due_date' => '2026-09-15', 'status' => 'Selesai'],
        ];
    @endphp

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Ringkasan Tugas Terkini</h3>
        </div>
        <div class="table-responsive" style="border: none; border-radius: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Tugas</th>
                        <th>Prioritas</th>
                        <th>Tenggat Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sampleTasks as $item)
                        <tr>
                            <td><strong>{{ $item['title'] }}</strong></td>
                            <td>
                                <span class="badge" style="background: {{ $item['priority'] === 'Tinggi' ? '#fee2e2; color: #991b1b;' : '#fef3c7; color: #92400e;' }}">
                                    {{ $item['priority'] }}
                                </span>
                            </td>
                            <td>{{ $item['due_date'] }}</td>
                            <td>
                                <span class="badge-status {{ $item['status'] === 'Selesai' ? 'status-selesai' : 'status-sedang' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection