<main class="main">
    <header class="movie-header">
        <div class="hero-container">
            <img class="movie-hero-image" src="../public/img/title/hero/<?php echo $this->titleData["hero"] ?>">
        </div>
        <div class="movie-main">
            <div class="container">
                <div class="poster-container">
                    <img class="poster" src="../public/img/title/poster/<?php echo $this->titleData["poster"] ?>" alt="Poster of <?php echo $this->titleData['title'] ?>">
                </div>
                <div class="movie-info">
                    <h1 class="title"><?php echo $this->titleData["title"]; ?></h1>
                    <ul>
                        <li><?php echo $this->titleData["genre"] ?></li>
                        <li><?php echo (floor($this->titleData["length"] / 60)) ?>h <?php echo $this->titleData["length"] % 60 ?>min</li>
                        <li>From <?php echo $this->titleData["age_limit"] ?> years</li>
                    </ul>
                </div>
                <div class="book-container">
                    <form id="add-booking-form" data-action="add-booking">
                        <input type="hidden" name="movie_id" value="<?php echo $this->titleData["movie_id"]; ?>">
                        <button type="submit" class="button">Book Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <div class="movie-body">
        <div class="container">
            <div class="movie-metadata">
                <p class="movie-description"><?php echo $this->titleData["description"]; ?></p>
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
                <h3>Language:</h3>
                <p class="movie-description"><?php echo $this->titleData["language"]; ?></p>
            </div>
            <div class="movie-metadata">
                <h3>Subtitles:</h3>
                <p class="movie-description">Yes/No</p>
            </div>
            <div class="movie-metadata">
                <h3>Premiere:</h3>
                <p class="movie-description"><?php echo $this->titleData["premiere"]; ?></p>
            </div>
        </div>
    </div>
</main>