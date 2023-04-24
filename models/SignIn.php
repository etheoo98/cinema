<?php

class SignIn
{

    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     *
     * This function checks if required input fields are missing and removes any harmful characters from user input
     * before returning it. An Exception is thrown if a required input field does not contain a value.
     *
     */
    public function sanitizeInput(): array
    {
        # Look for missing input fields
        $requiredFields = array('email', 'password');
        $missingFields = array();

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }

        if (count($missingFields) > 0) {
            $missingFieldsStr = implode(', ', $missingFields);
            throw new Exception("Missing fields: $missingFieldsStr");
        }

        # Sanitize user input
        $sanitizedInput = array();
        foreach ($_POST as $key => $value) {
            $sanitizedInput[$key] = mysqli_real_escape_string($this->conn, $value);
        }
        return $sanitizedInput;
    }

    /**
     * @throws Exception
     *
     * This function checks if the sanitized email exists in the database, and if it does, verifies that the input
     * password matches the stored hash. If the credentials are valid, it sets session variables for the logged-in user
     * and updates their "last seen" timestamp in the database. If the credentials are invalid, an exception is thrown.
     *
     */
    public function signIn($sanitizedInput): void
    {
        $sql = "SELECT user_id, email, username, password, role
                FROM user
                WHERE email=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $sanitizedInput['email']);
        if (!$stmt->execute()) {
            throw new Exception("Server failed");
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        # Verify the password hash
        if (!$user || !password_verify($sanitizedInput['password'] . "nuv`nHhPj7Cx&@Z#&@Jxi5xZnHRTkVL%", $user['password'])) {
            # Invalid credentials
            throw new Exception("Invalid credentials!");
        }

        # Password is valid, set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        # Update the user's last seen timestamp
        $sql = "UPDATE user
                SET last_seen = now()
                WHERE user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
    }
}