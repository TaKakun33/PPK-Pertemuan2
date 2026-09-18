<?php $__env->startSection('title', 'Dashboard - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Aktivitas</h1>
            <p class="page-subtitle">Ringkasan pengerjaan dan status tugas Anda</p>
        </div>
        <div>
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-primary">Lihat Semua Tugas</a>
        </div>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Total Tugas Saya</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?php echo e($totalTugas); ?></h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Belum Dikerjakan</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #92400e; margin-top: 4px;"><?php echo e($statusCounts['Belum Dikerjakan']); ?></h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Sedang Dikerjakan</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #1e40af; margin-top: 4px;"><?php echo e($statusCounts['Sedang Dikerjakan']); ?></h2>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <span class="text-muted">Selesai</span>
            <h2 style="font-size: 28px; font-weight: 800; color: #166534; margin-top: 4px;"><?php echo e($statusCounts['Selesai']); ?></h2>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        
        <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">⏰ Perlu Perhatian</h3>
                <p class="text-muted">Tugas yang terlambat atau tenggatnya sudah dekat</p>
            </div>
            <?php $attentionTasks = $overdueTasks->merge($upcomingTasks); ?>
            <?php if($attentionTasks->count() > 0): ?>
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
                            <?php $__currentLoopData = $attentionTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($item->judul); ?></strong></td>
                                    <td>
                                        <span class="badge" style="background: <?php echo e($item->prioritas === 'Tinggi' ? '#fee2e2; color: #991b1b;' : '#fef3c7; color: #92400e;'); ?>">
                                            <?php echo e($item->prioritas); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php echo e($item->tenggat_waktu?->format('d M Y') ?? '-'); ?>

                                        <?php if($item->is_overdue): ?>
                                            <br><small style="color:#dc2626; font-weight:600;">Terlambat</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $statusClass = $item->status === 'Sedang Dikerjakan' ? 'status-sedang' : ($item->status === 'Selesai' ? 'status-selesai' : 'status-belum');
                                        ?>
                                        <span class="badge-status <?php echo e($statusClass); ?>"><?php echo e($item->status); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Tidak ada tugas yang terlambat atau mendesak. Kerja bagus!</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">👥 Perkembangan Tim</h3>
            <p class="text-muted" style="margin-bottom: 16px;">Progres tugas-tugas yang dikerjakan secara kolaborasi</p>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Total tugas tim</span>
                    <strong><?php echo e($teamSummary['total_tugas_tim']); ?></strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Sedang berjalan</span>
                    <strong style="color:#1e40af;"><?php echo e($teamSummary['berjalan']); ?></strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span class="text-muted">Selesai</span>
                    <strong style="color:#166534;"><?php echo e($teamSummary['selesai']); ?></strong>
                </div>
            </div>

            <?php if($recentTasks->count() > 0): ?>
                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 16px 0;">
                <span class="text-muted" style="font-size: 12px; text-transform: uppercase;">Tugas Terbaru</span>
                <ul style="list-style: none; margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
                    <?php $__currentLoopData = $recentTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="display:flex; justify-content: space-between; font-size: 14px;">
                            <span><?php echo e($item->judul); ?></span>
                            <a href="<?php echo e(route('tasks.collaboration.show', $item->id)); ?>" style="color:#4f46e5; text-decoration:none; font-weight:600;">Lihat →</a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <?php if($systemSummary): ?>
        
        <div class="card" style="margin-top: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #0f172a;">🛡️ Ringkasan Sistem (Admin)</h3>
            <p class="text-muted" style="margin-bottom: 16px;">Pantauan seluruh tugas dan user di sistem JARA</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px;">
                <div>
                    <span class="text-muted">Total User</span>
                    <h3 style="font-size: 22px; font-weight: 800;"><?php echo e($systemSummary['total_user']); ?></h3>
                </div>
                <div>
                    <span class="text-muted">Total Tugas</span>
                    <h3 style="font-size: 22px; font-weight: 800;"><?php echo e($systemSummary['total_tugas']); ?></h3>
                </div>
                <div>
                    <span class="text-muted">Belum Dikerjakan</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#92400e;"><?php echo e($systemSummary['status']['Belum Dikerjakan']); ?></h3>
                </div>
                <div>
                    <span class="text-muted">Sedang Dikerjakan</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#1e40af;"><?php echo e($systemSummary['status']['Sedang Dikerjakan']); ?></h3>
                </div>
                <div>
                    <span class="text-muted">Selesai</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#166534;"><?php echo e($systemSummary['status']['Selesai']); ?></h3>
                </div>
                <div>
                    <span class="text-muted">Terlambat</span>
                    <h3 style="font-size: 22px; font-weight: 800; color:#dc2626;"><?php echo e($systemSummary['terlambat']); ?></h3>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary btn-sm">Kelola Akun User →</a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/dashboard.blade.php ENDPATH**/ ?>