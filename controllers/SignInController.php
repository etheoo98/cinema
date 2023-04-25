<?php
require_once(BASE_PATH . '/models/SignIn.php');
require_once(BASE_PATH . '/models/SignUp.php');
require_once(BASE_PATH . '/models/Session.php');
require_once(BASE_PATH . '/middleware/SignInControllerMiddleware.php');

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

    /**
     * This function handles the front controller request.
     *
     * Before allowing the rendition of the view, a check is made to determine whether the user is already signed-in.
     * If a 'user_id' is set in the $_SESSION super global variable, they will be redirected to the landing page.
     * If it is determined that the user is not signed in, the view will render.
     *
     */
    public function initializeView(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("LOCATION: /cinema/");
        }
        $this->renderView();
    }

    /**
     * This function handles the rendition of the view.
     *
     * If the request has been determined to have been made by a not already signed-in user, the view will render.
     * The contents of the title tag for this specific view is set here the controller, along with
     * what stylesheets apply to this view in particular.
     *
     */
    private function renderView(): void
    {
        $title = "Sign-In/Up";
        $css = ["main.css", "sign-in.css"];
        $js = ["sign-in.js"];

        require_once (BASE_PATH . '/views/shared/header.php');
        require_once (BASE_PATH . '/views/sign-in/index.php');
        require_once (BASE_PATH . '/views/shared/small-footer.php');
    }

    /**
     * This function handles incoming AJAX requests.
     *
     * The AJAX request must include a 'action' to be taken. The action is handled through a Switch
     * Statement. On valid action, an appropriate method call is made. The response is finally encoded as
     * JSON and returned to the AJAX request.
     *
     */
    public function ajaxHandler(): void
    {
        $action = $_POST['action'] ?? null;

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
                        'status' => true
                    ];
                } catch (Exception $e) {
                    $response = [
                        'status' => false,
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
                        'status' => true
                    ];
                } catch (Exception $e) {
                    $response = [
                        'status' => false,
                        'message' => $e->getMessage()
                    ];
                }
                break;
            default:
                $response = [
                    'status' => false,
                    'message' => 'Invalid action'
                ];
                break;
        }
        echo json_encode($response);
    }
}
