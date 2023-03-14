<?php
require_once('./models/SignUp.php');
# TODO: SignUpController doesn't log sessions like SignInController, causing member area to be inaccessible at registration.

class SignUpController {

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $title = "Sign Up";
        $css = ["register.css"];
        require_once ('./views/partials/header.php');
        require_once ('./views/sign-up/index.php');
        require_once ('./views/partials/footer.php');

        # TODO: Can be moved to its own php file in /scripts/ and included with require_once(?)
        echo '<script src="/cinema/public/js/error-message.js"></script>';

        if(isset($_POST['submit'])) {
            $this->initiateSignUp();
        }
    }

    /**
     * @throws Exception
     */
    private function initiateSignUp(): void
    {
        try {
            $model = new SignUp($this->conn);
            $sanitizedInput = $model->sanitizeInput();
            $model->validateEmail($sanitizedInput);
            $model->validatePassword($sanitizedInput);
            $model->emailLookup($sanitizedInput);
            $model->usernameLookup($sanitizedInput);
            $sanitizedInput = $model->passwordEncryption($sanitizedInput);
            $model->addUser($sanitizedInput);
        } catch (Exception $e) {
            # Log the error message
            error_log($e->getMessage());
            echo 'An error occurred while signing up. Please try again later.';
        }
    }
}