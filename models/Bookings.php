<?php

class Bookings
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getBookingsData(): false|mysqli_result
    {
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM user, booking, movie, poster WHERE user.user_id= ? AND booking.movie_id = movie.movie_id AND user.user_id = booking.user_id AND poster.movie_id = movie.movie_id AND showing=1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * @throws Exception
     */
    public function deleteBooking($movie_id): void
    {
        $user_id = $_SESSION["user_id"];

        $sql = "DELETE FROM booking WHERE user_id = ? AND movie_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception('Unable to delete booking.');
        }
    }

}