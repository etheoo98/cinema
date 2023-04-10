<?php
require_once (dirname(__DIR__) . '/models/Admin.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/AdminControllerMiddleware.php');

class AddTitleController
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    
    public function index(): void
    {
       $this->renderIndexView();
    }
    public function renderIndexView(): void
    {

        $title = "Add Title";
        $css = ["admin.css"];

        require_once (dirname(__DIR__) . '/views/admin/header.php');
        require_once (dirname(__DIR__) . '/views/admin/add-title/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-title.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');
    }
}