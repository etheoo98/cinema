<?php
require_once('./models/SignIn.php');
require_once('./models/Session.php');

class SignInController {

    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index(): void
    {
        $title = "Sign In";
        $css = ["register.css"];
        require_once ('./views/partials/header.php');
        require_once ('./views/sign-in/index.php');
        require_once ('./views/partials/footer.php');

        echo '<script src="/cinema/public/js/sign-in.js"></script>';

        if(isset($_POST['submit'])) {
            try {
                $this->attemptSignIn();
                $this->logSession();
                header("Location: index.php");
                exit;
            } catch (Exception $e) {
                # Log the error message
                error_log($e->getMessage());
                echo 'An error occurred while signing ip. Please try again later.';
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
    private function logSession(): void
    {
        $model = new Session($this->conn);
        $session = $model->GetCountryCode();
        $model->addSession($session);
    }
}
