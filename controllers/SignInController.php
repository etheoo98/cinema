<?php
require_once(dirname(__DIR__) . '/models/SignIn.php');
require_once(dirname(__DIR__) . '/models/SignUp.php');
require_once(dirname(__DIR__) . '/models/Session.php');

class SignInController {

    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("LOCATION: index.php");
        }

        $title = "Sign In";
        $css = ["main.css", "sign-in.css"];
        require_once (dirname(__DIR__) . '/views/shared/header.php');
        require_once (dirname(__DIR__) . '/views/sign-in/index.php');
        echo '<script src="/cinema/public/js/sign-in.js"></script>';
        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');


        if(isset($_POST['SignIn'])) {
            try {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                $this->attemptSignIn();
                $this->logSession();
                echo '<script type="text/javascript">CatalogRedirect();</script>';
                exit;
            } catch (Exception $e) {
                # Log the error message
                error_log($e->getMessage());
                echo 'An error occurred while signing in. Please try again later.';
                # echo '<script type="text/javascript">ShowErrorMessage("' . $e->getMessage() . '");</script>';
            }
        }
        if(isset($_POST['SignUp'])) {
            try {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                $this->attemptSignUp();
                echo '<script type="text/javascript">CatalogRedirect();</script>';
            } catch (Exception $e) {
                # Log the error message
                error_log($e->getMessage());
                echo 'An error occurred while signing Up. Please try again later.';
                # echo '<script type="text/javascript">ShowErrorMessage("' . $e->getMessage() . '");</script>';
            }
        }
    }

    /**
     * @throws Exception
     */
    private function attemptSignIn(): void
    {
        $model = new SignIn($this->conn);
        $sanitizedInput = $model->sanitizeInput();
        $model->signIn($sanitizedInput);
    }
    /**
     * @throws Exception
     */
    private function attemptSignUp(): void
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

    /**
     * @throws Exception
     */
    private function logSession(): void
    {
        $model = new Session($this->conn);
        $session = $model->GetCountryCode();
        $model->addSession($session);
    }
}
