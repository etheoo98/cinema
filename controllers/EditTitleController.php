<?php
require_once (dirname(__DIR__) . '/models/EditTitle.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/EditTitleControllerMiddleware.php');

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
        $this->titleData = $this->editTitleModel->getTitleData();
        $this->renderIndexView();
    }
    public function renderIndexView(): void
    {

        $title = 'Edit Title - ' . $this->titleData['title'];
        $css = ["admin.css"];

        require_once (dirname(__DIR__) . '/views/admin/header.php');
        require_once (dirname(__DIR__) . '/views/admin/edit-title/index.php');
        echo '<script src="/cinema/public/js/edit-title.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');
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