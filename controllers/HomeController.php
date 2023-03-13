<?php
require_once('./config/dbconnect.php');

class HomeController {
    public function index() {

      require_once ('./views/partials/header.php');
      require_once ('./views/home/index.php');
      require_once ('./views/partials/footer.php');
    }
  }