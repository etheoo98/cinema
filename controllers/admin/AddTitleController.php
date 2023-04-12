<?php
require_once(BASE_PATH . '/models/AddTitle.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/public/scripts/AddTitleControllerMiddleware.php');

class AddTitleController
{
    private mysqli $conn;
    private Session $sessionModel;
    private AddTitle $addTitleModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->addTitleModel = new AddTitle($this->conn);
    }
    
    public function index(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->renderIndexView();
        } else {
            # TODO: Fix .htaccess as to not require absolute path
            header("LOCATION: http://localhost/cinema/sign-in");
        }
    }
    public function renderIndexView(): void
    {

        $title = "Add Title";
        $css = ["admin/main.css", "admin/add-title.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/add-title/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-title.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'add-movie':
                $response = $this->addTitle();
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

    public function addTitle(): array
    {
        try {
            $sanitizedInput = $this->addTitleModel->sanitizeInput();
            $sanitizedActors = $this->addTitleModel->sanitizeActors();
            $this->addTitleModel->validateImage();
            $this->addTitleModel->titleLookup($sanitizedInput);
            $actorsObject = $this->addTitleModel->actorLookup($sanitizedActors);

            if (!empty($actorsObject['actorsNotFound'])) {
                $this->addTitleModel->addNewActors($actorsObject);
            }

            $movie_id = $this->addTitleModel->addTitle($sanitizedInput);
            $actorIDs = $this->addTitleModel->getActorID($actorsObject);
            $this->addTitleModel->addActorsToTitle($movie_id, $actorIDs);

            $response = [
                'status' => 'Success',
                'message' => 'Movie Added Successfully.'
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