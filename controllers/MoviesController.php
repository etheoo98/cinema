<?php
require_once('./config/dbconnect.php');
require_once('./models/Movies.php');
require_once('./models/LastSeen.php');

class MoviesController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index()
    {
        $this->updateLastSeen();
        $movieData = $this->getMovieData();

        $title = "Movies";
        $css = ["movies.css"];
        require_once('./views/partials/header.php');

        if (isset($movieData)) {
            require_once('./views/movies/index.php');
        }
        else {
            require_once('./views/partials/error-page.php');
        }

        require_once('./views/partials/footer.php');
    }
    public function getMovieData()
    {
        $model = new Movies($this->conn);
        return $model->getMovieData();
    }
    public function updateLastSeen() {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}
