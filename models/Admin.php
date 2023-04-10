<?php
class Admin {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    function sanitize_text_field( $str ) {
        $filtered = trim( $str );
        $filtered = strip_tags( $filtered );
        $filtered = stripslashes( $filtered );
        $filtered = htmlspecialchars( $filtered, ENT_QUOTES, 'UTF-8' );
        return $filtered;
    }


    /**
     * @throws Exception
     */
    public function sanitizeInput() {
        if (
            !empty($_POST['title']) && !empty($_POST['premiere']) && !empty($_POST['description']) &&
            !empty($_POST['genre']) && !empty($_POST['language']) && !empty($_POST['age_limit']) &&
            !empty($_POST['length']) && !empty($_POST['subtitles']) && !empty($_POST['screening'])
        ) {

            # Sanitize user input
            $title = mysqli_real_escape_string($this->conn, $_POST['title']);
            $premiere = mysqli_real_escape_string($this->conn, $_POST['premiere']);
            $description = mysqli_real_escape_string($this->conn, $_POST['description']);
            $genre = mysqli_real_escape_string($this->conn, $_POST['genre']);
            $language = mysqli_real_escape_string($this->conn, $_POST['language']);
            $age_limit = mysqli_real_escape_string($this->conn, $_POST['age_limit']);
            $length = mysqli_real_escape_string($this->conn, $_POST['length']);
            $subtitles = mysqli_real_escape_string($this->conn, intval($_POST['subtitles']));
            $screening = mysqli_real_escape_string($this->conn, intval($_POST['screening']));

            $randomString = uniqid();
            foreach (['poster', 'hero', 'logo'] as $name) {
                $fileName = $_FILES[$name]['name'];
                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', pathinfo($fileName, PATHINFO_FILENAME));
                $fileExt = preg_replace('/[^a-zA-Z0-9]/', '', $fileExt);
                $sanitizedFileName = $fileName . '-' . $randomString . '.' . $fileExt;
                $sanitizedInput[$name] = $sanitizedFileName;
            }

            echo 'Successfully sanitized all';

            return array(
                'title' => $title,
                'premiere' => $premiere,
                'description' => $description,
                'genre' => $genre,
                'language' => $language,
                'age_limit' => $age_limit,
                'length' => $length,
                'subtitles' => $subtitles,
                'screening' => $screening,
                'poster' => $sanitizedInput['poster'],
                'hero' => $sanitizedInput['hero'],
                'logo' => $sanitizedInput['logo']
            );
        }
        else {
            throw new Exception('Empty Fields');
        }
        echo 'Sanitized input';
    }

    public function sanitizeActors(): array
    {
        $sanitizedActors = array();
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'actor-')) {
                $sanitizedActor = $this->sanitize_text_field($value);
                $sanitizedActors[] = $sanitizedActor;
            }
        }
        return $sanitizedActors;
    }

    /**
     * @throws Exception
     */
    public function validateImage(): void
    {
        # Define allowed file types for each image input
        $allowed_types = [
            'poster' => ['image/jpeg', 'image/webp'],
            'hero' => ['image/jpeg', 'image/webp'],
            'logo' => ['image/png', 'image/webp']
        ];

        # Loop through each image input
        foreach ($allowed_types as $name => $types) {
            if ($_FILES[$name]['error'] == UPLOAD_ERR_OK) {
                $file_type = $_FILES[$name]['type'];

                if (!in_array($file_type, $types)) {
                    # File type is not allowed
                    throw new Exception("The file type for '{$name}' is not allowed. See tooltip for more information.");
                }
            } else {
                # File upload failed
                throw new Exception("Failed to upload '{$name}'");
            }
        }
        echo 'Validated images';
    }


    /**
     * @throws Exception
     */
    public function titleLookup($sanitizedInput) {
        $sql = "SELECT * FROM movie WHERE title=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $sanitizedInput['title']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            throw new Exception('A movie with this name already exists');
        }
        echo 'Title lookup';
    }

    public function actorLookup($sanitizedActors): array
    {
        $sql = 'SELECT `full_name` FROM `actor` WHERE `full_name` = ?;';
        $stmt = $this->conn->prepare($sql);
        $actorsNotFound = [];

        foreach ($sanitizedActors as $key => $actorName) {
            $stmt->bind_param('s', $actorName);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result->num_rows) {
                $actorsNotFound[] = $actorName;
            }
        }
        $actorsFound = array_diff($sanitizedActors, $actorsNotFound);

        return [
            'actorsNotFound' => $actorsNotFound,
            'actorsFound' => $actorsFound
        ];
    }
    public function addTitle($sanitizedInput): int
    {

        $this->conn->begin_transaction();
        try {
            # Insert into table 'movie'
            $sql = "INSERT INTO `movie` (`movie_id`, `title`, `description`, `genre`, `premiere`, `age_limit`, `language`, `subtitles`, `length`, `showing`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssssiiiii', $sanitizedInput['title'], $sanitizedInput['description'], $sanitizedInput['genre'], $sanitizedInput['premiere'], $sanitizedInput['age_limit'], $sanitizedInput['language'], $sanitizedInput['subtitles'], $sanitizedInput['length'], $sanitizedInput['screening']);
            $stmt->execute();
            $movie_id = $this->conn->insert_id;
            $stmt->close();

            # Insert into table 'poster'
            $sql = "INSERT INTO `poster` (`poster_id`, `movie_id`, `poster`, `hero`, `logo`) VALUES (NULL, ?, ?, ?, ?);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('isss', $movie_id, $sanitizedInput['poster'], $sanitizedInput['hero'], $sanitizedInput['logo']);
            $stmt->execute();
            $stmt->close();

            $uploadDir = dirname(__DIR__) . '/public/img/title/';

            # Loop through each image input
            foreach (['poster', 'hero', 'logo'] as $name) {
                $sanitizedFileName = $sanitizedInput[$name];

                # Move the uploaded file to the upload directory with the sanitized name
                if (!move_uploaded_file($_FILES[$name]['tmp_name'], $uploadDir . $name . '/' . $sanitizedFileName)) {
                    throw new Exception('Error: Failed to move file ' . $name);
                }
            }

            $this->conn->commit();
            return $movie_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            header("HTTP/1.0 400 Bad Request");
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function addNewActors(array $actorsObject): void
    {
        $sql = 'INSERT INTO `actor` (`actor_id`, `full_name`) VALUES (NULL, ?);';
        $stmt = $this->conn->prepare($sql);
        foreach ($actorsObject['actorsNotFound'] as $actor) {
            $stmt->bind_param('s', $actor);
            $stmt->execute();
        }
        $stmt->close();
    }

    public function getActorID($actorsObject): array
    {
        $actors = array_merge($actorsObject['actorsFound'], $actorsObject['actorsNotFound']);
        $placeholders = implode(',', array_fill(0, count($actors), '?'));
        $sql = "SELECT `actor_id`, `full_name` FROM `actor` WHERE `full_name` IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($actors)), ...$actors);
        $stmt->execute();
        $result = $stmt->get_result();
        $actorIDs = array();
        while ($row = $result->fetch_assoc()) {
            $actorIDs[$row['full_name']] = $row['actor_id'];
        }
        $stmt->close();
        return $actorIDs;
    }


    public function addActorsToTitle($movie_id, $actorIDs): void
    {
        $sql = 'INSERT INTO `movie_actor` (`id`, `movie_id`, `actor_id`) VALUES (NULL, ?, ?);';
        $stmt = $this->conn->prepare($sql);
        foreach ($actorIDs as $actorID) {
            $stmt->bind_param('ii', $movie_id, $actorID);
            $stmt->execute();
        }
        $stmt->close();
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