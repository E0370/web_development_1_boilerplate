<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <a href="/admin/create-user" class="btn btn-primary mb-3">Create New User</a>

    <?php if(isset($_SESSION['success_message'])){ ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>

    <?php if(isset($_SESSION['error_message'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>

    <h4 class="mt-4 mb-3">Users</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user){ ?>
                    <tr>
                        <td><?= htmlspecialchars($user->id) ?></td>
                        <td><?= htmlspecialchars($user->firstname) ?></td>
                        <td><?= htmlspecialchars($user->lastname) ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= htmlspecialchars($user->role) ?></td>
                        <td>
                            <form method="POST" action="/admin/user/role/<?= urlencode($user->id) ?>" class="d-inline">
                                <select name="role" class="form-select form-select-sm d-inline w-auto">
                                    <option value="user" <?= $user->role === 'user' ? 'selected' : '' ?>>user</option>
                                    <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                            </form>

                            <form method="POST" action="/admin/user/delete/<?= urlencode($user->id) ?>" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <h4 class="mt-5 mb-3">All Posts</h4>
    <div class="row g-4">
        <?php foreach($items as $item){ ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <img src="/assets/images/<?= htmlspecialchars($item->image) ?>"
                         class="card-img-top"
                         alt="<?= htmlspecialchars($item->title) ?>"
                         style="height:220px; object-fit:cover;">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item->title) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item->description) ?></p>
                        <span class="badge bg-secondary"><?= htmlspecialchars($item->status) ?></span>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <?php
                            $date = new DateTime($item->created_at);
                            echo $date->format('d F');
                            ?>
                        </small>

                        <div class="d-flex gap-2">
                            <a href="/admin/edititem/<?= urlencode($item->id) ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                            <form method="POST" action="/admin/deleteitem/<?= urlencode($item->id) ?>" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>