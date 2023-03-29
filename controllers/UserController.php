<?php
require_once ('./models/Profile.php');
require_once('./models/LastSeen.php');

class UserController
{
    private mysqli $conn;
    private ?array $profile = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index(): void
    {
        $this->UpdateLastSeen();
        $this->fetchProfileData();

        $title = $this->profile['username'];
        $css = ["main.css", "users.css"];
        require_once (dirname(__DIR__) . '/views/shared/header.php');
        require_once (dirname(__DIR__) . '/views/users/index.php');
        require_once (dirname(__DIR__) . '/views/shared/footer.php');
    }
    public function UpdateLastSeen(): void
    {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
    private function fetchProfileData(): void
    {
        $model = new profile($this->conn);
        $this->profile = $model->ProfileLookup();
        $model->GetUserData($this->profile);
        $this->profile['gravatar'] = $model->GetGravatar($this->profile);
    }
}
