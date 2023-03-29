<?php

use JetBrains\PhpStorm\NoReturn;

require_once (dirname(__DIR__) . "/models/SignOut.php");
class SignOutController {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    #[NoReturn] public function index(): void
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