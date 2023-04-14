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

    /**
     * @return void
     *
     * This function handles the front controller request.
     *
     * This function fetches data related to a specific movie with the given ID and renders a php template view.
     *
     */
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

    /**
     * This function handles the rendition of the view.
     *
     * If the request has been determined to by an 'admin', the view will render.
     * The contents of title tag for this specific view is set here the controller, along with
     * what stylesheets apply to this view in particular.
     */
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

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a Switch
     * Statement. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     */
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

    /**
     * @return array|string[]
     *
     * This function adds a booking for a movie by a user and returns a success message if the booking was added
     * successfully, and a failed message if not.
     *
     * Before a new database entry is created, a duplicate check is performed.
     *
     */
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