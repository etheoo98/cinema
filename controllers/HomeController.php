<?php
require_once (BASE_PATH . '/models/Home.php');
class HomeController {
    private mysqli $conn;
    private Home $homeModel;
    private false|mysqli_result $movieData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->homeModel = new Home($this->conn);
    }

    public function initializeView(): void {
        $this->movieData = $this->homeModel->getMovieData();
        $this->renderView();
    }
    public function renderView(): void
    {
        $title = 'Home';
        $css = ["main.css", "home.css"];
        require_once (BASE_PATH . '/views/shared/header.php');

        if($this->movieData) {
            require_once (BASE_PATH . '/views/home/index.php');
        } else {
            require_once(BASE_PATH . '/views/shared/error.php');
        }

        echo '<script src="/cinema/public/js/home.js"></script>';

        require_once (BASE_PATH . '/views/shared/footer.php');
    }
}