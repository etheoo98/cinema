<main style="background-image:
        linear-gradient(0deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)),
        url('../public/img/hero/<?php echo $this->titleData["poster"] ?>');">
    <div class="container">
        <div class="movie-list-content">
            <img class="poster" src="../public/img/posters/<?php echo $this->titleData["poster"] ?>" alt="Poster of <?php echo $this->titleData['title'] ?>">
            <div class="movie-list-desc">
                <div class="movie-title-area">
                    <h1 class="title"><?php echo $this->titleData["title"]; ?> <span class="premiere">(<?php echo $this->titleData["premiere"]; ?>)</span></h1>
                    <div class="rating-area-outer">
                        <div class="rating-area-inner">
                            <p>User Rating</p>
                            <?php if (!empty($this->RatingData) && $this->RatingData["avg_rating"] != 0) {
                                $avg_rating = $this->RatingData["avg_rating"] . " / 5.0";
                                $count_rating = $this->RatingData["count_rating"];
                            } else {
                                $avg_rating = "Not Yet Rated";
                                $count_rating = "0";
                            }
                            ?>
                            <p><?php echo $avg_rating ?></p>
                            <p><i class='fas fa-users'></i><?php echo $count_rating ?></p>
                        </div>
                    </div>
                </div>
                <p class="movie-list-metadata"><?php echo $this->titleData["description"]; ?></p>
                <ul class="movie-list-metadata">
                    <li><?php echo $this->titleData["genre"] ?></li>
                    <li><?php echo (floor($this->titleData["length"] / 60)) ?>h <?php echo $this->titleData["length"] % 60 ?>min</li>
                    <li>From <?php echo $this->titleData["age_limit"] ?> years</li>
                </ul>
                <div class='movie-list-metadata actors'>
                    <p>Starring:</p>
                    <ul class='starring-list'>
                        <?php foreach ($this->actorData as $actor) : ?>
                            <li><?php echo $actor['full_name']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <form class="booking-form" method="post">
                    <button type="submit" id="booking" name="book" value="<?php echo $this->titleData["movie_id"] ?>">Book ticket</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="/cinema/public/js/redirect.js"></script>