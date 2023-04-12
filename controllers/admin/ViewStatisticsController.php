<?php
require_once(BASE_PATH . '/models/ViewStatistics.php');
require_once(BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/public/scripts/ViewStatisticsControllerMiddleware.php');

class ViewStatisticsController
{
    private mysqli $conn;
    private Session $sessionModel;
    private ViewStatistics $viewStatisticsModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->viewStatisticsModel = new ViewStatistics($this->conn);
    }
    
    public function index(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->renderIndexView();
        } else {
            header("LOCATION: http://localhost/cinema/sign-in");
        }

    }
    public function renderIndexView(): void
    {

        $title = "View Statistics";
        $css = ["admin/main.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/view-statistics/index.php');
        echo '<script src="/cinema/public/js/view-statistics.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }
    public function ajaxHandler(): void
    {
        header('Content-Type: application/json');

        $totalUsers = $this->viewStatisticsModel->getTotalUsers();
        $data = array();
        while ($row = $totalUsers->fetch_assoc()) {
            $data[] = $row['registration_date'];
        }
        echo json_encode($data);
    }

}