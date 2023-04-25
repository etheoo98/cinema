<main class="main">
    <header class="movie-header">
        <div class="hero-container">
            <img class="movie-hero-image" src="../public/img/movie/hero/<?php echo $this->movieData["hero"] ?>" alt="Hero image of <?php echo $this->movieData["title"]; ?>">
        </div>
        <div class="movie-main">
            <div class="container">
                <div class="poster-container">
                    <img class="poster" src="../public/img/movie/poster/<?php echo $this->movieData["poster"] ?>" alt="Poster of <?php echo $this->movieData['title'] ?>">
                </div>
                <div class="movie-info">
                    <h1 class="title"><?php echo $this->movieData["title"]; ?></h1>
                    <ul>
                        <li><?php echo $this->movieData["genre"] ?></li>
                        <li><?php echo (floor($this->movieData["length"] / 60)) ?>h <?php echo $this->movieData["length"] % 60 ?>min</li>
                        <li>From <?php echo $this->movieData["age_limit"] ?> years</li>
                    </ul>
                </div>
                <div class="book-container">
                    <form id="add-booking-form">
                        <input type="hidden" name="movie_id" value="<?php echo $this->movieData["movie_id"]; ?>">
                        <button type="submit" class="button">Book Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <div class="movie-body">
        <div class="container">

            <div class="movie-metadata">
                <p class="movie-description"><?php echo $this->movieData["description"]; ?></p>
            </div>

            <div class="movie-metadata">
                <h3>Starring:</h3>
                <ul class="movie-starring-list">
                    <?php foreach ($this->actorData as $actor) : ?>
                        <li><?php echo $actor['full_name']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="movie-metadata">
                <h3>User Rating:</h3>
                <?php if (!empty($this->ratingData) && $this->ratingData["avg_rating"] != 0) { ?>
                    <div class="rating-container">
                        <span><?php echo $this->ratingData["avg_rating"]; ?> / 5.0</span>
                        <div class="svg-container">
                            <svg id="star-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>
                        </div>
                    </div>
                <?php } else { ?>
                    <span>Not Yet Rated</span>
                <?php } ?>
            </div>

            <div class="movie-metadata">
                <h3>Language:</h3>
                <p class="movie-description"><?php echo $this->movieData["language"]; ?></p>
            </div>

            <div class="movie-metadata">
                <h3>Subtitles:</h3>
                <p class="movie-description"><?php echo $this->movieData["subtitles"]; ?></p>
            </div>

            <div class="movie-metadata">
                <h3>Premiere:</h3>
                <p class="movie-description"><?php echo $this->movieData["premiere"]; ?></p>
            </div>

        </div>
    </div>
</main>