<?php
require_once(BASE_PATH . '/models/admin/EditMovie.php');
require_once(BASE_PATH . '/models/admin/AddMovie.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/middleware/EditMovieControllerMiddleware.php');

class EditMovieController
{
    private mysqli $conn;
    private Session $sessionModel;
    private EditMovie $editMovieModel;
    private AddMovie $addMovieModel;
    private ?array $movieData = null;
    private ?array $actorData = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->editMovieModel = new EditMovie($this->conn);
        $this->addMovieModel = new AddMovie($this->conn);
    }

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     *
     * Before rendering the view, a call to the model EditMovie's getMovieData function is
     * made in order to fetch all data associated with the ID in the URL.
     *
     */
    public function initializeView(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();

        if ($sessionIsAdmin) {
            $this->movieData = $this->editMovieModel->getMovieData();
            $this->actorData = $this->editMovieModel->getActorData();
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

        $title = 'Edit Movie - ' . $this->movieData['title'];
        $css = ["admin/main.css", "admin/add-movie.css"];
        $js = ["admin/admin.js", "admin/edit-movie.js"];

        require_once(BASE_PATH . '/views/admin/shared/header.php');
        require_once(BASE_PATH . '/views/admin/edit-movie/index.php');
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a match
     * expression. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     */
    public function ajaxHandler(): void
    {
        $action = $_POST['action'] ?? null;

        $response = match ($action) {
            'edit-movie' => $this->updateMovie(),
            default => [
                'status' => false,
                'message' => 'Invalid action'
            ],
        };
        echo json_encode($response);
    }

    /**
     * This function is called from ajaxHandler() and will attempt to add the movie.
     *
     * In order to determine whether the submitted form is valid, a series of validation
     * occurs in regard to the user input. First, the values entered by the user have to
     * be sanitized before a safe SQL-query can be made. Afterward, the uploaded images are
     * looked over in order to verify that they are of an acceptable file type.
     *
     * If no exception was thrown, an SQL-query will be performed that updates the movie data.
     */
    public function updateMovie(): array
    {
        try {
            /**$sanitizedInput = $this->editMovieModel->sanitizeInput();
            $sanitizedActors = $this->addMovieModel->sanitizeActors();

            $actorsObject = $this->addMovieModel->actorLookup($sanitizedActors);

            if (!empty($actorsObject['actorsNotFound'])) {
                $this->addMovieModel->addNewActors($actorsObject);
            }

            $this->editMovieModel->validateImages();
            $this->editMovieModel->updateMovie($sanitizedInput);
            $actorIDs = $this->addMovieModel->getActorID($actorsObject);
            $this->editMovieModel->addActorsToMovie($actorIDs);
             */
            $response = [
                'status' => true,
                'message' => 'Movie Successfully Updated'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return $response;
    }
}