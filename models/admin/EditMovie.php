<?php

class EditMovie
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return array|null
     *
     * This function retrieves data for a specific movie based on its ID from the database, and returns an array of the
     * movie's information if it is found, or null if it is not found.
     *
     */
    public function getMovieData(): ?array
    {
        #TODO: Sanitize
        $movie_id = $_GET['id'];

        $sql = "SELECT * FROM poster, movie WHERE movie.movie_id = ? AND poster.movie_id = movie.movie_id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function getActorData(): ?array {
        $movie_id = mysqli_real_escape_string($this->conn, $_GET['id']);

        $stmt = $this->conn->prepare("SELECT full_name FROM actor, movie_actor WHERE movie_actor.movie_id = ? AND movie_actor.actor_id = actor.actor_id");
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $actors = array(); // initialize empty array for results
        while ($row = $result->fetch_assoc()) {
            $actors[] = $row; // append each row to result array
        }
        return $actors;
    }

    /**
     * @return array
     *
     * This function sanitizes user input by escaping special characters, and if certain files are uploaded, it
     * generates sanitized filenames with unique IDs for those files. It then returns the sanitized input in an array.
     *
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

        $sanitizedInput = [];
        foreach ($_POST as $key => $value) {
            if ($value === 'false') {
                $sanitizedInput[$key] = 0;
            } else if ($value === 'true') {
                $sanitizedInput[$key] = 1;
            } else {
                $sanitizedInput[$key] = stripslashes(mysqli_real_escape_string($this->conn, $value));
            }
        }


        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK
            && isset($_FILES['hero']) && $_FILES['hero']['error'] === UPLOAD_ERR_OK
            && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

            $randomString = uniqid();

            foreach (['poster', 'hero', 'logo'] as $name) {
                $fileName = $_FILES[$name]['name'];
                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', pathinfo($fileName, PATHINFO_FILENAME));
                $fileExt = preg_replace('/[^a-zA-Z0-9]/', '', $fileExt);
                $sanitizedFileName = $fileName . '-' . $randomString . '.' . $fileExt;
                $sanitizedInput[$name] = $sanitizedFileName;
            }
        }

        return $sanitizedInput;
    }

    /**
     * @throws Exception
     *
     * This function validates whether the uploaded images (poster, hero, logo) meet the allowed file types, and throws
     * an exception if the file type is not allowed or the file upload failed.
     *
     */
    public function validateImages(): void
    {
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK
            && isset($_FILES['hero']) && $_FILES['hero']['error'] === UPLOAD_ERR_OK
            && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

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
                        throw new Exception("The file type for '$name' is not allowed. See tooltip for more information.");
                    }
                } else {
                    # File upload failed
                    throw new Exception("Failed to upload '$name'");
                }
            }

            # echo 'Validated Images';

        } else {
            # echo 'No Images Selected';
        }
    }


    /**
     * @param $sanitizedInput
     * @return void
     *
     * This function updates the database record for a movie with the provided sanitized input, but it checks
     * whether there are new images to upload or not. If there are no new images, it prepares and executes an SQL query
     * with the sanitized input to update the movie information. If there are new images, it needs to handle the upload
     * of the new images and update the image file paths in the database accordingly, but that functionality is not yet
     * implemented.
     *
     */
    public function updateMovie($sanitizedInput): void
    {
        if (!isset($sanitizedInput['poster']) || !isset($sanitizedInput['hero']) || !isset($sanitizedInput['logo'])) {
            # SQL Query that excludes image upload
            $sql = 'UPDATE `movie` SET `title` = ?, `description` = ?, `genre` = ?, `premiere` = ?, `age_limit` = ?, `language` = ?, `subtitles` = ?, `length` = ?, `showing` = ? WHERE `movie`.`movie_id` = ?;';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssiisiiii', $sanitizedInput['title'], $sanitizedInput['description'], $sanitizedInput['genre'], $sanitizedInput['premiere'], $sanitizedInput['age_limit'], $sanitizedInput['language'], $sanitizedInput['subtitles'], $sanitizedInput['length'], $sanitizedInput['screening'], $sanitizedInput['movie_id']);
            $stmt->execute();
        }
        else {
            #TODO: Handle new image uploads
            $sql = '';
            # echo 'yes image';
        }
    }

    public function addActorsToMovie($actorIDs): void
    {
        $movie_id = $_GET['id'];

        $sql = 'INSERT INTO `movie_actor` (`id`, `movie_id`, `actor_id`) VALUES (NULL, ?, ?);';
        $checkSql = 'SELECT COUNT(*) FROM `movie_actor` WHERE `movie_id` = ? AND `actor_id` = ?;';
        $stmt = $this->conn->prepare($sql);
        $checkStmt = $this->conn->prepare($checkSql);
        foreach ($actorIDs as $actorID) {
            $checkStmt->bind_param('ii', $movie_id, $actorID);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $count = $checkResult->fetch_array()[0];
            if ($count == 0) {
                $stmt->bind_param('ii', $movie_id, $actorID);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to associate actor(s) with movie.');
                }
            }
        }
        $stmt->close();
        $checkStmt->close();
    }

}