<?php

use JetBrains\PhpStorm\NoReturn;

class SignOut {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function removeSession(): void
    {
        # Delete row from session table
        $phpsessid = filter_var(session_id(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $sql = "DELETE user_session, session FROM user_session INNER JOIN session ON user_session.session_id = session.session_id WHERE phpsessid=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $phpsessid);
        $stmt->execute();
        if (!$stmt->execute()) {
            // get the error code and error message
            $errno = $stmt->errno;
            $error = $stmt->error;
            // handle the error (e.g. log it or display an error message)
            echo "Error: $errno - $error";
        }
        else {
            header("LOCATION: /cinema/");
        }
    }
    #[NoReturn] public function signOut(): void
    {
        # Remove all session variables.
        session_unset();
        # Invalidate session ID
        session_destroy();
        header("Location: /cinema/index.php");
        exit();
    }
}