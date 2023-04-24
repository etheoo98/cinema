<main class="main">
    <div class="slideshow-container">
        <?php while ($row = mysqli_fetch_array($this->movieData)) { ?>

        <div class="mySlides fade">
            <div class="hero-container">
                <img class="movie-hero-image" src="/cinema/public/img/movie/hero/<?php echo $row['hero'] ?>" alt="Hero of <?php echo $row['title']?>" style="width:100%">
            </div>

            <div class="logo-container">
                <a href="/cinema/movie/<?php echo $row['movie_id'] ?>">
                    <img class="movie-logo" src="/cinema/public/img/movie/logo/<?php echo $row['logo'] ?>" alt="Logo of <?php echo $row['title']?>" style="width:100%">
                </a>
            </div>
        </div>

        <?php } ?>

        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </div>

    <div class="container" id="movie-list-container">
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