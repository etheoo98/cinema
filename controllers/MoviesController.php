<?php
require_once('./models/Movies.php');
require_once('./models/LastSeen.php');

class MoviesController
{
    private $conn;
    private $movieData;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index(): void
    {
        $this->updateLastSeen();
        $this->getMovieData();

        $title = "Movies";
        $css = ["movies.css"];
        require_once('./views/partials/header.php');

        if (isset($this->movieData)) {
            require_once('./views/movies/index.php');
        }
        else {
            require_once('./views/error/index.php');
        }

        require_once('./views/partials/footer.php');
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
