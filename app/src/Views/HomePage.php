<?php require __DIR__ . '/partials/header.php'; ?>

<section class="hero">
    <div class="hero-overlay text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Lost Something?</h1>
            <p class="lead">Browse lost and found posts shared by the community.</p>
        </div>
    </div>
</section>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Latest Posts</h2>

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

                            <span class="badge <?= $item->status === 'lost' ? 'bg-danger' : 'bg-success' ?>">
                                <?= htmlspecialchars(ucfirst($item->status)) ?>
                            </span>
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?php
                                $date = new DateTime($item->created_at);
                                echo $date->format('d F');
                                ?>
                            </small>

                            <?php if (isset($_SESSION['user'])) { ?>

                                <?php if ((int)$item->user_id !== (int)$_SESSION['user']['id']) { ?>
                                    <a href="/messages/<?= urlencode($item->id) ?>/<?= urlencode($item->user_id) ?>"
                                       class="btn btn-sm btn-primary">
                                        Claim
                                    </a>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Your Post</span>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="/login" class="btn btn-sm btn-primary">Claim</a>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="text-center">No posts available yet.</p>
    <?php } ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>