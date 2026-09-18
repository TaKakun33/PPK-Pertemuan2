<?php $__env->startSection('title', 'Edit User - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container" style="max-width: 520px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit User: <?php echo e($user->name); ?></h1>
            <p class="page-subtitle">Perbarui data akun dan peran user</p>
        </div>
    </div>

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
        <form method="POST" action="<?php echo e(route('admin.users.update', $user->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru (opsional)</label>
                <input type="password" id="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="form-group">
                <label for="role">Peran</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="user" <?php echo e(old('role', $user->role) == 'user' ? 'selected' : ''); ?>>User</option>
                    <option value="collaborator" <?php echo e(old('role', $user->role) == 'collaborator' ? 'selected' : ''); ?>>Kolaborator</option>
                    <option value="admin" <?php echo e(old('role', $user->role) == 'admin' ? 'selected' : ''); ?>>Admin</option>
                </select>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">💾 Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>