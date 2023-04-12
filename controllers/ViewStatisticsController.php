<?php
require_once (dirname(__DIR__) . '/models/ViewStatistics.php');
require_once (dirname(__DIR__) . '/models/Session.php');
require_once (dirname(__DIR__) . '/public/scripts/ViewStatisticsControllerMiddleware.php');

class ViewStatisticsController
{
    private mysqli $conn;
    private ViewStatistics $viewStatisticsModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->viewStatisticsModel = new ViewStatistics($this->conn);
    }
    
    public function index(): void
    {
        $this->renderIndexView();
    }
    public function renderIndexView(): void
    {

        $title = "View Statistics";
        $css = ["admin.css"];

        require_once (dirname(__DIR__) . '/views/admin/header.php');
        require_once (dirname(__DIR__) . '/views/admin/view-statistics/index.php');
        echo '<script src="/cinema/public/js/view-statistics.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');
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