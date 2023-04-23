<?php

class AddMovie
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param $str
     * @return string
     *
     * This method takes a string as input, removes any leading/trailing white space, removes any HTML tags, removes
     * any slashes, and returns the resulting string with any special characters encoded.
     *
     */
    function sanitize_text_field( $str ): string
    {
        $filtered = trim( $str );
        $filtered = strip_tags( $filtered );
        $filtered = stripslashes( $filtered );
        return htmlspecialchars( $filtered, ENT_QUOTES, 'UTF-8' );
    }


    /**
     * @throws Exception
     *
     * This function checks if all the required fields for a form have been filled out, and throws an
     * exception with a message listing the missing fields if any are found.
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
            if ($value === 'false') {
                $sanitizedInput[$key] = 0;
            } else if ($value === 'true') {
                $sanitizedInput[$key] = 1;
            } else {
                $sanitizedInput[$key] = stripslashes(mysqli_real_escape_string($this->conn, $value));
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

    /**
     * @return array
     *
     * This function sanitizes the input values that start with the prefix "actor-" and returns
     * an array of sanitized actors.
     *
     */
    public function sanitizeActors(): array
    {
        $sanitizedActors = array();
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'actor-') && !empty($value)) {
                $sanitizedActor = $this->sanitize_text_field($value);
                $sanitizedActors[] = $sanitizedActor;
            }
        }
        return $sanitizedActors;
    }

    /**
     * @throws Exception
     *
     * This is a function for validating image files. It checks whether the uploaded image files
     * are of the allowed file types (JPEG, PNG, WEBP) for each image input (poster, hero, logo).
     * If the uploaded file type is not allowed or the file upload fails, it throws an exception
     * with an appropriate error message.
     *
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
                    throw new Exception("File type for '$name' is not allowed.");
                }
            } else {
                # File upload failed
                throw new Exception("Failed to upload '$name'");
            }
        }
    }


    /**
     * @throws Exception
     *
     * This function searches the movie database to check if a movie with the same movie as
     * the sanitized input already exists. If it exists, it throws an exception with a message
     * stating that a movie with that movie already exists.
     *
     */
    public function movieLookup($sanitizedInput): void
    {
        $sql = "SELECT * FROM movie
                WHERE title = ?";

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
     *
     * This function performs an actor lookup by querying the database to check if the specified actors exist.
     *
     * It takes an array of sanitized actor names as input, and uses a prepared statement to execute a SELECT query
     * on a database table called actor. For each actor name in the input array, the function checks if the query
     * executed successfully and if the result set contains any rows. If an actor is not found in the database, their
     * name is added to an array called actorsNotFound. Finally, the function returns an array containing two elements:
     * actorsNotFound, which is an array of actors not found in the database, and actorsFound, which is an array of actors
     * that were found in the database.
     *
     */
    public function actorLookup($sanitizedActors): array
    {
        $sql = 'SELECT `full_name`
                FROM `actor`
                WHERE `full_name` = ?;';

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

    /**
     * @param $sanitizedInput
     * @return int
     *
     * This function is a method that adds a new movie to the database. It starts by beginning a database
     * transaction, and then executes multiple SQL queries using prepared statements to insert data into the 'movie'
     * and 'poster' tables. The function also moves uploaded image files to a specified directory on the server. If any
     * part of the process fails, a rollback is initiated and an error message is displayed. The function returns the ID
     * of the newly inserted movie or 0 if an error occurs.
     *
     */
    public function addMovie($sanitizedInput): int
    {

        $this->conn->begin_transaction();
        try {
            # Insert into table 'movie'
            $sql = "INSERT INTO `movie` (`movie_id`, `title`, `description`, `genre`, `premiere`, `age_limit`, `language`, `subtitles`, `length`, `screening`)
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssiisiii', $sanitizedInput['title'], $sanitizedInput['description'], $sanitizedInput['genre'], $sanitizedInput['premiere'], $sanitizedInput['age_limit'], $sanitizedInput['language'], $sanitizedInput['subtitles'], $sanitizedInput['length'], $sanitizedInput['screening']);
            if(!$stmt->execute()) {
                throw new Exception("Failed to execute query insert into movie: " . $stmt->error);
            }

            $movie_id = $this->conn->insert_id;
            $stmt->close();

            # Insert into table 'image'
            $sql = "INSERT INTO `image` (`poster_id`, `movie_id`, `poster`, `hero`, `logo`)
                    VALUES (NULL, ?, ?, ?, ?);";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('isss', $movie_id, $sanitizedInput['poster'], $sanitizedInput['hero'], $sanitizedInput['logo']);
            if(!$stmt->execute()) {
                throw new Exception("Failed to execute query insert into poster: " . $stmt->error);
            }
            $stmt->close();

            $uploadDir = BASE_PATH . '/public/img/movie/';

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
            echo "Unable to add movie to database: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * @throws Exception
     *
     * This function adds new actors to the database if they are not already present.
     *
     */
    public function addNewActors(array $actorsObject): void
    {
        $sql = 'INSERT INTO `actor` (`actor_id`, `full_name`)
                VALUES (NULL, ?);';

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
     *
     * This function takes an array of actors, queries the database for their corresponding actor IDs, and returns an
     * associative array of the actors' names and IDs.
     *
     */
    public function getActorID($actorsObject): array
    {
        $actors = array_merge($actorsObject['actorsFound'], $actorsObject['actorsNotFound']);
        $placeholders = implode(',', array_fill(0, count($actors), '?'));
        $sql = "SELECT `actor_id`, `full_name`
                FROM `actor`
                WHERE `full_name`
                IN ($placeholders)";

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
     *
     * This function inserts the IDs of actors associated with a given movie ID into a junction table called movie_actor.
     *
     */
    public function addActorsToMovie($movie_id, $actorIDs): void
    {
        $sql = 'INSERT INTO `movie_actor` (`id`, `movie_id`, `actor_id`)
                VALUES (NULL, ?, ?);';

        $stmt = $this->conn->prepare($sql);
        foreach ($actorIDs as $actorID) {
            $stmt->bind_param('ii', $movie_id, $actorID);
            if (!$stmt->execute()) {
                throw new Exception('Failed to associate actor(s) with movie.');
            }
        }
        $stmt->close();
    }

}