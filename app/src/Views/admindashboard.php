<?php require __DIR__ . '/partials/header.php'; ?>

<?php $currentUserId = $_SESSION['user']['id'] ?? null; ?>

<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <a href="/admin/createaccount" class="btn btn-primary mb-3">Create New User</a>

    <?php if (isset($_SESSION['success_message'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['error_message'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>

    <h4 class="mt-4 mb-3">Users</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <caption>User Management Table</caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col" style="width: 280px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?= htmlspecialchars($user->id) ?></td>
                        <td><?= htmlspecialchars($user->firstname) ?></td>
                        <td><?= htmlspecialchars($user->lastname) ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= htmlspecialchars($user->role) ?></td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-between align-items-center gap-3" style="min-width:220px;">
                                
                                <?php if ((string)$user->id === (string)$currentUserId) { ?>
                                    <span class="text-muted small">You cannot change your own role</span>
                                <?php } else { ?>
                                    <form method="POST" action="/admin/user/role/<?= urlencode($user->id) ?>"
                                        class="d-flex align-items-center gap-2 mb-0">
                                        <select name="role" class="form-select form-select-sm w-auto">
                                            <option value="user" <?= $user->role === 'user' ? 'selected' : '' ?>>user</option>
                                            <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                <?php } ?>

                                <form method="POST" action="/admin/user/delete/<?= urlencode($user->id) ?>" class="mb-0"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <section aria-label="All Posts">
        <h4 class="mt-5 mb-3">All Posts</h4>
        <div class="row g-4">
            <?php foreach ($items as $item) { ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <article class="card h-100 shadow-sm">
                        <img src="/assets/images/<?= htmlspecialchars($item->image) ?>" class="card-img-top"
                            alt="<?= htmlspecialchars($item->title) ?>" style="height:220px; object-fit:cover;">

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
                                <a href="/admin/edititem/<?= urlencode($item->id) ?>"
                                    class="btn btn-sm btn-outline-primary">Edit</a>

                                <form method="POST" action="/admin/deleteitem/<?= urlencode($item->id) ?>" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>