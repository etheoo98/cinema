<?php
require_once (BASE_PATH . '/models/Settings.php');
require_once (BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/public/scripts/SettingsControllerMiddleware.php');

class SettingsController {
    private mysqli $conn;
    private Session $sessionModel;
    private Settings $settingsModel;
    private bool $sessionIsValid;
    private ?array $sessions = null;
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->settingsModel = new Settings($this->conn);
    }

    /**
     * Handles the settings index page request.
     *
     * This method calls the validateSession method of the Session Model to verify
     * that the current session is recorded as valid in the database and assigns
     * the returned value to $this->sessionIsValid. It then retrieves the recorded
     * sessions associated with this user and assigns the returned array to
     * $this->sessions. Finally the renderIndexView method is called to render
     * the index view.
     *
     * @throws Exception
     */
    public function index(): void
    {
        $this->sessionIsValid = $this->sessionModel->validateSession();
        $this->sessions = $this->settingsModel->getSessions();
        $this->renderIndexView();
    }
    /**
     * Renders the settings index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $this->sessionIsValid is true and $this->sessions is not NULL,
     * the catalog index view is rendered. Otherwise, an error page is rendered.
     */
    private function renderIndexView(): void
    {
        $title = "Settings";
        $css = ["main.css", 'settings.css'];

        require_once (dirname(__DIR__) . '/views/shared/header.php');

        if ($this->sessionIsValid && isset($this->sessions)) {
            require_once (BASE_PATH . '/views/settings/index.php');
        }
        else {
            require_once (BASE_PATH . '/views/shared/error.php');
        }

        require_once (BASE_PATH . '/views/shared/footer.php');
    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'update-email':
                $response = $this->updateEmail();
                break;
            case 'update-password':
                $response = $this->updatePassword();
                break;
            case 'terminate-session':
                $response = $this->terminateSession();
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
     * @throws Exception
     */
    public function updateEmail(): array
    {
        try {
            $email = $this->settingsModel->emailLookup();
            $this->settingsModel->changeEmail($email);
            $response = [
                'status' => 'Success',
                'message' => 'Email Successfully Updated.',
                'email' => $email
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }

    public function updatePassword() {

    }

    /**
     * @throws Exception
     */
    private function terminateSession(): array
    {
        try {
            $checkedBoxes = $_POST['checkBoxes'];

            foreach ($checkedBoxes as &$value) {
                $value = mysqli_real_escape_string($this->conn, $value);
            }

            $this->sessionModel->terminateSession($checkedBoxes);
            $this->sessionModel->validateSession();

            $response = [
                'status' => 'Success',
                'message' => 'Successfully removed session(s)'
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