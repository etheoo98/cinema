<?php
require_once('./config/dbconnect.php');
require_once('./models/Title.php');
require_once('./models/LastSeen.php');

class TitleController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index()
    {
        $this->updateLastSeen();
        $TitleData = $this->getTitleData();
        $RatingData = $this->getRatingData();
        $ActorData = $this->getActorData();

        # TODO: Fix title title
        $title = $this->TitleData['title'];
        $css = ["title.css"];
        require_once('./views/partials/header.php');
        if (isset($TitleData) && isset($RatingData) && isset($ActorData)) {
            require_once('./views/title/index.php');
        } else {
            echo "No data to display";
        }
        require_once('./views/partials/footer.php');

        if (isset($_POST['book'])) {
            $this->addBooking();
        }
    }
    public function getTitleData()
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $TitleData = $model->getTitleData($title_id);
        return $TitleData;
    }
    public function getRatingData()
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $RatingData = $model->getRatingData($title_id);
        return $RatingData;
    }
    public function getActorData()
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $ActorData = $model->getActorData($title_id);
        return $ActorData;
    }
    public function addBooking()
    {
        $user_id = $_SESSION["user_id"];
        $movie_id = $_POST["book"];
        $model = new Title($this->conn);
        $isDuplicate = $model->duplicateCheck($user_id, $movie_id);
        if (!$isDuplicate) {
            $model->addBooking($user_id, $movie_id);
            echo "<script>redirectTo('/cinema/bookings');</script>";
        } else {
            echo "You have already booked this movie.";
        }
    }
    public function updateLastSeen() {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}