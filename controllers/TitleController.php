<?php
require_once (dirname(__DIR__) . '/models/Title.php');
require_once (dirname(__DIR__) . '/models/Session.php');

class TitleController
{
    private mysqli $conn;
    private Title $titleModel;
    private Session $sessionModel;
    private ?array $titleData = null;
    private ?array $ratingData = null;
    private ?array $actorData = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->titleModel = new Title($this->conn);
        $this->sessionModel = new Session($this->conn);
    }

    public function index(): void
    {
        $title_id = mysqli_real_escape_string($this->conn, $_GET['id']);

        if ($title_id) {
            $this->sessionModel->updateLastSeen();

            $this->titleData = $this->titleModel->getTitleData($title_id);
            $this->ratingData = $this->titleModel->getRatingData($title_id);
            $this->actorData = $this->titleModel->getActorData($title_id);

            $this->renderIndexView();
        }
        else {
            header ('LOCATION: /cinema/catalog');
        }
    }

    private function renderIndexView(): void
    {
        $title = $this->titleData['title'];
        $css = ["main.css", "title.css"];

        require_once('./views/shared/header.php');

        if(isset($this->titleData) && isset($this->ratingData) && isset($this->actorData)) {
            require_once('./views/title/index.php');
        }
        else {
            require_once('./views/shared/error.php');
        }

        require_once('./views/shared/footer.php');

        if (isset($_POST['book'])) {
            $this->addBooking();
        }
    }

    public function addBooking(): void
    {
        $user_id = $_SESSION["user_id"];
        $movie_id = mysqli_real_escape_string($this->conn, $_POST['book']);

        $isDuplicate = $this->titleModel->duplicateCheck($user_id, $movie_id);

        if (!$isDuplicate) {
            $this->titleModel->addBooking($user_id, $movie_id);
            echo "<script>redirectTo('/cinema/bookings');</script>";
        } else {
            echo "You have already booked this movie.";
        }
    }
}