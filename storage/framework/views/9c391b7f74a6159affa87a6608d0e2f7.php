<?php $__env->startSection('title', 'Beranda - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Selamat datang, <?php echo e(Auth::user()->name); ?>!</h1>
            <p class="page-subtitle">Sistem Manajemen Tugas & Kolaborasi Tim</p>
        </div>
        <div>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">+ Buat Tugas Baru</a>
        </div>
    </div>

    <div class="card">
        <h2 style="font-size: 18px; margin-bottom: 8px;">Informasi Akun</h2>
        <p class="text-muted">
            Anda sedang masuk sebagai <span class="role-badge <?php echo e(Auth::user()->role); ?>"><?php echo e(Auth::user()->role); ?></span> (<?php echo e(Auth::user()->email); ?>).
        </p>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-primary">
                📋 Lihat Semua Tugas
            </a>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-secondary">
                ➕ Tambah Tugas Baru
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/home.blade.php ENDPATH**/ ?>