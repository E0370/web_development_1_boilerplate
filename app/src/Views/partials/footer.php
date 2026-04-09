</main>

<footer class="sitefooter mt-5">
    <div class="container py-1">
        <div class="row g-4">

            <div class="col-md-4">
                <section aria-label="About Lost & Found">
                    <h5 class="footertitle mb-3">Lost &amp; Found</h5>
                    <p class="footertext mb-0">
                        Helping people reconnect with lost items quickly and safely.
                    </p>
                </section>
            </div>

            <div class="col-md-4">
                <nav aria-label="Quick Links">
                    <h6 class="footerheading mb-3">Quick Links</h6>
                    <ul class="list-unstyled footerlinks">
                        <li><a href="/">Home</a></li>

                        <?php if (!empty($_SESSION['user'])): ?>
                            <li><a href="/createitem">Create Post</a></li>
                            <li><a href="/myitems">My Posts</a></li>
                            <li><a href="/mymessages">Messages</a></li>

                            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <li><a href="/admin">Admin</a></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li><a href="/login">Login</a></li>
                            <li><a href="/createaccount">Create Account</a></li>
                            <li><a href="/privacy">Privacy Policy</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>

            <div class="col-md-4">
                <section aria-label="Contact Information">
                    <h6 class="footerheading mb-3">Contact</h6>
                    <p class="footertext mb-2">
                        Need help with a lost or found item?
                    </p>
                    <a href="mailto:support@lostfound.com" class="footercontact">
                        support@lostfound.com
                    </a>
                </section>
            </div>

        </div>

        <hr class="footerdivider my-4">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
            <p class="mb-2 mb-md-0 footercopy">
                &copy; <?= date('Y'); ?> Lost &amp; Found. All rights reserved.
            </p>
            <small class="footermade">
                Built with care for the community.
            </small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>