<?php
class Admin {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    # Edit-movie.php
    public function getTitleData(): false|mysqli_result
    {
        $sql = "SELECT title, movie.movie_id FROM movie";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getEditTitleData(): false|mysqli_result
    {
        $sql = "SELECT `movie`.`movie_id`, `poster`, `title`, `genre`, `age_limit`, `length`, `showing` FROM poster, movie WHERE poster.movie_id = movie.movie_id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getRequestedTitleData(): array
    {
        $title_id = $_POST['title_id'];

        $sql = "SELECT * FROM poster, movie WHERE movie.movie_id = ? AND poster.movie_id = movie.movie_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $title_id); // "i" indicates the parameter type (integer), followed by the parameter value
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}