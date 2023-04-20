<?php
require_once (BASE_PATH . '/models/Settings.php');
require_once (BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/models/SignUp.php');
require_once (BASE_PATH . '/public/scripts/SettingsControllerMiddleware.php');

#[AllowDynamicProperties] class SettingsController {
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
        $this->signUpModel = new SignUp($this->conn);
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
    public function initializeView(): void
    {
        $this->sessionIsValid = $this->sessionModel->validateSession();
        $this->sessions = $this->settingsModel->getSessions();
        $this->renderView();
    }
    /**
     * Renders the settings index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $this->sessionIsValid is true and $this->sessions is not NULL,
     * the catalog index view is rendered. Otherwise, an error page is rendered.
     */
    private function renderView(): void
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

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a match
     * expression. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     *
     */
    public function ajaxHandler(): void
    {
        $action = $_POST['action'] ?? null;

        $response = match ($action) {
            'change-email' => $this->changeEmail(),
            'change-password' => $this->changePassword(),
            'terminate-session' => $this->terminateSession(),
            default => [
                'status' => 'error',
                'message' => 'Invalid action'
            ],
        };
        echo json_encode($response);
    }

    /**
     * @throws Exception
     */
    public function changeEmail(): array
    {
        try {
            $email = $this->settingsModel->emailLookup();
            $this->settingsModel->changeEmail($email);
            $response = [
                'status' => true,
                'message' => 'Email Successfully Updated.',
                'email' => $email
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }

    public function changePassword(): array
    {
        try {
            $this->settingsModel->verifyOldPassword();
            $sanitizedInput = $this->settingsModel->checkPasswordMatch();
            $sanitizedInput = $this->signUpModel->passwordEncryption($sanitizedInput);
            $this->settingsModel->changePassword($sanitizedInput);

            $response = [
                'status' => true,
                'message' => 'Password successfully changed.'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
        return $response;
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
                'status' => true,
                'message' => 'Successfully removed session(s)'
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