<?php

class Movie
{
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getMovieData($movie_id): false|array|null
    {
        $sql = 'SELECT * FROM `image`, `movie`
                WHERE `movie`.`movie_id` = ?
                AND `movie`.`movie_id` = `image`.`movie_id`
                AND `screening` = 1;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data && isset($data['subtitles'])) {
            $data['subtitles'] = $data['subtitles'] == 1 ? 'English' : 'None';
        }

        return $data;
    }

    public function getRatingData($movie_id): false|array|null
    {
        $sql = 'SELECT ROUND(AVG(rating), 1) AS avg_rating,
                COUNT(rating) AS count_rating
                FROM rating
                WHERE movie_id = ?;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getActorData($movie_id): array
    {
        $sql = 'SELECT full_name
                FROM actor, movie_actor
                WHERE movie_actor.movie_id = ?
                AND movie_actor.actor_id = actor.actor_id;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $actors = array(); // initialize empty array for results
        while ($row = $result->fetch_assoc()) {
            $actors[] = $row; // append each row to result array
        }
        return $actors;
    }

    /**
     * @throws Exception
     */
    public function duplicateCheck($user_id, $movie_id): void
    {
        $sql = 'SELECT * FROM `booking`
                WHERE user_id = ?
                AND movie_id = ?;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute duplicate check.");
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("You already have a ticket for this movie.");
        }
    }


    /**
     * @throws Exception
     */
    public function addBooking($user_id, $movie_id): void
    {
        $sql = 'INSERT INTO booking (booking_id, user_id, movie_id, `date`)
                VALUES (NULL, ?, ?, NOW());';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add new booking.");
        }
    }
}