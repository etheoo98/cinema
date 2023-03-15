<?php
require_once ('./models/Settings.php');
require_once ('./models/Session.php');

class SettingsController {
    private $conn;
    private $sessions;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
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

        if (isset($_POST['terminate']) && isset($_POST['checkBoxes'])) {
            $this->terminateSession();
        }
    }
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