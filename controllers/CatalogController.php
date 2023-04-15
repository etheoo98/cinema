<?php
require_once (BASE_PATH . '/models/Catalog.php');
require_once (BASE_PATH . '/models/Session.php');

class CatalogController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Catalog $catalogModel;
    private false|mysqli_result $movieData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->catalogModel = new Catalog($this->conn);
    }

    /**
     * Handles the catalog index page request.
     *
     * This method calls the updateLastSeen method of the sessionModel to record the
     * current user's last seen time. It then retrieves the movie data using the
     * catalogModel's getMovieData method and assigns it to $this->movieData. Finally,
     * the renderIndexView method is called to render the index view.
     *
     */
    public function initializeView(): void
    {
        $this->sessionModel->updateLastSeen();
        $this->movieData = $this->catalogModel->getMovieData();
        $this->renderView();
    }

    /**
     * Renders the catalog index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $this->movieData is not NULL, the catalog index view is rendered. Otherwise,
     * an error page is rendered.
     */
    private function renderView(): void
    {
        $title = "Catalog";
        $css = ["main.css", "catalog.css"];

        require_once(BASE_PATH . '/views/shared/header.php');

        if (isset($this->movieData)) {
            require_once(BASE_PATH . '/views/catalog/index.php');
        }
        else {
            require_once(BASE_PATH . '/views/shared/error.php');
        }

        require_once(BASE_PATH . '/views/shared/footer.php');
    }
}
