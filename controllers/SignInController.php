<?php
require_once(dirname(__DIR__) . '/models/SignIn.php');
require_once(dirname(__DIR__) . '/models/SignUp.php');
require_once(dirname(__DIR__) . '/models/Session.php');
require_once(dirname(__DIR__) . '/public/scripts/SignInControllerMiddleware.php');

class SignInController {

    private mysqli $conn;
    private Session $sessionModel;
    private SignIn $signInModel;
    private SignUp $signUpModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->signInModel = new SignIn($this->conn);
        $this->signUpModel = new SignUp($this->conn);
    }

    public function index(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("LOCATION: /cinema/");
        }
        $this->renderIndexView();
    }

    private function renderIndexView(): void
    {
        $title = "Sign In";
        $css = ["main.css", "sign-in.css"];

        require_once (dirname(__DIR__) . '/views/shared/header.php');

        require_once (dirname(__DIR__) . '/views/sign-in/index.php');

        echo '<script src="/cinema/public/js/sign-in.js"></script>';

        require_once (dirname(__DIR__) . '/views/shared/small-footer.php');

        /**
        if(isset($_POST['SignIn'])) {
            try {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                $sanitizedInput = $this->signInModel->sanitizeInput();
                $this->signInModel->signIn($sanitizedInput);
                $sessionData = $this->sessionModel->getSessionData();
                $this->sessionModel->addSession($sessionData);
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

                $sanitizedInput = $this->signUpModel->sanitizeInput();
                $this->signUpModel->validateEmail($sanitizedInput);
                $this->signUpModel->validatePassword($sanitizedInput);
                $this->signUpModel->emailLookup($sanitizedInput);
                $this->signUpModel->usernameLookup($sanitizedInput);
                $sanitizedInput = $this->signUpModel->passwordEncryption($sanitizedInput);
                $this->signUpModel->addUser($sanitizedInput);

                echo '<script type="text/javascript">CatalogRedirect();</script>';
            } catch (Exception $e) {
                # Log the error message
                error_log($e->getMessage());
                echo 'An error occurred while signing Up. Please try again later.';
                # echo '<script type="text/javascript">ShowErrorMessage("' . $e->getMessage() . '");</script>';
            }
        }
        */
    }
    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'sign-in':
                try {
                    ini_set('display_errors', 1);
                    error_reporting(E_ALL);
                    $sanitizedInput = $this->signInModel->sanitizeInput();
                    $this->signInModel->signIn($sanitizedInput);
                    $this->signUpModel->validateEmail($sanitizedInput);
                    $sessionData = $this->sessionModel->getSessionData();
                    $this->sessionModel->addSession($sessionData);
                    $response = [
                        'status' => 'Sign-In Success'
                    ];
                } catch (Exception $e) {
                    $response = [
                        'status' => 'Sign-In Failed',
                        'message' => $e->getMessage()
                    ];
                }
                break;
            case 'sign-up':
                try {
                    $sanitizedInput = $this->signUpModel->sanitizeInput();
                    $this->signUpModel->validateEmail($sanitizedInput);
                    $this->signUpModel->validatePassword($sanitizedInput);
                    $this->signUpModel->emailLookup($sanitizedInput);
                    $this->signUpModel->usernameLookup($sanitizedInput);
                    $sanitizedInput = $this->signUpModel->passwordEncryption($sanitizedInput);
                    $this->signUpModel->addUser($sanitizedInput);
                    $response = [
                        'status' => 'Sign-Up Success'
                    ];
                } catch (Exception $e) {
                    $response = [
                        'status' => 'Sign-Up Failed',
                        'message' => $e->getMessage()
                    ];
                }
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action'
                ];
                break;
        }
        echo json_encode($response);
    }
}
