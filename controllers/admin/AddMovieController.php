<?php
require_once(BASE_PATH . '/models/admin/AddMovie.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/middleware/AddMovieControllerMiddleware.php');

class AddMovieController
{
    private mysqli $conn;
    private Session $sessionModel;
    private AddMovie $addMovieModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
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
     *
     */
    public function renderView(): void
    {

        $title = "Add Movie";
        $css = ["admin/main.css", "admin/add-movie.css"];
        $js = ["admin/admin.js", "admin/add-movie.js"];

        require_once(BASE_PATH . '/views/admin/shared/header.php');
        require_once(BASE_PATH . '/views/admin/add-movie/index.php');
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
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        $response = match ($action) {
            'add-movie' => $this->addMovie(),
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
     * looked over, to control that they are of an acceptable file type.
     *
     * If the input validation is passed, both the movie and its actors are fetched in order
     * to determine duplicate entries. The actor names that are determined to not be stored
     * in the database, are seperated from the actor names that were found, in order to create new
     * entries of those interpreted as 'not found' actors.
     *
     * Finally, the new movie entry is created, and the actor ids are to be associated with the movie
     * through a link-table. If no error occurred within any of the methods, a successful response
     * will be sent back to the AJAX request.
     */
    public function addMovie(): array
    {
        try {
            $sanitizedInput = $this->addMovieModel->sanitizeInput();
            $sanitizedActors = $this->addMovieModel->sanitizeActors();
            $this->addMovieModel->validateImage();

            $this->addMovieModel->movieLookup($sanitizedInput);
            $actorsObject = $this->addMovieModel->actorLookup($sanitizedActors);

            if (!empty($actorsObject['actorsNotFound'])) {
                $this->addMovieModel->addNewActors($actorsObject);
            }

            $movie_id = $this->addMovieModel->addMovie($sanitizedInput);
            $actorIDs = $this->addMovieModel->getActorID($actorsObject);
            $this->addMovieModel->addActorsToMovie($movie_id, $actorIDs);

            $response = [
                'status' => true,
                'message' => 'Movie Added Successfully.'
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