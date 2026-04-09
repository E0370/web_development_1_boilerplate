<?php

namespace App\Controllers;

abstract class BaseController
{
    protected function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }

    protected function requireAdmin()
    {
        if (
            !isset($_SESSION['user']) ||
            !isset($_SESSION['user']['role']) ||
            $_SESSION['user']['role'] !== 'admin'
        ) {
            header('Location: /login');
            exit();
        }
    }
}
