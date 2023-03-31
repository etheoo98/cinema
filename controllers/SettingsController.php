<?php
require_once ('./models/Settings.php');
require_once ('./models/Session.php');

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
            require './views/settings/index.php';
        }
        else {
            require_once (dirname(__DIR__) . '/views/shared/error.php');
        }

        require_once (dirname(__DIR__) . '/views/shared/footer.php');

        # TODO: ajaxHandler for forms
        if(isset($_POST['change-email']) && isset($_POST['new-email']) && !empty($_POST['new-email'])) {
            try {
                $model = new Settings($this->conn);
                $model->emailLookup();
                $model->changeEmail();
                echo '<script>alert("Your email was successfully changed.")</script>';
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo '<script>alert("'.$e->getMessage().'")</script>';
            }
        }

        if (isset($_POST['terminate']) && isset($_POST['checkBoxes'])) {
            try {
                $this->terminateSession();
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo '<script>alert("We were unable to terminate your session(s).")</script>';
            }
        }
    }

    /**
     * @throws Exception
     */
    private function terminateSession(): void
    {
        $checkedBoxes = $_POST['checkBoxes'];
        # Sanitize values, as they may have been altered
        foreach ($checkedBoxes as &$value) {
            $value = mysqli_real_escape_string($this->conn, $value);
        }

        $model = new Session($this->conn);
        $model->terminateSession($checkedBoxes);
        $model->validateSession();
    }
}