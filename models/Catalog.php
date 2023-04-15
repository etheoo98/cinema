<?php

class Catalog
{
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getMovieData(): false|mysqli_result
    {
        $sql = "SELECT `poster`, `title`, `genre`, `age_limit`, `length`, `movie`.`movie_id` FROM `poster`, `movie` WHERE `showing`=1 AND `poster`.`movie_id` = `movie`.`movie_id` ORDER BY `movie`.`movie_id` DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
}