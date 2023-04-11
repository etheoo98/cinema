<?php
class Admin {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    function sanitize_text_field( $str ): string
    {
        $filtered = trim( $str );
        $filtered = strip_tags( $filtered );
        $filtered = stripslashes( $filtered );
        return htmlspecialchars( $filtered, ENT_QUOTES, 'UTF-8' );
    }


    /**
     * @throws Exception
     */
    public function sanitizeInput(): array
    {
        # Look for missing input fields
        $requiredFields = array('title', 'premiere', 'description', 'genre', 'language', 'age_limit', 'length', 'subtitles', 'screening', 'actor-1');
        $missingFields = array();

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }

        if (count($missingFields) > 0) {
            $missingFieldsStr = implode(', ', $missingFields);
            throw new Exception("Missing fields: $missingFieldsStr");
        }


        # Sanitize user input
        $sanitizedInput = array();
        foreach ($_POST as $key => $value) {
            if ($key === 'subtitles' || $key === 'screening') {
                $sanitizedInput[$key] = mysqli_real_escape_string($this->conn, intval($value));
            } else {
                $sanitizedInput[$key] = mysqli_real_escape_string($this->conn, $value);
            }
        }

        # Sanitize image file names and give the name a random string, as to not cause conflicts
        $randomString = uniqid();

        foreach (['poster', 'hero', 'logo'] as $name) {
            $fileName = $_FILES[$name]['name'];
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', pathinfo($fileName, PATHINFO_FILENAME));
            $fileExt = preg_replace('/[^a-zA-Z0-9]/', '', $fileExt);
            $sanitizedFileName = $fileName . '-' . $randomString . '.' . $fileExt;
            $sanitizedInput[$name] = $sanitizedFileName;
        }

        return $sanitizedInput;
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
                    throw new Exception("File type for '{$name}' is not allowed.");
                }
            } else {
                # File upload failed
                throw new Exception("Failed to upload '{$name}'");
            }
        }
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
    }

    /**
     * @throws Exception
     */
    public function actorLookup($sanitizedActors): array
    {
        $sql = 'SELECT `full_name` FROM `actor` WHERE `full_name` = ?;';
        $stmt = $this->conn->prepare($sql);
        $actorsNotFound = [];

        foreach ($sanitizedActors as $key => $actorName) {
            $stmt->bind_param('s', $actorName);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
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
            $stmt->bind_param('sssiisiii', $sanitizedInput['title'], $sanitizedInput['description'], $sanitizedInput['genre'], $sanitizedInput['premiere'], $sanitizedInput['age_limit'], $sanitizedInput['language'], $sanitizedInput['subtitles'], $sanitizedInput['length'], $sanitizedInput['screening']);
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
                    throw new Exception('Failed to move file ' . $name);
                }
            }

            $this->conn->commit();
            return $movie_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            header("HTTP/1.0 400 Bad Request");
            echo "Unable to add title to database: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * @throws Exception
     */
    public function addNewActors(array $actorsObject): void
    {
        $sql = 'INSERT INTO `actor` (`actor_id`, `full_name`) VALUES (NULL, ?);';
        $stmt = $this->conn->prepare($sql);
        foreach ($actorsObject['actorsNotFound'] as $actor) {
            $stmt->bind_param('s', $actor);
            if (!$stmt->execute()) {
                throw new Exception('Failed to add actor ' . $actor . ' to database.');
            }
        }
        $stmt->close();
    }

    /**
     * @throws Exception
     */
    public function getActorID($actorsObject): array
    {
        $actors = array_merge($actorsObject['actorsFound'], $actorsObject['actorsNotFound']);
        $placeholders = implode(',', array_fill(0, count($actors), '?'));
        $sql = "SELECT `actor_id`, `full_name` FROM `actor` WHERE `full_name` IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($actors)), ...$actors);
        if (!$stmt->execute()) {
            throw new Exception('Failed to retrieve actor ID(s) from database.');
        }

        $result = $stmt->get_result();
        $actorIDs = array();

        while ($row = $result->fetch_assoc()) {
            $actorIDs[$row['full_name']] = $row['actor_id'];
        }

        $stmt->close();
        return $actorIDs;
    }


    /**
     * @throws Exception
     */
    public function addActorsToTitle($movie_id, $actorIDs): void
    {
        $sql = 'INSERT INTO `movie_actor` (`id`, `movie_id`, `actor_id`) VALUES (NULL, ?, ?);';
        $stmt = $this->conn->prepare($sql);
        foreach ($actorIDs as $actorID) {
            $stmt->bind_param('ii', $movie_id, $actorID);
            if (!$stmt->execute()) {
                throw new Exception('Failed to associate actor(s) with movie.');
            }
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