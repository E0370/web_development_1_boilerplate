<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container page-section">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">

            <?php if (isset($_SESSION['invalid_credentials'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['invalid_credentials']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['invalid_credentials']); ?>
            <?php } ?>

            <?php if (isset($_SESSION['account_created'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['account_created']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['account_created']); ?>
            <?php } ?>

            <?php if (isset($_SESSION['confirm_logout'])) { ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['confirm_logout']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['confirm_logout']); ?>
            <?php } ?>

            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="text-center mb-4">Login</h3>

                    <form method="POST" action="/login">

                        <div class="mb-3">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>

                            <div class="password-block position-relative">
                                <input type="password" class="form-control" id="password" name="password" required>

                                <div id="togglePassword"
                                    style="cursor:pointer; position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                                    Show
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <small>Don't have an account?</small>
                            <a href="/createaccount">Create account</a>
                        </div>
                        <div>
                            <a href="/forgotpassword">Forgot Password?</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");

    toggle.addEventListener("click", function () {
        if (password.type === "password") {
            password.type = "text";
            toggle.textContent = "Hide";
        } else {
            password.type = "password";
            toggle.textContent = "Show";
        }
    });
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>