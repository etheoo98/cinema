<?php

class Bookings
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getBookingsData($user_id)
    {
        $sql = "SELECT * FROM user, booking, movie, poster WHERE user.user_id= ? AND booking.movie_id = movie.movie_id AND user.user_id = booking.user_id AND poster.movie_id = movie.movie_id AND showing=1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function deleteBooking($user_id, $movie_id)
    {
        $sql = "DELETE FROM booking WHERE user_id = ? AND movie_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);
        if (!$stmt->execute()) {
            return true;
            $stmt->execute();
        }
        else {
            return $stmt->error;
        }
    }
}