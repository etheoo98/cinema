<?php
require_once('./config/dbconnect.php');
require_once('./models/Bookings.php');
require_once('./models/Session.php');
require_once('./models/LastSeen.php');

class BookingsController
{
    private mysqli $conn;
    private false|mysqli_result $bookingData;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $this->requireSignIn();
        $this->updateLastSeen();
        $this->getBookingsData();

        $title = "Bookings";
        $css = ["main.css", "catalog.css", "bookings.css"];
        require_once (dirname(__DIR__) . '/views/shared/header.php');

        if (isset($this->bookingData)) {
            require_once(dirname(__DIR__) . '/views/bookings/index.php');
        }
        else {
            require_once(dirname(__DIR__) . '/views/error/index.php');
        }

        require_once(dirname(__DIR__) . '/views/shared/footer.php');

        if (isset($_POST['remove'])) {
            try {
                $this->deleteBooking();
                # TODO: Don't refresh, remove movie-item with javascript.
                echo "<script>redirectTo('/cinema/bookings');</script>";
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo '<script>alert("We were unable to cancel your booking.")</script>';
            }
        }
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function deleteBooking(): void
    {
        $movie_id = $_POST['remove'];
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