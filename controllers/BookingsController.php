<?php
require_once (BASE_PATH . '/models/Bookings.php');
require_once (BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/public/scripts/BookingsControllerMiddleware.php');

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
        require_once (BASE_PATH . '/views/shared/header.php');

        if ($this->sessionIsValid && isset($this->bookingsData)) {
            require_once(BASE_PATH . '/views/bookings/index.php');
        }
        else {
            require_once(BASE_PATH . '/views/shared/error.php');
        }
        echo '<script src="/cinema/public/js/bookings.js"></script>';
        require_once(BASE_PATH . '/views/shared/footer.php');
    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;

        switch ($action) {
            case 'remove-booking':
                $response = $this->removeBooking();
                break;
            case 'get-rating':
                $response = $this->ratingData();
                break;
            case 'rate':
                $response = $this->rateMovie();
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

    public function removeBooking(): array
    {
        try {
            $movie_id = mysqli_real_escape_string($this->conn, $_POST['remove']);

            $this->bookingsModel->deleteBooking($movie_id);
            $response = [
                'status' => 'Success',
                'message' => 'Booking Successfully Removed.'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }

    public function ratingData(): array
    {
        $movie_id = mysqli_real_escape_string($this->conn, $_POST['movie_id']);
        $user_id = $_SESSION["user_id"];

        $userRating = $this->bookingsModel->getRatingData($user_id, $movie_id);

        // Return rating value as array
        if ($userRating) {
            return array(
                'success' => true,
                'data' => array(
                    'rating_value' => $userRating
                )
            );
        } else {
            return array(
                'success' => false
            );
        }
    }

    public function rateMovie(): array
    {
        try {
            $movie_id = mysqli_real_escape_string($this->conn, $_POST['movie_id']);
            $rating = mysqli_real_escape_string($this->conn, $_POST['rating']);
            $user_id = $_SESSION["user_id"];

            $this->bookingsModel->validateRatingValue($rating);
            $this->bookingsModel->movieLookup($movie_id);

            $newRating = $this->bookingsModel->ratingLookup($user_id, $movie_id);
            if (!$newRating) {
                $this->bookingsModel->updateRating($user_id, $movie_id, $rating);
                $response = [
                    'status' => 'Success',
                    'message' => 'Rating successfully updated'
                ];
            } else {
                $this->bookingsModel->insertRating($user_id, $movie_id, $rating);
                $response = [
                    'status' => 'Success',
                    'message' => 'Rating successfully inserted'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }
}