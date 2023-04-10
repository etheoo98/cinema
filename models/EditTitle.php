<?php

class EditTitle
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getTitleData(): ?array
    {
        $title_id = $_GET['id'];

        $sql = "SELECT * FROM poster, movie WHERE movie.movie_id = ? AND poster.movie_id = movie.movie_id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $title_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function sanitizeInput(): array
    {
        $sanitizedInput = [];
        foreach ($_POST as $key => $value) {
            $sanitizedInput[$key] = mysqli_real_escape_string($this->conn, $value);
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
                        throw new Exception("The file type for '{$name}' is not allowed. See tooltip for more information.");
                    }
                } else {
                    # File upload failed
                    throw new Exception("Failed to upload '{$name}'");
                }
            }

            echo 'Validated Images';

        } else {
            echo 'No Images Selected';
        }
    }


    public function updateTitle($sanitizedInput) {
        if (!isset($sanitizedInput['poster']) || !isset($sanitizedInput['hero']) || !isset($sanitizedInput['logo'])) {
            # SQL Query that excludes image upload
            $sql = 'UPDATE `movie` SET `title` = ?, `description` = ?, `genre` = ?, `premiere` = ?, `age_limit` = ?, `language` = ?, `subtitles` = ?, `length` = ?, `showing` = ? WHERE `movie`.`movie_id` = ?;';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssiisiiii', $sanitizedInput['title'], $sanitizedInput['description'], $sanitizedInput['genre'], $sanitizedInput['premiere'], $sanitizedInput['age_limit'], $sanitizedInput['language'], $sanitizedInput['subtitles'], $sanitizedInput['length'], $sanitizedInput['screening'], $sanitizedInput['movie_id']);
            $stmt->execute();
        }
        else {
            $sql = '';
            echo 'yes image';
        }
    }
}