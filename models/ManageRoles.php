<?php

#[AllowDynamicProperties] class ManageRoles
{
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * The function queries the database to retrieve the user IDs and usernames of
     * all users with the 'admin' role, and returns the result set.
     *
     */
    public function getCurrentAdmins() {
        $sql = "SELECT `user_id`, `username` FROM `user` WHERE `role` = 'admin';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * @return array
     *
     * This function sanitizes all the input received from a POST request and returns it
     * in an array format.
     */
    public function sanitizeInput(): array
    {
        $sanitizedInput = array();
        foreach ($_POST as $key => $value) {
            $sanitizedInput[$key] = mysqli_real_escape_string($this->conn, $value);
        }
        return $sanitizedInput;
    }

    /**
     * @throws Exception
     *
     * This function is used for when both 'promoting' and 'demoting' usernames.
     *
     * Separating this function into two different functions would result in a lot
     * of duplicate code, as the way promotions and demotions are handled are nearly
     * identical.
     *
     * This function performs a username lookup based on the sanitized input and
     * returns the input or throws an exception if the username doesn't exist or if
     * the user is already an administrator or a normal user depending on the 'action'
     * parameter.
     *
     */
    public function usernameLookup($sanitizedInput)
    {
        $sql = 'SELECT user_id, username, role FROM `user` WHERE username = ?';

        $stmt = $this->conn->prepare($sql);

        if ($_POST['action'] == 'promote-user') {
            $stmt->bind_param('s', $sanitizedInput['promote_username']);
        } elseif ($_POST['action'] == 'demote-user') {
            $stmt->bind_param('s', $sanitizedInput['demote_username']);
        }

        if (!$stmt->execute()) {
            throw new Exception('Unable to lookup username at this moment.');
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception('The username does not exist');
        }

        if ($row['role'] == 'admin' && $_POST['action'] == 'promote-user') {
            throw new Exception('The user ' . $sanitizedInput['promote_username'] . ' is already an administrator.');
        } elseif ($row['role'] == 'user' && $_POST['action'] == 'demote-user') {
            throw new Exception('The user ' . $sanitizedInput['demote_username'] . ' is already a normal user.');
        } else {
            $sanitizedInput['user_id'] = $row['user_id'];
        }

        return $sanitizedInput;
    }

    /**
     * @throws Exception
     *
     * This function promotes a user to an admin by updating the database, and throws
     * an exception if it is unsuccessful.
     */
    public function promoteUserToAdmin($sanitizedInput): void
    {
        $sql = "UPDATE `user` SET `role` = 'admin' WHERE `user`.`user_id` = ? AND `user`.`username` = ?;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('is', $sanitizedInput['user_id'], $sanitizedInput['promote_username']);

        if (!$stmt->execute()) {
            throw new Exception('Unable to promote to admin.');
        }
    }

    /**
     * @throws Exception
     *
     * This function demotes an admin to a user by updating the database, and throws
     * an exception if it is unsuccessful.
     */
    public function demoteAdminToUser($sanitizedInput): void
    {
        $sql = "UPDATE `user` SET `role` = 'user' WHERE `user`.`user_id` = ? AND `user`.`username` = ?;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('is', $sanitizedInput['user_id'], $sanitizedInput['demote_username']);

        if (!$stmt->execute()) {
            throw new Exception('Unable to promote to admin.');
        }
    }

}