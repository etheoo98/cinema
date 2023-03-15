<?php
require_once('./models/Title.php');
require_once('./models/LastSeen.php');

class TitleController
{
    private mysqli $conn;
    private ?array $titleData = null;
    private ?array $ratingData = null;
    private ?array $actorData = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index(): void
    {
        $this->updateLastSeen();
        $this->getTitleData();
        $this->getRatingData();
        $this->getActorData();

        # TODO: Fix title title
        $title = $this->titleData['title'];
        $css = ["title.css"];
        require_once('./views/partials/header.php');
        if(isset($this->titleData) && isset($this->ratingData) && isset($this->actorData)) {
            require_once('./views/title/index.php');
        }
        else {
            require_once('./views/error/index.php');
        }
        require_once('./views/partials/footer.php');

        if (isset($_POST['book'])) {
            $this->addBooking();
        }
    }
    public function getTitleData(): void
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $this->titleData = $model->getTitleData($title_id);
    }
    public function getRatingData(): void
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $this->ratingData = $model->getRatingData($title_id);
    }
    public function getActorData(): void
    {
        $title_id = $_GET["id"];
        $model = new Title($this->conn);
        $this->actorData = $model->getActorData($title_id);
    }
    public function addBooking(): void
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
    public function updateLastSeen(): void
    {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}