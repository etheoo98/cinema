<?php
require_once(BASE_PATH . '/models/BrowseTitles.php');
require_once(BASE_PATH . '/models/Session.php');

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
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->titleData = $this->browseTitlesModel->getTitleData();
            $this->renderIndexView();
        } else {
            header("LOCATION: http://localhost/cinema/sign-in");
        }

    }
    public function renderIndexView(): void
    {

        $title = "Browse Titles";
        $css = ["admin/main.css", "admin/browse-titles.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/browse-titles/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }
}