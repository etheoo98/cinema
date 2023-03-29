<?php

class HomeController {
    public function index(): void
    {$css = ["main.css"];
      require_once (dirname(__DIR__) . '/views/shared/header.php');
      require_once (dirname(__DIR__) . '/views/home/index.php');
      require_once (dirname(__DIR__) . '/views/shared/footer.php');
    }
  }