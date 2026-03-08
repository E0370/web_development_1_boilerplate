<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">My Items</h2>

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

    <?php if (!empty($items)) { ?>
        <div class="row g-4">
            <?php foreach ($items as $item) { ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img
                            src="/assets/images/<?= htmlspecialchars($item->image) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($item->title) ?>"
                            style="height: 220px; object-fit: cover;"
                        >

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
                                <a href="/edititem/<?= urlencode($item->id) ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                <form method="POST" action="/deleteitem/<?= urlencode($item->id) ?>" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>No items found.</p>
    <?php } ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>