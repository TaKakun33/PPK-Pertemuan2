<?php $__env->startSection('title', 'Daftar Tugas Saya - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container" style="max-width: 720px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Daftar Tugas</h1>
            <p class="page-subtitle">Buat kategori/daftar tugas Anda sendiri, misal "Tugas Kuliah" atau "Tugas Kantor"</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <strong>Berhasil!</strong> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-error">
            <strong>Perhatian:</strong> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <ul style="padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; color: #0f172a;">+ Buat Daftar Tugas Baru</h3>
        <form method="POST" action="<?php echo e(route('task-lists.store')); ?>" style="display: flex; gap: 12px; align-items: flex-end;">
            <?php echo csrf_field(); ?>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label for="nama">Nama Daftar Tugas</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    placeholder="Contoh: Tugas Kuliah, Proyek Freelance, dll"
                    value="<?php echo e(old('nama')); ?>"
                    required
                >
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <?php if($taskLists->count() > 0): ?>
            <div class="table-responsive" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Daftar Tugas</th>
                            <th>Jumlah Tugas</th>
                            <th style="text-align: right; width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $taskLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><strong><?php echo e($list->nama); ?></strong></td>
                                <td><?php echo e($list->tasks_count); ?> tugas</td>
                                <td style="text-align: right;">
                                    <form action="<?php echo e(route('task-lists.destroy', [$list->user_id, $list->id])); ?>" method="POST" style="margin: 0;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus daftar tugas ini?')"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Anda belum punya daftar tugas. Buat satu menggunakan form di atas.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/task-lists/index.blade.php ENDPATH**/ ?>