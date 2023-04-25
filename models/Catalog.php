<?php

class Catalog
{
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getMovieData(): false|mysqli_result
    {
        $sql = "SELECT `poster`, `title`, `genre`, `age_limit`, `length`, `movie`.`movie_id`
                FROM `image`, `movie` WHERE `screening`=1 
                AND `image`.`movie_id` = `movie`.`movie_id`
                ORDER BY `movie`.`movie_id` DESC;";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function searchMovies(): false|mysqli_result
    {
        $query = mysqli_real_escape_string($this->conn, $_POST['query']);
        $sql = "SELECT `poster`, `title`, `genre`, `age_limit`, `length`, `movie`.`movie_id` 
                FROM `image`, `movie` 
                WHERE `screening`=1 
                AND `image`.`movie_id` = `movie`.`movie_id`
                AND (`title` LIKE ? OR `genre` LIKE ?)
                ORDER BY `movie`.`movie_id` DESC;";

        $stmt = $this->conn->prepare($sql);
        $query = "%" . $query . "%";
        $stmt->bind_param("ss", $query, $query);
        $stmt->execute();
        return $stmt->get_result();
    }

}