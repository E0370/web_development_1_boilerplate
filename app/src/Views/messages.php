<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Item: <?= htmlspecialchars($item->title) ?></h4>
                    <small class="text-muted">Chat with: <?= htmlspecialchars($otherUsername) ?></small>
                </div>

                <div class="card-body chat-box">

                    <?php if(isset($_SESSION['error_message'])){ ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error_message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php } ?>

                    <?php if(!empty($messages)){ ?>
                        <?php foreach($messages as $msg){ ?>
                            <?php $isMine = ((int)$msg['sender_id'] === (int)$_SESSION['user']['id']); ?>

                            <div class="d-flex mb-3 <?= $isMine ? 'justify-content-end' : 'justify-content-start' ?>">
                                <div class="message-bubble <?= $isMine ? 'my-message' : 'other-message' ?>">
                                    <div class="message-text">
                                        <?= htmlspecialchars($msg['message']) ?>
                                    </div>
                                    <div class="message-time">
                                        <?php
                                        $date = new DateTime($msg['created_at']);
                                        echo $date->format('d F H:i');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted text-center mb-0">No messages yet. Start the conversation.</p>
                    <?php } ?>

                </div>

                <div class="card-footer">
                    <form method="POST" action="/send-message">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item->id) ?>">
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">

                        <div class="input-group">
                            <textarea
                                name="message"
                                class="form-control"
                                rows="2"
                                placeholder="Type your message..."
                                required
                            ></textarea>

                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>