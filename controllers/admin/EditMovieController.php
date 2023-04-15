<?php
require_once(BASE_PATH . '/models/admin/EditMovie.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/public/scripts/EditMovieControllerMiddleware.php');

class EditMovieController
{
    private mysqli $conn;
    private Session $sessionModel;
    private EditMovie $editMovieModel;
    private ?array $movieData = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->editMovieModel = new EditMovie($this->conn);
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

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/edit-movie/index.php');
        echo '<script src="/cinema/public/js/edit-movie.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
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
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'edit-movie':
                $response = $this->updateMovie();
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action'
                ];
                break;
        }
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
            $sanitizedInput = $this->editMovieModel->sanitizeInput();
            $this->editMovieModel->validateImages();
            $this->editMovieModel->updateMovie($sanitizedInput);
            $response = [
                'status' => 'Success',
                'message' => 'Movie Added Successfully.'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'message' => $e->getMessage()
            ];
        }

        return $response;
    }
}