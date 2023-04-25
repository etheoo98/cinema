<?php
require_once (BASE_PATH . '/models/Catalog.php');
require_once (BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/middleware/CatalogControllerMiddleware.php');

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

        echo '<script src="/cinema/public/js/catalog.js"></script>';

        require_once(BASE_PATH . '/views/shared/footer.php');
    }

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a Match
     * Expression. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     */
    public function ajaxHandler(): void
    {
        $action = $_POST['action'] ?? null;
        $response = match ($action) {
            'search' => $this->getMovieData(),
            default => [
                'status' => false,
                'message' => 'Invalid action'
            ],
        };
        header("Content-Type: application/json");
        echo json_encode($response);
    }

    public function getMovieData(): array
    {
        $movieData = $this->catalogModel->searchMovies();
        $movies = [];
        foreach ($movieData as $movie) {
            $movies[] = [
                'poster' => $movie['poster'],
                'title' => $movie['title'],
                'genre' => $movie['genre'],
                'age_limit' => $movie['age_limit'],
                'length' => $movie['length'],
                'movie_id' => $movie['movie_id']
            ];
        }
        $response = [
            'status' => true,
            'data' => $movies
        ];
        return $response;
    }
}
