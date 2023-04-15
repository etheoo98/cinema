<?php
require_once(BASE_PATH . '/models/admin/Admin.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/public/scripts/AdminControllerMiddleware.php');

class AdminController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Admin $adminModel;

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
     *
     */
    public function initializeView(): void
    {
        # IMPORTANT: Admin role check
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();
        if ($sessionIsAdmin) {
            $this->renderView();
        } else {
            header("LOCATION: /cinema/sign-in");
        }
    }

    /**
     * Renders the admin index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $isAdmin is true, the catalog index view is rendered. Otherwise,
     * the user is redirected.
     */
    private function renderView(): void
    {
        $title = "Admin";
        $css = ["admin/main.css"];

        require_once(BASE_PATH . '/views/admin/header.php');

        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-movie.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }
}