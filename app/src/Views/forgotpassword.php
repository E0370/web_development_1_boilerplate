<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container page-section">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">

      <?php if (isset($_SESSION['reset_error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['reset_error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['reset_error']); ?>
      <?php } ?>

      <?php if (isset($_SESSION['password_reset_message'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['password_reset_message']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['password_reset_message']); ?>
      <?php } ?>

      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="text-center mb-4">Forgot Password</h3>

          <form method="POST" action="/forgotpassword" id="forgotForm">
            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
              <small id="emailFeedback" class="text-danger"></small>
            </div>

            <button class="btn btn-primary w-100" id="sendBtn" type="submit" disabled>
              Send reset link
            </button>
          </form>

          <div class="text-center mt-3">
            <small><a href="/login">Back to login</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/forgotpassword.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>