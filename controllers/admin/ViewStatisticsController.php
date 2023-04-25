<?php
require_once(BASE_PATH . '/models/admin/ViewStatistics.php');
require_once(BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/middleware/ViewStatisticsControllerMiddleware.php');

class ViewStatisticsController
{
    /**
     * This class is not finished.
     *
     * It is only possible to see a graph showing the amount of registered users over
     * each passing month. Work in progress, this class is far from being finalized.
     *
     */
    private mysqli $conn;
    private Session $sessionModel;
    private ViewStatistics $viewStatisticsModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->viewStatisticsModel = new ViewStatistics($this->conn);
    }

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     *
     */
    public function initializeView(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->renderView();
        } else {
            header("LOCATION: /cinema/sign-in");
        }

    }

    /**
     * This function handles the rendition of the view.
     *
     * If the request has been determined to by an 'admin', the view will render.
     * The contents of movie tag for this specific view is set here the controller, along with
     * what stylesheets apply to this view in particular.
     */
    public function renderView(): void
    {

        $title = "View Statistics";
        $css = ["admin/main.css"];
        $js = ["admin/admin.js", "admin/view-statistics.js"];

        require_once(BASE_PATH . '/views/admin/shared/header.php');
        require_once(BASE_PATH . '/views/admin/view-statistics/index.php');
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a Switch
     * Statement. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     */
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