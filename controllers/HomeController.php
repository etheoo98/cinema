<?php

class HomeController {
    public function index(): void
    {
      require_once ('./views/partials/header.php');
      require_once ('./views/home/index.php');
      require_once ('./views/partials/footer.php');
    }
  }