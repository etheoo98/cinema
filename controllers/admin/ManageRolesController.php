<?php
require_once(BASE_PATH . '/models/admin/ManageRoles.php');
require_once(BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/middleware/ManageRolesControllerMiddleware.php');

class ManageRolesController
{
    private mysqli $conn;
    private Session $sessionModel;
    private ManageRoles $manageRolesModel;
    private false|mysqli_result $currentAdmins;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->manageRolesModel = new ManageRoles($this->conn);
    }

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, the session model's requireAdminRole()
     * function is called, which will return either 'true' or 'false'. This will depend on
     * what 'role' the 'user_id' has in the database. If true, the rendition of the
     * page will commence. Otherwise, a redirect occurs.
     *
     * Before rendering the view, a call to the model ManageRoles' getCurrentAdmins function
     * is made in order to fetch usernames with the role of 'admin'.
     *
     */
    public function initializeView(): void
    {
        $sessionIsAdmin = $this->sessionModel->requireAdminRole();

        if ($sessionIsAdmin) {
            $this->currentAdmins = $this->manageRolesModel->getCurrentAdmins();
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

        $title = "Add Movie";
        $css = ["admin/main.css", "admin/manage-roles.css"];
        $js = ["admin/admin.js", "admin/manage-roles.js"];

        require_once(BASE_PATH . '/views/admin/shared/header.php');
        require_once(BASE_PATH . '/views/admin/manage-roles/index.php');
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
            'promote-user' => $this->promoteUser(),
            'demote-user' => $this->demoteUser(),
            default => [
                'status' => 'error',
                'message' => 'Invalid action'
            ],
        };

        echo json_encode($response);
    }

    /**
     * This function is called from ajaxHandler() and will attempt to 'promote' the
     * inputted username to 'admin'.
     *
     * First, the submitted values have to be sanitized before a safe SQL-query can be made.
     * The username is then fetched from the database using the sanitized value and a series
     * of validation is performed such as checking if the username exists and if the username
     * is already of the 'admin' role or not.
     *
     * If the username exists and is determined to not already be of the 'admin' role, they will
     * be promoted.
     */
    public function promoteUser(): array
    {
        {
            try {
                $sanitizedInput = $this->manageRolesModel->sanitizeInput();
                $sanitizedInput = $this->manageRolesModel->usernameLookup($sanitizedInput);
                $this->manageRolesModel->promoteUserToAdmin($sanitizedInput);
                $response = [
                    'status' => true,
                    'message' => 'User successfully promoted to Admin',
                    'user_id' => $sanitizedInput['user_id'],
                    'username' => $sanitizedInput['promote_username']
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

    /**
     * This function is called from ajaxHandler() and will attempt to 'demote' the
     * inputted username to 'user'.
     *
     * First, the submitted values have to be sanitized before a safe SQL-query can be made.
     * The username is then fetched from the database using the sanitized value and a series
     * of validation is performed such as checking if the username exists and if the username
     * is already of the 'user' role or not.
     *
     * If the username exists and is determined to not already be of the 'user' role, they will
     * be demoted.
     */
    public function demoteUser(): array
    {
        {
            try {
                $sanitizedInput = $this->manageRolesModel->sanitizeInput();
                $sanitizedInput = $this->manageRolesModel->usernameLookup($sanitizedInput);
                $this->manageRolesModel->demoteAdminToUser($sanitizedInput);
                $response = [
                    'status' => true,
                    'message' => 'Admin successfully demoted to user',
                    'username' => $sanitizedInput['demote_username']
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
}