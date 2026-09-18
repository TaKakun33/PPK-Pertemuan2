<?php $__env->startSection('title', 'Kelola User - JARA'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen User</h1>
            <p class="page-subtitle">Kelola akun Admin, User, dan Kolaborator dalam sistem</p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">+ Tambah User</a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><strong>Berhasil!</strong> <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-error"><strong>Perhatian:</strong> <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive" style="border: none; border-radius: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Tugas Dibuat</th>
                        <th>Tugas Kolaborasi</th>
                        <th style="text-align: right; width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><strong><?php echo e($u->name); ?></strong></td>
                            <td><?php echo e($u->email); ?></td>
                            <td><span class="role-badge <?php echo e($u->role); ?>"><?php echo e($u->role); ?></span></td>
                            <td><?php echo e($u->tasks_count); ?></td>
                            <td><?php echo e($u->collaborated_tasks_count); ?></td>
                            <td style="text-align: right;">
                                <a href="<?php echo e(route('admin.users.edit', $u->id)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="<?php echo e(route('admin.users.destroy', $u->id)); ?>" method="POST" style="display:inline; margin: 0;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus akun <?php echo e($u->name); ?>?')"
                                        <?php echo e($u->id === Auth::id() ? 'disabled' : ''); ?>

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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/admin/users/index.blade.php ENDPATH**/ ?>