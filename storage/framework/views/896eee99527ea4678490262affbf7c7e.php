<?php $__env->startSection('title', 'Daftar Tugas - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Daftar Tugas</h1>
            <p class="page-subtitle">Kelola semua tugas pribadi dan kolaborasi tim Anda</p>
        </div>
        <div>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">+ Tambah Tugas Baru</a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <strong>Berhasil!</strong> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden;">
        <?php if($tasks->count() > 0): ?>
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
                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <strong><?php echo e($task->judul); ?></strong>
                                </td>
                                <td>
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">
                                        <?php echo e($task->category_name); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $prioritasColor = match($task->prioritas) {
                                            'Tinggi' => 'background: #fee2e2; color: #991b1b;',
                                            'Rendah' => 'background: #f1f5f9; color: #475569;',
                                            default => 'background: #fef3c7; color: #92400e;',
                                        };
                                    ?>
                                    <span class="badge" style="<?php echo e($prioritasColor); ?>"><?php echo e($task->prioritas ?? '-'); ?></span>
                                </td>
                                <td>
                                    <?php echo e($task->tenggat_waktu ? $task->tenggat_waktu->format('d M Y') : '-'); ?>

                                    <?php if($task->is_overdue): ?>
                                        <br><small style="color: #dc2626; font-weight: 600;">Terlambat</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($task->user_id === Auth::id()): ?>
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">Anda Sendiri</span>
                                    <?php else: ?>
                                        <?php echo e($task->owner->name ?? 'User #' . $task->user_id); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $statusClass = 'status-belum';
                                        if ($task->status === 'Sedang Dikerjakan') {
                                            $statusClass = 'status-sedang';
                                        } elseif ($task->status === 'Selesai') {
                                            $statusClass = 'status-selesai';
                                        }
                                    ?>
                                    <span class="badge-status <?php echo e($statusClass); ?>">
                                        <?php echo e($task->status ?? 'Belum Dikerjakan'); ?>

                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?php echo e(route('tasks.collaboration.show', $task->id)); ?>" class="btn btn-secondary btn-sm">
                                        👥 Kolaborasi & Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada tugas yang tersedia saat ini.</p>
                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary btn-sm">+ Buat Tugas Pertama</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/tasks/index.blade.php ENDPATH**/ ?>