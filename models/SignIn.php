<?php

class SignIn
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sanitizeInput()
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = mysqli_real_escape_string($this->conn, $_POST['email']);
            $password = mysqli_real_escape_string($this->conn, $_POST['password']);

            return array(
                'email' => $email,
                'password' => $password
            );
        } elseif (empty($_POST['email']) || empty($_POST['password'])) {
            echo '<script type="text/javascript">EmptyFields();</script>';
            exit();
        }
    }
    public function signIn($sanitizedInput)
    {
        $sql = "SELECT user_id, email, username, salt, password FROM user WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $sanitizedInput['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            # Concatenate the salt with the entered password
            $entered_password_with_salt = $user['salt'] . $sanitizedInput['password'];

            # Verify the password hash
            # TODO: $sanitizedInput['password'] and $user['password'] naming may be confusing.
            # Alter user table to password->password_hash & salt->password_salt
            if (password_verify($entered_password_with_salt, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                return True;
            }
            else {
                # TODO: Error handling
                throw new Exception('Invalid credentials.');
            }
        } else {
            throw new Exception('Invalid credentials.');
        }
    }
}