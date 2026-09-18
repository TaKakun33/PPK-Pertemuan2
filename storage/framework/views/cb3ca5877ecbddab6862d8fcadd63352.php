<?php $__env->startSection('title', 'Tambah Tugas Baru - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container" style="max-width: 580px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Tugas Baru</h1>
            <p class="page-subtitle">Buat tugas baru untuk dikerjakan secara mandiri atau bersama tim</p>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <strong style="display: block; margin-bottom: 6px;">Periksa kembali isian Anda:</strong>
            <ul style="padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="<?php echo e(route('tasks.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="task_list_id">Daftar Tugas (Kategori)</label>
                <?php if($taskLists->isEmpty()): ?>
                    <div class="alert alert-error" style="margin-bottom: 12px;">
                        Anda belum punya daftar tugas. Silakan
                        <a href="<?php echo e(route('task-lists.index')); ?>" style="font-weight: 700;">buat daftar tugas</a>
                        terlebih dahulu sebelum menambahkan tugas.
                    </div>
                <?php else: ?>
                    <select id="task_list_id" name="task_list_id" class="form-control" required>
                        <option value="">-- Pilih Daftar Tugas --</option>
                        <?php $__currentLoopData = $taskLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($list->id); ?>" <?php echo e(old('task_list_id', $task_list_id ?? '') == $list->id ? 'selected' : ''); ?>>
                                <?php echo e($list->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-muted">
                        Tidak menemukan daftar yang sesuai?
                        <a href="<?php echo e(route('task-lists.index')); ?>">Buat daftar tugas baru</a>.
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="judul">Judul Tugas</label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    class="form-control" 
                    placeholder="Contoh: Menyusun Modul Praktikum" 
                    value="<?php echo e(old('judul')); ?>" 
                    required
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="prioritas">Prioritas</label>
                    <select id="prioritas" name="prioritas" class="form-control" required>
                        <option value="Rendah" <?php echo e(old('prioritas') == 'Rendah' ? 'selected' : ''); ?>>Rendah</option>
                        <option value="Sedang" <?php echo e(old('prioritas', 'Sedang') == 'Sedang' ? 'selected' : ''); ?>>Sedang</option>
                        <option value="Tinggi" <?php echo e(old('prioritas') == 'Tinggi' ? 'selected' : ''); ?>>Tinggi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tenggat_waktu">Tenggat Waktu</label>
                    <input 
                        type="date" 
                        id="tenggat_waktu" 
                        name="tenggat_waktu" 
                        class="form-control" 
                        value="<?php echo e(old('tenggat_waktu')); ?>" 
                        required
                    >
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;" <?php echo e($taskLists->isEmpty() ? 'disabled' : ''); ?>>
                    💾 Simpan Tugas
                </button>
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/tasks/create.blade.php ENDPATH**/ ?>