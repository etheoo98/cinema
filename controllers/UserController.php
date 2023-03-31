<?php
require_once (dirname(__DIR__) . '/models/User.php');
require_once (dirname(__DIR__) . '/models/Session.php');

class UserController
{
    private mysqli $conn;
    private Session $sessionModel;
    private User $userModel;
    private ?array $profile = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->userModel = new User($this->conn);
    }

    public function index(): void
    {
        $this->sessionModel->updateLastSeen();
        $this->profile = $this->userModel->ProfileLookup();
        $this->userModel->GetUserData($this->profile);
        $this->profile['gravatar'] = $this->userModel->GetGravatar($this->profile);

        $this->renderIndexView();
    }
    private function renderIndexView(): void
    {
        $title = $this->profile['username'];
        $css = ["main.css", "users.css"];

        require_once (dirname(__DIR__) . '/views/shared/header.php');
        require_once (dirname(__DIR__) . '/views/users/index.php');
        require_once (dirname(__DIR__) . '/views/shared/footer.php');
    }
}
