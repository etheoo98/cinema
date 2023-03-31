<?php
require_once (dirname(__DIR__) . '/models/Catalog.php');
require_once (dirname(__DIR__) . '/models/Session.php');

class CatalogController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Catalog $catalogModel;
    private false|mysqli_result $titleData;

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
     * current user's last seen time. It then retrieves the title data using the
     * catalogModel's getTitleData method and assigns it to $this->titleData. Finally,
     * the renderIndexView method is called to render the index view.
     */
    public function index(): void
    {
        $this->sessionModel->updateLastSeen();
        $this->titleData = $this->catalogModel->getTitleData();
        $this->renderIndexView();
    }

    /**
     * Renders the catalog index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $this->titleData is not NULL, the catalog index view is rendered. Otherwise,
     * an error page is rendered.
     */
    private function renderIndexView(): void
    {
        $title = "Catalog";
        $css = ["main.css", "catalog.css"];

        require_once(dirname(__DIR__) . '/views/shared/header.php');

        if (isset($this->titleData)) {
            require_once(dirname(__DIR__) . '/views/catalog/index.php');
        }
        else {
            require_once(dirname(__DIR__) . '/views/shared/error.php');
        }

        require_once(dirname(__DIR__) . '/views/shared/footer.php');
    }
}
