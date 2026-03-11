<?php require __DIR__ . '/partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <?php if(isset($_SESSION['error_message'])){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-4">Create User</h3>

                <form method="POST" action="/admin/create-user">
                    <div class="mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirmPassword" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" required>
                            <option value="">Choose role</option>
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Create User</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>