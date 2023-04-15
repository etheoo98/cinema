<?php
require_once(BASE_PATH . '/models/admin/BrowseMovies.php');
require_once(BASE_PATH . '/models/Session.php');

class BrowseMoviesController
{
    private mysqli $conn;
    private Session $sessionModel;
    private BrowseMovies $browseMoviesModel;
    private false|mysqli_result $movieData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->browseMoviesModel = new BrowseMovies($this->conn);
    }

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     *
     * A call to the model BrowseMovies' getMovieData function is made in order to fetch all
     * movie data. This function differs from the one used in the Catalog model, as this includes
     * all movies, even those that are not currently screening.
     *
     */
    public function initializeView(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();

        if ($sessionIsAdmin) {
            $this->movieData = $this->browseMoviesModel->getMovieData();
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

        $title = "Browse Titles";
        $css = ["admin/main.css", "admin/browse-movies.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/browse-movies/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }
}