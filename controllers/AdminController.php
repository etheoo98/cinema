<?php
require_once (dirname(__DIR__) . '/models/Admin.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/AdminControllerMiddleware.php');

class AdminController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Admin $adminModel;
    private bool $sessionIsAdmin;
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->adminModel = new Admin($this->conn);
    }

    /**
     * Handles the admin index page request.
     *
     * This method calls the requireAdminRole method of the Session model to verify that
     * the current user's role is Admin. It then calls the renderIndexView method to
     * render the index view.
     */
    public function index(): void
    {
        # IMPORTANT: Admin role check
        $this->sessionIsAdmin = $this->sessionModel->requireAdminRole();
        $this->renderIndexView();
    }

    /**
     * Renders the admin index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $isAdmin is true, the catalog index view is rendered. Otherwise,
     * the user is redirected.
     */
    private function renderIndexView(): void
    {
        $title = "Admin";
        $css = ["admin.css"];
        require_once (dirname(__DIR__) . '/views/admin/header.php');

        if ($this->sessionIsAdmin) {
            require_once (dirname(__DIR__) . '/views/admin/index.php');
            echo '<script src="/cinema/public/js/admin.js"></script>';
            echo '<script src="/cinema/public/js/add-movie.js"></script>';
        }
        else {
            header('LOCATION: /cinema/');
        }
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');
    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'add-movie':
                $this->addTitle();
                $response = [
                    'status' => 'success',
                    'message' => 'Movie added successfully'
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

    public function addTitle(): void
    {
        try {
            $sanitizedInput = $this->adminModel->sanitizeInput();
            $this->adminModel->titleLookup($sanitizedInput);
            $this->adminModel->validateImage();
            $this->adminModel->addTitle($sanitizedInput);
            echo 'Success maybe';
        } catch(Exception $e) {
            echo 'error exception: ' . $e;
        }
    }
}