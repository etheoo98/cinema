<?php

class Title
{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getTitleData($title_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM poster, movie WHERE movie.movie_id = ? AND movie.movie_id = poster.movie_id AND showing=1");
        $stmt->bind_param('i', $title_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();


        #$sql = "SELECT * FROM poster, movie WHERE movie.movie_id = $movie_id AND movie.movie_id = poster.movie_id AND showing=1";
        #$sql1 = "SELECT full_name FROM actor, movie_actor WHERE movie_actor.movie_id = $movie_id AND movie_actor.actor_id = actor.actor_id";
        #$sql2 = "SELECT ROUND(AVG(rating), 1), COUNT(rating) FROM rating WHERE movie_id= $movie_id";
        #$title_data = $this->conn->query($sql);
        #$rating_data = $this->conn->query($sql2);
        #return $title_data;
    }

    public function getRatingData($title_id) {
        $stmt = $this->conn->prepare("SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(rating) AS count_rating FROM rating WHERE movie_id = ?");
        $stmt->bind_param('i', $title_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getActorData($title_id) {
        $stmt = $this->conn->prepare("SELECT full_name FROM actor, movie_actor WHERE movie_actor.movie_id = ? AND movie_actor.actor_id = actor.actor_id");
        $stmt->bind_param('i', $title_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $actors = array(); // initialize empty array for results
        while ($row = $result->fetch_assoc()) {
            $actors[] = $row; // append each row to result array
        }
        return $actors;
    }
    public function duplicateCheck($user_id, $movie_id) {
        $stmt = $this->conn->prepare("SELECT * FROM `booking` WHERE user_id = ? AND movie_id = ?");
        if (!$stmt) {
            return $this->conn->error;
        }
        $stmt->bind_param('ii', $user_id, $movie_id);
        if (!$stmt->execute()) {
            return $stmt->error;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true; // duplicate record found
        } else {
            return false; // no duplicate record found
        }
    }    


    public function addBooking($user_id, $movie_id) {
        $stmt = $this->conn->prepare("INSERT INTO booking (booking_id, user_id, movie_id, `date`) VALUES (NULL, ?, ?, NOW())");
        if (!$stmt) {
            return $this->conn->error;
        }
        $stmt->bind_param('ii', $user_id, $movie_id);
        if (!$stmt->execute()) {
            return $stmt->error;
        }
        return true;
        $stmt->execute();
    }
}
?>