<?php

class HomeController {
    public function renderView(): void
    {$css = ["main.css"];
      require_once (BASE_PATH . '/views/shared/header.php');
      require_once (BASE_PATH . '/views/home/index.php');
      require_once (BASE_PATH . '/views/shared/footer.php');
    }
  }