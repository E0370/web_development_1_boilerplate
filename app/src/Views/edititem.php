<?php require __DIR__ . '/partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <?php if (isset($_SESSION['error_message'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-4">Edit Item</h3>

                <form method="POST" action="/edititem/<?= urlencode($item->id) ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            id="title"
                            name="title"
                            value="<?= htmlspecialchars($item->title) ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($item->description) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="lost" <?= $item->status === 'lost' ? 'selected' : '' ?>>Lost</option>
                            <option value="found" <?= $item->status === 'found' ? 'selected' : '' ?>>Found</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        <img
                            src="/assets/images/<?= htmlspecialchars($item->image) ?>"
                            alt="<?= htmlspecialchars($item->title) ?>"
                            style="width: 160px; height: 120px; object-fit: cover; border-radius: 6px;"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">New Image (optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Item</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>