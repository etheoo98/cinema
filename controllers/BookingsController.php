<?php
require_once (dirname(__DIR__) . '/models/Bookings.php');
require_once (dirname(__DIR__) . '/models/Session.php');

class BookingsController
{
    private mysqli $conn;
    private Session $sessionModel;
    private Bookings $bookingsModel;
    private bool $sessionIsValid;
    private false|mysqli_result $bookingsData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sessionModel = new Session($this->conn);
        $this->bookingsModel = new Bookings($this->conn);
    }

    /**
     * Handles the bookings index page request.
     *
     * This method calls the validateSession method of the Session Model to verify
     * that the current session is recorded as valid in the database and assigns
     * the returned value to $this->sessionIsValid. It then retrieves the bookings
     * data using the booking model's getBookingsData method and assigns it to
     * $this->bookingsData. Finally the renderIndexView method is called to render
     * the index view.
     *
     * @throws Exception
     */
    public function index(): void
    {
        $this->sessionIsValid = $this->sessionModel->validateSession();
        $this->bookingsData = $this->bookingsModel->getBookingsData();
        $this->sessionModel->updateLastSeen();
        $this->renderIndexView();
    }

    /**
     * Renders the bookings index view.
     *
     * View-specific assets, such as CSS files, are included to style the view.
     * If $this->sessionIsValid is true and $this->bookingsData is not NULL,
     * the catalog index view is rendered. Otherwise, an error page is rendered.
     *
     * Note: $this->sessionIsValid can never truly be false, as users are redirected to
     * the sign-in page if their session is invalid, through session model's
     * validateSession method.
     */
    private function renderIndexView(): void
    {
        $title = "Bookings";
        $css = ["main.css", "catalog.css", "bookings.css"];
        require_once (dirname(__DIR__) . '/views/shared/header.php');

        if ($this->sessionIsValid && isset($this->bookingsData)) {
            require_once(dirname(__DIR__) . '/views/bookings/index.php');
        }
        else {
            require_once(dirname(__DIR__) . '/views/shared/error.php');
        }

        require_once(dirname(__DIR__) . '/views/shared/footer.php');

        if (isset($_POST['remove'])) {
            try {
                $this->bookingsModel->deleteBooking();
                # TODO: Don't refresh, remove movie-item with javascript.
                echo "<script>redirectTo('/cinema/bookings');</script>";
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo '<script>alert("We were unable to cancel your booking.")</script>';
            }
        }
    }
}