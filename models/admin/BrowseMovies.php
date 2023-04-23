<?php

class BrowseMovies {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return false|mysqli_result
     *
     * This function executes a SQL query to select data from the 'poster' and 'movie' tables, ordering the results by
     * the movie ID in descending order, and returns a false value or a mysqli_result object representing the result set.
     *
     */
    public function getMovieData(): false|mysqli_result
    {
        $sql = "SELECT `movie`.`movie_id`, `poster`, `title`, `genre`, `age_limit`, `length`, `showing` FROM `poster`, `movie` WHERE `poster`.`movie_id` = `movie`.`movie_id` ORDER BY `movie`.`movie_id` DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function searchMovies(): false|mysqli_result
    {
        $query = $_POST['query'];
        $sql = "SELECT `poster`, `title`, `genre`, `age_limit`, `length`, `showing`, `movie`.`movie_id` 
            FROM `poster`, `movie` 
            WHERE `poster`.`movie_id` = `movie`.`movie_id`
            AND (`title` LIKE ? OR `genre` LIKE ?)
            ORDER BY `movie`.`movie_id` DESC;";

        $stmt = $this->conn->prepare($sql);
        $query = "%" . $query . "%"; // wrap search term with % so it matches partial strings
        $stmt->bind_param("ss", $query, $query);
        $stmt->execute();
        return $stmt->get_result();
    }
}