<?php
require_once ('./models/Settings.php');
require_once ('./models/Session.php');

class SettingsController {
    private mysqli $conn;
    private ?array $sessions = null;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $this->requireSignIn();
        $this->fetchSessionData();

        $title = "Settings";
        $css = ['settings.css'];
        require_once './views/partials/header.php';
        if (isset($this->sessions)) {
            require './views/settings/index.php';
        }
        else {
            require_once './views/error/index.php';
        }
        require_once './views/partials/footer.php';

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
    private function requireSignIn(): void
    {
        $model = new Session($this->conn);
        $model->validateSession();
    }
    private function fetchSessionData(): void
    {
        $model = new Settings($this->conn);
        $this->sessions = $model->getSessions();
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
        # TODO: Use javascript to remove deleted session(s) as to not require refresh
    }
}