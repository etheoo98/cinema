<?php
require_once(BASE_PATH . '/models/EditTitle.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/public/scripts/EditTitleControllerMiddleware.php');

class EditTitleController
{
    private mysqli $conn;
    private Session $sessionModel;
    private EditTitle $editTitleModel;
    private ?array $titleData = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->editTitleModel = new EditTitle($this->conn);
    }

    public function index(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->titleData = $this->editTitleModel->getTitleData();
            $this->renderIndexView();
        } else {
            header("LOCATION: http://localhost/cinema/sign-in");
        }

    }
    public function renderIndexView(): void
    {

        $title = 'Edit Title - ' . $this->titleData['title'];
        $css = ["admin/main.css", "admin/add-title.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/edit-title/index.php');
        echo '<script src="/cinema/public/js/edit-title.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }
    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;
        switch ($action) {
            case 'edit-title':
                $this->updateTitle();
                $response = [
                    'status' => 'success',
                    'message' => 'Hello from update-movie'
                ];
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
    public function updateTitle() {
        $sanitizedInput = $this->editTitleModel->sanitizeInput();
        $this->editTitleModel->validateImages();
        $this->editTitleModel->updateTitle($sanitizedInput);
    }
}