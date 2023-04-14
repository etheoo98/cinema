<?php

class Bookings
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return false|mysqli_result
     *
     * This function retrieves all booking data associated with the currently logged-in user, including movie details
     * and poster images.
     *
     */
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
     *
     * This function deletes a booking made by the currently logged-in user for a specific movie.
     *
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

    /**
     * @throws Exception
     *
     * This function takes a user ID and a movie ID as arguments and returns the rating given by the user for
     * the given movie if it exists, otherwise it returns null.
     *
     */
    public function getRatingData($user_id, $movie_id) {
        $sql = 'SELECT `rating`.`rating` FROM `rating`, `user_rating` WHERE `user_rating`.`user_id` = ? AND `rating`.`movie_id` = ? AND `rating`.`rating_id` = `user_rating`.`rating_id`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception('Unable look up existing rating');
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        return $row['rating'];
    }

    /**
     * @throws Exception
     *
     * This function checks the value of $rating and makes sure it's between 1 and 5, as a way to counter potential
     * value manipulation.
     *
     */
    public function validateRatingValue($rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new Exception('Invalid rating value. Rating values must range between 1 and 5.');
        }
    }

    /**
     * @throws Exception
     *
     * This function looks up the requested movie_id in the database.
     *
     */
    public function movieLookup($movie_id): void
    {
        $sql = 'SELECT `movie_id` FROM `movie` WHERE `movie_id` = ?;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $movie_id);

        if (!$stmt->execute()) {
            throw new Exception('Unable look up existing movie.');
        }

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            throw new Exception('Movie does not exist.');
        }
    }

    /**
     * @param $user_id
     * @param $movie_id
     * @return bool
     * @throws Exception
     *
     * This function performs a lookup to check whether the user has already submitted a rating for a specific movie,
     * and returns a boolean indicating whether they have or not.
     *
     */
    public function ratingLookup($user_id, $movie_id): bool
    {
        $sql = 'SELECT `rating`.`rating_id`, `user_id`, `movie_id` FROM `rating`, `user_rating` WHERE `user_rating`.`user_id` = ? AND `rating`.`movie_id` = ?';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception('Unable look up existing rating');
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            # The user already has a rating for this movie_id
            return false;
        } else {
            # The user does not have a rating for this movie_id
            return true;
        }

    }

    /**
     * @param $user_id
     * @param $movie_id
     * @param $rating
     * @return void
     * @throws Exception
     *
     * This function inserts a rating for a specific movie and user into two tables "rating" and "user_rating" using a
     * transaction, and rolls back the transaction in case of any query failure.
     *
     */
    public function insertRating($user_id, $movie_id, $rating): void
    {
        # Start transaction
        $this->conn->begin_transaction();

        try {
            # Insert into "rating" table
            $sql = 'INSERT INTO `rating` (`rating_id`, `movie_id`, `rating`) VALUES (NULL, ?, ?)';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $movie_id, $rating);

            if (!$stmt->execute()) {
                throw new Exception('Unable to insert into rating table');
            }

            # Retrieve last insert ID
            $rating_id = $this->conn->insert_id;

            # Insert into "user_rating" table
            $sql = 'INSERT INTO `user_rating` (`id`, `user_id`, `rating_id`) VALUES (NULL, ?, ?)';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $user_id, $rating_id);

            if (!$stmt->execute()) {
                throw new Exception('Unable to insert into user_rating table');
            }

            # Commit transaction if both queries are successful
            $this->conn->commit();
        } catch (Exception $e) {
            # Roll back transaction if any query fails
            $this->conn->rollback();
            throw $e;
        }
    }

    /**
     * @throws Exception
     *
     * This function updates the rating of an already existing database entry.
     *
     */
    public function updateRating($user_id, $movie_id, $rating): void
    {
        $sql = 'UPDATE `rating`, `user_rating` SET `rating`.`rating` = ? WHERE `user_rating`.`user_id` = ? AND `rating`.`movie_id` = ? AND `rating`.`rating_id` = `user_rating`.`rating_id`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $rating, $user_id, $movie_id);

        if (!$stmt->execute()) {
            throw new Exception('Unable to update rating.');
        }
    }

}