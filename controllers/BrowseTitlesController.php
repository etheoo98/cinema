<?php
require_once (dirname(__DIR__) . '/models/BrowseTitles.php');
require_once (dirname(__DIR__) . '/models/Session.php');

class BrowseTitlesController
{
    private mysqli $conn;
    private Session $sessionModel;
    private BrowseTitles $browseTitlesModel;

    private false|mysqli_result $titleData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->browseTitlesModel = new BrowseTitles($this->conn);
    }

    public function index(): void
    {
        $this->titleData = $this->browseTitlesModel->getTitleData();
        $this->renderIndexView();
    }
    public function renderIndexView(): void
    {

        $title = "Browse Titles";
        $css = ["admin.css"];

        require_once (dirname(__DIR__) . '/views/admin/header.php');
        require_once (dirname(__DIR__) . '/views/admin/browse-titles/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');
    }
}