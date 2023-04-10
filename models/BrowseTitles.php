<?php

class BrowseTitles {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getTitleData(): false|mysqli_result
    {
        $sql = "SELECT `movie`.`movie_id`, `poster`, `title`, `genre`, `age_limit`, `length`, `showing` FROM poster, movie WHERE poster.movie_id = movie.movie_id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
}