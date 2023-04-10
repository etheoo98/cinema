<?php
require_once (dirname(__DIR__) . '/models/Admin.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/AdminControllerMiddleware.php');

class AdminController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Admin $adminModel;
    private Catalog $catalogModel;
    private bool $sessionIsAdmin;
    private string $view;
    private false|mysqli_result $titleData;
    private false|mysqli_result $editTitleData;

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
        $this->titleData = $this->adminModel->getTitleData();
        $this->editTitleData = $this->adminModel->getEditTitleData();
        $this->getSubView();
        if ($this->sessionIsAdmin) {
            $this->renderIndexView();
        } else {
            header('LOCATION: /cinema/');
        }
    }

    public function getSubView(): void {
        $this->view = isset($_GET['view']) ? mysqli_real_escape_string($this->conn, $_GET['view']) : null;
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

        switch ($this->view) {
            case 'add-movie':
                require_once (dirname(__DIR__) . '/views/admin/index.php');
                break;
            case 'browse-titles':
                require_once (dirname(__DIR__) . '/views/admin/index.php');
                break;
            case 'edit-title':
                require_once (dirname(__DIR__) . '/views/admin/index1.php');
                break;
            case 'edit-movie':
                require_once (dirname(__DIR__) . '/views/admin/edit-movie.php');
                break;
            case 'add-actors':
                require_once (dirname(__DIR__) . '/views/admin/add-actors.php');
                break;
            case 'manage-roles':
                require_once (dirname(__DIR__) . '/views/admin/manage-roles.php');
                break;
            case 'view-stats':
                require_once (dirname(__DIR__) . '/views/admin/view-stats.php');
                break;
            default:
                require_once (dirname(__DIR__) . '/views/admin/index1.php');
                break;
        }

        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-title.js"></script>';
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
            case 'browse-movies':

                $response = [
                    'status' => 'success',
                    'message' => 'Successfully fetched title data',
                ];

                break;
            case 'load-title-data':
                    $requestedTitleData = $this->adminModel->getRequestedTitleData();

                    $response = [
                        'status' => 'success',
                        'message' => 'Successfully fetched title data',
                        'data' => $requestedTitleData
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