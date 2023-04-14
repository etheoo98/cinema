<?php
require_once(BASE_PATH . '/models/AddTitle.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/public/scripts/AddTitleControllerMiddleware.php');

class AddTitleController
{
    private mysqli $conn;
    private Session $sessionModel;
    private AddTitle $addTitleModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->addTitleModel = new AddTitle($this->conn);
    }

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     */
    public function index(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();

        if ($sessionIsAdmin) {
            $this->renderIndexView();
        } else {
            # TODO: Fix .htaccess as to not require absolute path
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

        $title = "Add Title";
        $css = ["admin/main.css", "admin/add-title.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/add-title/index.php');
        echo '<script src="/cinema/public/js/admin.js"></script>';
        echo '<script src="/cinema/public/js/add-title.js"></script>';
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
            case 'add-movie':
                $response = $this->addTitle();
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
    public function addTitle(): array
    {
        try {
            $sanitizedInput = $this->addTitleModel->sanitizeInput();
            $sanitizedActors = $this->addTitleModel->sanitizeActors();
            $this->addTitleModel->validateImage();

            $this->addTitleModel->titleLookup($sanitizedInput);
            $actorsObject = $this->addTitleModel->actorLookup($sanitizedActors);

            if (!empty($actorsObject['actorsNotFound'])) {
                $this->addTitleModel->addNewActors($actorsObject);
            }

            $movie_id = $this->addTitleModel->addTitle($sanitizedInput);
            $actorIDs = $this->addTitleModel->getActorID($actorsObject);
            $this->addTitleModel->addActorsToTitle($movie_id, $actorIDs);

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