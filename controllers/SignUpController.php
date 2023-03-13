<?php
require_once('./config/dbconnect.php');
require_once('./models/SignUp.php');
# TODO: SignUpController doesn't log sessions like SignInController, causing member area to be inaccessible at registration.

class SignUpController {

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index() {
        $title = "Sign Up";
        $css = ["register.css"];
        require_once ('./views/partials/header.php');
        require_once ('./views/sign-up/index.php');
        require_once ('./views/partials/footer.php');

        # Can be moved to it's own php file in /scripts/ and included with require_once
        echo '<script src="/cinema/public/js/error-message.js"></script>';

        if(isset($_POST['submit'])) {
            $this->initiateSignUp();
        }
    }
    private function initiateSignUp() {
        $model = new SignUp($this->conn);
        $sanitizedInput = $model->sanitizeInput();
        $model->validateEmail($sanitizedInput);
        $model->validatePassword($sanitizedInput);
        $model->emailLookup($sanitizedInput);
        $model->usernameLookup($sanitizedInput);
        $sanitizedInput = $model->passwordEncryption($sanitizedInput);
        $model->addUser($sanitizedInput);
    }
}