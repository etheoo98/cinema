<?php
require_once (dirname(__DIR__) . '/models/Admin.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/AdminControllerMiddleware.php');

class AdminController
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index(): void
    {
        $model = new Session($this->conn);
        $isAdmin = $model->requireAdminRole();

        $title = "Admin";
        $css = ["admin.css"];
        require_once (dirname(__DIR__) . '/views/admin/header.php');

        if ($isAdmin) {
            require_once (dirname(__DIR__) . '/views/admin/index.php');
        }
        else {
            require_once (dirname(__DIR__) . '/views/error/index.php');
        }
        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-movie.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');

    }
    public function ajaxHandler(): void
    {
        $action = $_POST['action'] ?? null;

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
        echo 'reached ajaxHandler';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        try {
            $model = new Admin($this->conn);
            $sanitizedInput = $model->sanitizeInput();
            $model->titleLookup($sanitizedInput);
            $model->validateImage();
            $model->addTitle($sanitizedInput);
            echo 'Success maybe';
        } catch(Exception $e) {
            echo 'error exception: ' . $e;
        }
    }

}