<?php
require_once('./models/Movies.php');
require_once('./models/LastSeen.php');

class CatalogController
{
    private mysqli $conn;
    private false|mysqli_result $movieData;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index(): void
    {
        $this->updateLastSeen();
        $this->getMovieData();

        $title = "Movies";
        $css = ["main.css", "catalog.css"];
        require_once(dirname(__DIR__) . '/views/shared/header.php');

        if (isset($this->movieData)) {
            require_once(dirname(__DIR__) . '/views/catalog/index.php');
        }
        else {
            require_once(dirname(__DIR__) . '/views/error/index.php');
        }

        require_once(dirname(__DIR__) . '/views/shared/footer.php');
    }
    public function getMovieData(): void
    {
        $model = new Movies($this->conn);
        $this->movieData = $model->getMovieData();
    }
    public function updateLastSeen(): void
    {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}
