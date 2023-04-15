<main>
    <div class="page-info">
        <h1 class="page-title">Movies</h1>
        <p class="page-desc">These movies are currently being screened</p>
    </div>
    <div class="container">
        <?php
        while ($row = mysqli_fetch_array($this->movieData)) {
            ?>
            <div class="movie-list-content">
                <img class="poster" src="/cinema/public/img/movie/poster/<?php echo $row["poster"] ?>" alt="Poster of <?php echo $row['title']?>"    >
                <div class="movie-list-desc">
                    <a href="/cinema/movie/<?php echo $row["movie_id"] ?>">
                        <h3 class="movie-list-title"><?php echo $row["title"] ?></h3>
                    </a>
                    <div class="movie-list-metadata">
                        <ul>
                            <li><?php echo $row["genre"] ?></li>
                            <li><?php echo (floor($row["length"] / 60)) . "h " . $row["length"] % 60 . "min" ?></li>
                            <li>From <?php echo $row["age_limit"] ?> years</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>