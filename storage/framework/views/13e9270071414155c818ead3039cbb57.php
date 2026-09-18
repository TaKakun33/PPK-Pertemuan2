<?php $__env->startSection('title', 'Login - JARA'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>JARA</h1>
                <p>Sistem Manajemen Tugas & Kolaborasi</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.process')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo e(old('email')); ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taka/Praktikum4/PPK-Pertemuan2/resources/views/auth/login.blade.php ENDPATH**/ ?>