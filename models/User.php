<?php
class User
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function ProfileLookup() {
        $user_id = mysqli_real_escape_string($this->conn, $_GET["id"]);
        $sql = 'SELECT user_id, username
                FROM user
                WHERE user_id = ?
                OR username = ?';

        $stmt = $this->conn->prepare("");
        $stmt->bind_param('is', $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            # Error if id or username does not exist in db
            echo "Profile Does not exist";
            exit();
        }
        else {
            return $result->fetch_assoc();
        }
    }
    
    public function GetUserData(&$profile): void
    {
        $sql = 'SELECT username, email, date_of_registration
                FROM user
                WHERE user_id=?';

        # Getting the email may pose a security concern. Alternatively make your own avatar upload.
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $profile["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        # Add keys & values to the already existing $users array
        $profile['username'] = $row['username'];
        $profile['email'] = $row['email'];
        $profile['date_of_registration'] = $row['date_of_registration'];
    }
    public function GetGravatar($profile): string
    {
        #TODO: Remove hotlink for default avatar
        $default = "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/b5/b5bd56c1aa4644a474a2e4972be27ef9e82e517e_full.jpg";
        $size = 184;
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($profile['email']))) . "?d=" . urlencode($default) . "&s=" . $size;
    }
}
