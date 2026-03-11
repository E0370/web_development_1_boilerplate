<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="/">Lost &amp; Found</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === '/') ? 'active' : '' ?>" href="/">
                        Home
                    </a>
                </li>

                <?php if (!empty($_SESSION['user'])): ?>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === '/createitem') ? 'active' : '' ?>" href="/createitem">
                            Create Item
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === '/myitems') ? 'active' : '' ?>" href="/myitems">
                            My Items
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === '/my-messages') ? 'active' : '' ?>" href="/my-messages">
                            Messages
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === '/admin') ? 'active' : '' ?>" href="/admin">
                                Admin
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>

            </ul>

            <ul class="navbar-nav ms-auto align-items-center">

                <?php if (!empty($_SESSION['user'])): ?>

                    <li class="nav-item me-3">
                        <span class="navbar-text text-light">
                            Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="/logout" class="d-inline">
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>

                <?php else: ?>

                    <li class="nav-item me-2">
                        <a class="btn btn-outline-light btn-sm" href="/login">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="/create-account">
                            Create Account
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>

<main class="container my-4">