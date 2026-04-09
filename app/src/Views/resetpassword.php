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

      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="text-center mb-4">Reset Password</h3>

          <form method="POST" action="/resetpassword" id="resetForm">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

            <div class="mb-3">
              <label class="form-label">New Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password" name="password" required>

              <div class="mt-2">
                <div id="rule-length">Minimum 8 characters</div>
                <div id="rule-uppercase">At least one uppercase letter</div>
                <div id="rule-lowercase">At least one lowercase letter</div>
                <div id="rule-number">At least one number</div>
                <div id="rule-special">At least one special character</div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
              <small id="confirmFeedback" class="text-danger"></small>
            </div>

            <button class="btn btn-primary w-100" id="resetBtn" type="submit" disabled>
              Reset Password
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/resetpassword.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>