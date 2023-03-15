<?php

class SignUp
{

    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sanitizeInput()
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
            # Call js error function
            echo '<script type="text/javascript">EmptyFields()</script>';
            exit();
        }
    }
    public function validateEmail($sanitizedInput): void
    {
        # Check if input matches email format
        if (!filter_var($sanitizedInput['email'], FILTER_VALIDATE_EMAIL)) {
            # Call js error function
            echo '<script type="text/javascript">InvalidEmailFormat();</script>';
            exit();
        }
    }
    public function validatePassword($sanitizedInput): void
    {
        # Check if password fields match
        if ($sanitizedInput['password'] != $sanitizedInput['_password']) {
            # Call js error function
            echo '<script type="text/javascript">PasswordMatch();</script>';
            exit();
        }
    }
    public function emailLookup($sanitizedInput): void
    {
        # Check if email is available
        $stmt = $this->conn->prepare("SELECT email FROM user WHERE email=?");
        $stmt->bind_param('s', $sanitizedInput['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            echo '<script type="text/javascript">EmailInUse();</script>';
            exit();
        }
    }
    public function usernameLookup($sanitizedInput): void
    {
        # Check if username is available
        $stmt = $this->conn->prepare("SELECT username FROM user WHERE username=?");
        $stmt->bind_param('s', $sanitizedInput['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            echo '<script type="text/javascript">UsernameInUse();</script>';
            exit();
        }
    }

    /**
     * @throws Exception
     */
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
            header("Location: /cinema/");
            exit();
        } else {
            # Query failed to execute
            echo "Error executing query: " . $stmt->error;
        }
    }
}
