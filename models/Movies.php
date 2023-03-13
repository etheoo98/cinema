<?php

class Movies
{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getMovieData()
    {
        $sql = "SELECT poster, title, genre, age_limit, `length`, movie.movie_id FROM poster, movie WHERE showing=1 AND poster.movie_id = movie.movie_id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}