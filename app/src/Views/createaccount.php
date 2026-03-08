<?php require __DIR__ . '/partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <?php if(isset($_SESSION['username_email_validate'])){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['username_email_validate']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['username_email_validate']); ?>
        <?php } ?>

        <div class="card shadow-sm">
            <div class="card-body">

                <h3 class="text-center mb-4">Create Account</h3>

                <form method="POST"
                      action="/create-account"
                      class="needs-validation"
                      novalidate>

                    <div class="mb-3">
                        <label for="firstname" class="form-label">
                            First Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="firstname"
                               name="firstname"
                               value="<?= htmlspecialchars($user->firstname ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">
                            Last Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="lastname"
                               name="lastname"
                               value="<?= htmlspecialchars($user->lastname ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               value="<?= htmlspecialchars($user->username ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?= htmlspecialchars($user->email ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <div class="password-block">
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   required>
                        </div>

                        <div id="rule-length">Minimum 8 characters</div>
                        <div id="rule-uppercase">At least one uppercase letter</div>
                        <div id="rule-lowercase">At least one lowercase letter</div>
                        <div id="rule-number">At least one number</div>
                        <div id="rule-special">At least one special character</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">
                            Confirm Password <span class="text-danger">*</span>
                        </label>

                        <input type="password"
                               class="form-control"
                               id="confirm-password"
                               name="confirmPassword"
                               required>

                        <span id="confirmpassword-feedback"
                              class="text-danger small"></span>
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-100"
                            id="submitBtn"
                            disabled>
                        Create Account
                    </button>

                </form>

                <div class="text-center mt-3">
                    <small>
                        Already have an account?
                        <a href="/login">Login</a>
                    </small>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="/assets/js/createaccount.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>