<?php

class SignUp
{

    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function sanitizeInput(): array
    {
        # Check if no value is left empty in form
        if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['_password'])) {
            # Sanitize input
            $email = mysqli_real_escape_string($this->conn, $_POST['email']);
            $username = mysqli_real_escape_string($this->conn, $_POST['username']);
            $password = mysqli_real_escape_string($this->conn, $_POST['password']);
            $_password = mysqli_real_escape_string($this->conn, $_POST['_password']);

            return array(
                'email' => $email,
                'username' => $username,
                'password' => $password,
                '_password' => $_password
            );
        } else {
            throw new Exception('Please Fill In All Form Fields!');
        }
    }

    /**
     * @throws Exception
     */
    public function validateEmail($sanitizedInput): void
    {
        # Check if input matches email format
        if (!filter_var($sanitizedInput['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please Enter A Valid Email Format');
        }
    }

    /**
     * @throws Exception
     */
    public function validatePassword($sanitizedInput): void
    {
        # Check if password fields match
        if ($sanitizedInput['password'] != $sanitizedInput['_password']) {
            throw new Exception("Passwords Don't Match!");
        }
    }

    /**
     * @throws Exception
     */
    public function emailLookup($sanitizedInput): void
    {
        # Check if email is available
        $stmt = $this->conn->prepare("SELECT email FROM user WHERE email=?");
        $stmt->bind_param('s', $sanitizedInput['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            throw new Exception("Email Is Already In Use!");
        }
    }

    /**
     * @throws Exception
     */
    public function usernameLookup($sanitizedInput): void
    {
        # Check if username is available
        $stmt = $this->conn->prepare("SELECT username FROM user WHERE username=?");
        $stmt->bind_param('s', $sanitizedInput['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            throw new Exception("Username Is Already In Use!");
        }
    }

    public function passwordEncryption($sanitizedInput)
    {
        # Generate a random salt
        $salt = bin2hex(random_bytes(16)); # 16 bytes = 128 bits

        # Concatenate the salt with the password and hash it
        $hashed_password = password_hash($salt . $sanitizedInput['password'], PASSWORD_DEFAULT);

        # Remove the original password from the input array
        unset($sanitizedInput['password']);
        unset($sanitizedInput['_password']);

        # Store the salt and hashed password in the input array
        $sanitizedInput['password_salt'] = $salt;
        $sanitizedInput['password_hash'] = $hashed_password;

        return $sanitizedInput;
    }

    public function addUser($sanitizedInput): void
    {
        $sql = "INSERT INTO user (role, email, username, salt, password, date_of_registration, last_seen) VALUES ('user', ?, ?, ?, ?, now(), now())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $sanitizedInput['email'], $sanitizedInput['username'], $sanitizedInput['password_salt'], $sanitizedInput['password_hash']);

        if ($stmt->execute()) {
            $user_id = mysqli_insert_id($this->conn);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $sanitizedInput['email'];
            $_SESSION['username'] = $sanitizedInput['username'];
        } else {
            # Query failed to execute
            echo "Error executing query: " . $stmt->error;
        }
    }
}
