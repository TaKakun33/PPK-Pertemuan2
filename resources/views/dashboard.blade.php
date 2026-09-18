@extends('layouts.app')

@section('title', 'Dashboard - JARA')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Aktivitas</h1>
            <p class="page-subtitle">Ringkasan pengerjaan dan status tugas Anda</p>
        </div>
        <div>
            <a href="{{ route('tasks.index') }}" class="btn btn-primary">Lihat Semua Tugas</a>
        </div>
    </div>

    {{-- FR-07: Ringkasan status tugas (data asli dari database) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Total Tugas Saya</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalTugas }}</h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Belum Dikerjakan</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #92400e; margin-top: 4px;">{{ $statusCounts['Belum Dikerjakan'] }}</h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Sedang Dikerjakan</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #1e40af; margin-top: 4px;">{{ $statusCounts['Sedang Dikerjakan'] }}</h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Selesai</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #166534; margin-top: 4px;">{{ $statusCounts['Selesai'] }}</h2>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        {{-- Tugas mendekati tenggat & terlambat --}}
        <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">⏰ Perlu Perhatian</h3>
                <p class="text-muted">Tugas yang terlambat atau tenggatnya sudah dekat</p>
            </div>
            @php $attentionTasks = $overdueTasks->merge($upcomingTasks); @endphp
            @if ($attentionTasks->count() > 0)
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
                            @foreach ($attentionTasks as $item)
                                <tr>
                                    <td><strong>{{ $item->judul }}</strong></td>
                                    <td>
                                        <span class="badge" style="background: {{ $item->prioritas === 'Tinggi' ? '#fee2e2; color: #991b1b;' : '#fef3c7; color: #92400e;' }}">
                                            {{ $item->prioritas }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $item->tenggat_waktu?->format('d M Y') ?? '-' }}
                                        @if ($item->is_overdue)
                                            <br><small style="color:#dc2626; font-weight:600;">Terlambat</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = $item->status === 'Sedang Dikerjakan' ? 'status-sedang' : ($item->status === 'Selesai' ? 'status-selesai' : 'status-belum');
                                        @endphp
                                        <span class="badge-status {{ $statusClass }}">{{ $item->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>Tidak ada tugas yang terlambat atau mendesak. Kerja bagus!</p>
                </div>
            @endif
        </div>

        {{-- Ringkasan Tim / Kolaborasi --}}
        <div class="card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">👥 Perkembangan Tim</h3>
            <p class="text-muted" style="margin-bottom: 16px;">Progres tugas-tugas yang dikerjakan secara kolaborasi</p>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Total tugas tim</span>
                    <strong>{{ $teamSummary['total_tugas_tim'] }}</strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Sedang berjalan</span>
                    <strong style="color:#1e40af;">{{ $teamSummary['berjalan'] }}</strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Selesai</span>
                    <strong style="color:#166534;">{{ $teamSummary['selesai'] }}</strong>
                </div>
            </div>

            @if ($recentTasks->count() > 0)
                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 16px 0;">
                <span class="text-muted" style="font-size: 12px; text-transform: uppercase;">Tugas Terbaru</span>
                <ul style="list-style: none; margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
                    @foreach ($recentTasks as $item)
                        <li style="display:flex; justify-content: space-between; font-size: 14px;">
                            <span>{{ $item->judul }}</span>
                            <a href="{{ route('tasks.collaboration.show', $item->id) }}" style="color:#4f46e5; text-decoration:none; font-weight:600;">Lihat →</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    @if ($systemSummary)
        {{-- Ringkasan seluruh sistem, khusus Admin (FR-07 + FR-08) --}}
        <div class="card" style="margin-top: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">🛡️ Ringkasan Sistem (Admin)</h3>
            <p class="text-muted" style="margin-bottom: 16px;">Pantauan seluruh tugas dan user di sistem JARA</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px;">
                <div>
                    <span class="text-muted">Total User</span>
                    <h3 style="font-size: 22px; font-weight: 800;">{{ $systemSummary['total_user'] }}</h3>
                </div>
                <div>
                    <span class="text-muted">Total Tugas</span>
                    <h3 style="font-size: 22px; font-weight: 800;">{{ $systemSummary['total_tugas'] }}</h3>
                </div>
                <div>
                    <span class="text-muted">Belum Dikerjakan</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#92400e;">{{ $systemSummary['status']['Belum Dikerjakan'] }}</h3>
                </div>
                <div>
                    <span class="text-muted">Sedang Dikerjakan</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#1e40af;">{{ $systemSummary['status']['Sedang Dikerjakan'] }}</h3>
                </div>
                <div>
                    <span class="text-muted">Selesai</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#166534;">{{ $systemSummary['status']['Selesai'] }}</h3>
                </div>
                <div>
                    <span class="text-muted">Terlambat</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#dc2626;">{{ $systemSummary['terlambat'] }}</h3>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Kelola Akun User →</a>
            </div>
        </div>
    @endif
</div>
@endsection
