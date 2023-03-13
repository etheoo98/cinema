<?php
require_once('./config/dbconnect.php');
require_once('./models/Bookings.php');
require_once('./models/Session.php');
require_once('./models/LastSeen.php');

class BookingsController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index()
    {
        $this->requireSignIn();
        $this->updateLastSeen();
        $bookingsData = $this->getBookingsData();

        $title = "Bookings";
        $css = ["movies.css", "bookings.css"];
        require_once('./views/partials/header.php');

        if (isset($bookingsData)) {
            require_once('./views/bookings/index.php');
        }
        else {
            require_once('./views/partials/error-page.php');
        }

        require_once('./views/partials/footer.php');

        if (isset($_POST['remove'])) {
            $movie_id = $_POST['remove'];
            $this->deleteBooking($movie_id);
            # TODO: Don't refresh, remove movie-item with javascript.
            echo "<script>redirectTo('/cinema/bookings');</script>";
        }
    }
    private function requireSignIn() {
        $model = new Session($this->conn);
        $model->validateSession();
    }
    public function getBookingsData()
    {
        $user_id = $_SESSION['user_id'];
        $model = new Bookings($this->conn);
        return $model->getBookingsData($user_id);
    }
    public function deleteBooking($movie_id)
    {
        $user_id = $_SESSION["user_id"];
        $model = new Bookings($this->conn);
        $model->deleteBooking($user_id, $movie_id);
    }
    public function updateLastSeen() {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}