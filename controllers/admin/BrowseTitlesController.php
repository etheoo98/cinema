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

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     *
     * A call to the model BrowseTitles' getTitleData function is made in order to fetch all
     * movie data. This function differs from the one used in the Catalog model, as this includes
     * all movies, even those that are not currently screening.
     *
     */
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

    /**
     * This function handles the rendition of the view.
     *
     * If the request has been determined to by an 'admin', the view will render.
     * The contents of title tag for this specific view is set here the controller, along with
     * what stylesheets apply to this view in particular.
     */
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