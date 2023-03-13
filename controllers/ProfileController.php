<?php
require_once('./config/dbconnect.php');
require_once ('./models/Profile.php');
require_once('./models/LastSeen.php');

class ProfileController
{
    private $conn;
    private $profile;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        $this->UpdateLastSeen();
        $this->fetchProfileData();

        $title = $this->profile['username'];
        $css = ["profile.css"];
        require './views/partials/header.php';
        require './views/profile/index.php';
        require './views/partials/footer.php';
    }
    public function UpdateLastSeen() {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
    private function fetchProfileData() {
        $model = new profile($this->conn);
        $this->profile = $model->ProfileLookup();
        $model->GetUserData($this->profile);
        $this->profile['gravatar'] = $model->GetGravatar($this->profile);
    }
}
