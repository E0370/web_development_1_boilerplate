<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">My Messages</h3>

    <?php if(isset($_SESSION['error_message'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>

    <?php if(!empty($conversations)){ ?>
        <div class="list-group">
            <?php foreach($conversations as $conversation){ ?>
                <a href="/messages/<?= urlencode($conversation['item_id']) ?>/<?= urlencode($conversation['other_user_id']) ?>"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3">

                    <img src="/assets/images/<?= htmlspecialchars($conversation['item_image']) ?>"
                         alt="<?= htmlspecialchars($conversation['item_title']) ?>"
                         style="width:70px; height:70px; object-fit:cover; border-radius:8px;">

                    <div class="flex-grow-1">
                        <div class="fw-bold">
                            <?= htmlspecialchars($conversation['item_title']) ?>
                        </div>

                        <div class="small text-muted">
                            User: <?= htmlspecialchars($conversation['other_username']) ?>
                        </div>

                        <div>
                            <?= htmlspecialchars($conversation['message']) ?>
                        </div>
                    </div>

                    <small class="text-muted">
                        <?php
                        $date = new DateTime($conversation['created_at']);
                        echo $date->format('d F H:i');
                        ?>
                    </small>
                </a>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="text-muted">No conversations yet</p>
    <?php } ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>