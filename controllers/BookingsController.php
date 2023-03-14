<?php
require_once('./config/dbconnect.php');
require_once('./models/Bookings.php');
require_once('./models/Session.php');
require_once('./models/LastSeen.php');

class BookingsController
{
    private $conn;
    private $bookingData;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function index(): void
    {
        $this->requireSignIn();
        $this->updateLastSeen();
        $this->getBookingsData();

        $title = "Bookings";
        $css = ["movies.css", "bookings.css"];
        require_once('./views/partials/header.php');

        if (isset($this->bookingData)) {
            require_once('./views/bookings/index.php');
        }
        else {
            require_once('./views/error/index.php');
        }

        require_once('./views/partials/footer.php');

        if (isset($_POST['remove'])) {
            $movie_id = $_POST['remove'];
            $this->deleteBooking($movie_id);
            # TODO: Don't refresh, remove movie-item with javascript.
            echo "<script>redirectTo('/cinema/bookings');</script>";
        }
    }
    private function requireSignIn(): void
    {
        $model = new Session($this->conn);
        $model->validateSession();
    }
    public function getBookingsData(): void
    {
        $user_id = $_SESSION['user_id'];
        $model = new Bookings($this->conn);
        $this->bookingData = $model->getBookingsData($user_id);
    }
    public function deleteBooking($movie_id): void
    {
        $user_id = $_SESSION["user_id"];
        $model = new Bookings($this->conn);
        $model->deleteBooking($user_id, $movie_id);
    }
    public function updateLastSeen(): void
    {
        if (isset($_SESSION['user_id'])) {
            $model = new LastSeen($this->conn);
            $model->updateLastSeen();
        }
    }
}