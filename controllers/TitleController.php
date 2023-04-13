<?php
require_once (BASE_PATH . '/models/Title.php');
require_once (BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/public/scripts/TitleControllerMiddleware.php');

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
    }

    private function renderIndexView(): void
    {
        $title = $this->titleData['title'];
        $css = ["main.css", "title.css"];

        require_once(BASE_PATH . '/views/shared/header.php');

        if(isset($this->titleData) && isset($this->ratingData) && isset($this->actorData)) {
            require_once(BASE_PATH . '/views/title/index.php');
        }
        else {
            require_once(BASE_PATH . '/views/shared/error.php');
        }
        echo '<script src="/cinema/public/js/add-booking.js"></script>';
        require_once(BASE_PATH . '/views/shared/footer.php');

    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'add-booking':
                $response = $this->addBooking();
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action'
                ];
                break;
        }
        echo json_encode($response);
    }

    public function addBooking(): array
    {
        try {
            $user_id = $_SESSION["user_id"];
            $movie_id = mysqli_real_escape_string($this->conn, $_POST['movie_id']);
            $this->titleModel->duplicateCheck($user_id, $movie_id);
            $this->titleModel->addBooking($user_id, $movie_id);
            $response = [
                'status' => 'Success',
                'message' => 'Booking Successfully Added.'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }
}