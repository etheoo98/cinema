<?php
class Admin {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
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
    public function addTitle($sanitizedInput): void
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
        } catch (Exception $e) {
            $this->conn->rollback();
            header("HTTP/1.0 400 Bad Request");
            echo "Error: " . $e->getMessage();
        }
    }
}