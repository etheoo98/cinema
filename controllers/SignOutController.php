<?php
use JetBrains\PhpStorm\NoReturn;
require_once (BASE_PATH . "/models/SignOut.php");
class SignOutController {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    #[NoReturn] public function initializeView(): void
    {
        # TODO: Include view, maybe.
        echo "Please wait...";
        $this->initiateSignOut();
    }

    #[NoReturn] private function initiateSignOut(): void
    {
        $model = new SignOut($this->conn);
        $model->removeSession();
        $model->signOut();
    }
}