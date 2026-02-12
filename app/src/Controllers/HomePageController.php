<?php

namespace App\Controllers;

class HomePageController
{
    public function showhome($vars = [])
    {
       require __DIR__ . '/../Views/HomePage.php';
    }
}
