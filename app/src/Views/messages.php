<?php 
/** @var \App\Models\Items $item */
require __DIR__ . '/partials/header.php'; 
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Item: <?= htmlspecialchars($item->title) ?></h4>
                    <small class="text-muted">Chat with: <?= htmlspecialchars($otherUsername) ?></small>
                </div>

                <section class="card-body chatbox" aria-label="Chat Messages" role="log">

                    <?php if (isset($_SESSION['error_message'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error_message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php } ?>

                    <?php if (!empty($messages)) { ?>
                        <?php foreach ($messages as $msg) { ?>
                            <?php $isMine = ((int) $msg['sender_id'] === (int) $_SESSION['user']['id']); ?>

                            <article class="d-flex mb-3 <?= $isMine ? 'justify-content-end' : 'justify-content-start' ?>" role="listitem">
                                <div class="messagebubble <?= $isMine ? 'mymessage' : 'othermessage' ?>">
                                    <div class="messagetext">
                                        <?= htmlspecialchars($msg['message']) ?>
                                    </div>
                                    <div class="messagetime">
                                        <?php
                                        $date = new DateTime($msg['created_at']);
                                        echo $date->format('d F H:i');
                                        ?>
                                    </div>
                                </div>
                            </article>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted text-center mb-0">No messages yet. Start the conversation.</p>
                    <?php } ?>

                </section>

                <div class="card-footer">
                    <form id="messageForm">
                        <input type="hidden" name="item_id" id="itemId" value="<?= htmlspecialchars($item->id) ?>">
                        <input type="hidden" name="receiver_id" id="receiverId" value="<?= htmlspecialchars($receiverId) ?>">

                        <div class="input-group">
                            <textarea name="message" id="messageInput" class="form-control" rows="2"
                                placeholder="Type your message..." required></textarea>

                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>

                        <div id="messageError" class="text-danger small mt-2"></div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="/assets/js/messages.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>