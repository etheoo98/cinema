<?php
require_once ("./config/dbconnect.php");
require_once ("./models/SignOut.php");
class SignOutController {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index() {
        # TODO: Include view maybe.
        echo "Please wait...";
        $this->initiateSignOut();
        header("Location: /cinema/index.php");
    }
    private function initiateSignOut() {
        $model = new SignOut($this->conn);
        $model->removeSession();
        $model->signOut();
    }
}