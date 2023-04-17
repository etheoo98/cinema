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
     *
     * This function checks if all required form fields are filled, and if they are, it sanitizes and returns the user
     * input as an array. If any field is missing, it throws an exception asking the user to fill in all the fields.
     *
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
     *
     * This function checks if the email input matches a valid email format or not.
     *
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
     *
     * This function compares the two entered passwords, and throws an exception if they are not the same.
     *
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
     *
     * This function looks up the inputted email, in order to make sure that the email is unique. If the inputted email
     * is already in use, an exception will be thrown, informing the user that it's not available.
     *
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
     *
     * This function looks up the inputted username, in order to make sure that the username is unique. If the inputted
     * username is already in use, an exception will be thrown, informing the user that it's not available.
     *
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

    /**
     * @param $sanitizedInput
     * @return mixed
     *
     * This function takes the inputted password and adds a "pepper" string to it. The password and pepper is then
     * hashed using the bcrypt algorithm with a cost factor of 12, which includes salt. Before returning the hashed
     * password, the previously used variables and keys storing the password are removed.
     *
     */
    public function passwordEncryption($sanitizedInput): mixed
    {
        $password = $sanitizedInput['password'];
        $pepper = "nuv`nHhPj7Cx&@Z#&@Jxi5xZnHRTkVL%";

        $password .= $pepper;

        # Hash the password with a random salt
        $sanitizedInput['password_hash'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        # Remove the original password
        unset($sanitizedInput['password']);
        unset($sanitizedInput['_password']);
        unset ($password);

        return $sanitizedInput;
    }

    /**
     * @param $sanitizedInput
     * @return void
     *
     * If no exception has been thrown up until this point, the input is deemed as valid, and the account details will
     * be stored in the database. If the query is successful, the session variables will be set.
     *
     */
    public function addUser($sanitizedInput): void
    {
        $sql = "INSERT INTO user (role, email, username, password, date_of_registration, last_seen) VALUES ('user', ?, ?, ?, now(), now())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $sanitizedInput['email'], $sanitizedInput['username'], $sanitizedInput['password_hash']);

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
