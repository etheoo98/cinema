<?php
require_once('./config/dbconnect.php');
require_once('./models/SignIn.php');
require_once('./models/Session.php');

class SignInController {

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index() {
        $title = "Sign In";
        $css = ["register.css"];
        require_once ('./views/partials/header.php');
        require_once ('./views/sign-in/index.php');
        require_once ('./views/partials/footer.php');

        echo '<script src="/cinema/public/js/SignIn.js"></script>';

        if(isset($_POST['submit'])) {
            try {
                $this->attemptSignIn();
                $this->logSession();
                header("Location: index.php");
                exit;
            } catch (Exception $e) {
                # TODO: Setup js exception handling
                echo "CATCH ERROR";
                # echo '<script type="text/javascript">ShowErrorMessage("' . $e->getMessage() . '");</script>';
            }
        }
    }
    private function attemptSignIn() {
        $model = new SignIn($this->conn);
        $sanitizedInput = $model->sanitizeInput();
        return $model->signIn($sanitizedInput);
    }
    private function logSession() {
        $model = new Session($this->conn);
        $session = $model->GetCountryCode();
        $model->AddSession($session);
    }
}
