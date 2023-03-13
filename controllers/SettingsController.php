<?php
require_once ('./config/dbconnect.php');
require_once ('./models/Settings.php');
require_once ('./models/Session.php');

class SettingsController {
    private $conn;
    private $sessions;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index() {
        $this->requireSignIn();
        $sessions = $this->fetchSessionData();

        $title = "Settings";
        $css = ['settings.css'];
        require './views/partials/header.php';
        require './views/settings/index.php';
        require './views/partials/footer.php';

        if (isset($_POST['terminate']) && isset($_POST['checkboxes'])) {
            $this->terminateSession();
        }
    }
    private function requireSignIn() {
        $model = new Session($this->conn);
        $model->validateSession();
    }
    private function fetchSessionData() {
        $model = new Settings($this->conn);
        $this->sessions = $model->getSessions();
    }
    private function terminateSession() {
        $checkedBoxes = $_POST['checkboxes'];
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