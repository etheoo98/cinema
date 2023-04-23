<?php
class Settings {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function emailLookup(): string
    {
        $email = mysqli_real_escape_string($this->conn, $_POST['new-email']);

        $sql = 'SELECT * FROM `user`
                WHERE user.email = ?';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        # Check that no rows are returned
        if ($result->num_rows > 0) {
            throw new Exception('Email is already in use.');
        }
        return $email;
    }

    /**
     * @throws Exception
     */
    public function changeEmail($email): void
    {
        $user_id = $_SESSION['user_id'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "UPDATE `user`
                    SET `email` = ?
                    WHERE `user`.`user_id` = ?;";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $email, $user_id);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception('Unable to change email.');
            }
        }
        else {
            throw new Exception('Invalid email format.');
        }
    }

    /**
     * @throws Exception
     */
    public function verifyOldPassword() {
        $user_id = $_SESSION['user_id'];
        $currentPassword = $_POST['current-password'];
        $sql = 'SELECT `password`
                FROM `user`
                WHERE `user_id` = ?';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $oldPassword = $result->fetch_assoc();

        if (!password_verify($currentPassword . "nuv`nHhPj7Cx&@Z#&@Jxi5xZnHRTkVL%", $oldPassword['password'])) {
            # Invalid credentials
            throw new Exception("Current password is wrong.");
        }
    }

    /**
     * @throws Exception
     */
    public function checkPasswordMatch(): array
    {
        if ($_POST['new-password'] != $_POST['retype-new-password']) {
            throw new Exception("Passwords don't match.");
        }
        $sanitizedInput['password'] = mysqli_real_escape_string($this->conn, $_POST['new-password']);
        return $sanitizedInput;
    }

    public function changePassword($sanitizedInput) {
        $user_id = $_SESSION['user_id'];
        $sql = 'UPDATE `user`
                SET `password` = ?
                WHERE `user`.`user_id` = ?;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $sanitizedInput['password_hash'], $user_id);
        $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public function getSessions(): array
    {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM user_session, session
                WHERE user_session.session_id = session.session_id
                AND user_id = ?
                AND valid = 1
                ORDER BY date DESC;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sessions = $result->fetch_all(MYSQLI_ASSOC);

        # Compare dates
        foreach ($sessions as &$session) {
            $date = new DateTime($session['date']);
            $now = new DateTime();
            $interval = $date->diff($now);
            $days = $interval->days;

            if ($days == 0) {
                $session['date'] = "Today";
            } else if ($days == 1) {
                $session['date'] = "Yesterday";
            } else if ($days > 1 && $days <= 7) {
                $session['date'] = "$days days ago";
            } else if ($days > 7 && $days <= 28) {
                $weeks = floor($days / 7);

                if ($weeks == 1) {
                    $session['date'] = "1 week ago";
                } else {
                    $session['date'] = "$weeks weeks ago";
                }
            } else if ($days > 28 && $days <= 365) {
                $months = floor($days / 30);

                if ($months == 1) {
                    $session['date'] = "1 month ago";
                } else {
                    $session['date'] = "$months months ago";
                }
            } else {
                $years = floor($days / 365);

                if ($years == 1) {
                    $session['date'] = "1 year ago";
                } else {
                    $session['date'] = "$years years ago";
                }
            }
        }

        return $sessions;
    }
}