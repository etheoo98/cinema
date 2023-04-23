<?php

use JetBrains\PhpStorm\NoReturn;

class SignOut {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return void
     *
     * This function removes the user's session by deleting the corresponding rows from the session table and the
     * user_session table in the database. It also redirects the user to the landing page.
     *
     */
    public function removeSession(): void
    {
        # Delete row from session table
        $phpsessid = filter_var(session_id(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $sql = "DELETE user_session, session
                FROM user_session
                INNER JOIN session ON user_session.session_id = session.session_id
                WHERE phpsessid=?";

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

    /**
     * @return void
     *
     * This function logs the user out of the system by removing all session variables and invalidating the session ID.
     * It then redirects the user to the landing page.
     *
     */
    #[NoReturn] public function signOut(): void
    {
        # Remove all session variables.
        session_unset();
        # Invalidate session ID
        session_destroy();
        # Redirect to landing page
        header("Location: /cinema/");
        exit();
    }
}