<?php

class Home
{
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * @return false|mysqli_result
     *
     * This function fetches a certain amount of random movies from the database, which will be used in the landing page
     * slideshow.
     *
     */
    public function getMovieData(): false|mysqli_result
    {
        $sql = 'SELECT `movie`.`movie_id`, `title`, `poster`, `hero`, `logo`
                FROM `movie`, `image` 
                WHERE `movie`.`movie_id` = `image`.`movie_id` 
                ORDER BY RAND()
                LIMIT 5;';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
}