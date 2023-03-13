    <main style="background-image:
    linear-gradient(0deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)),
    url('../public/img/hero/<?php echo $TitleData["poster"] ?>');" ;>
        <div class="container">
            <div class="movie-list-content">
                <img class="poster" src="../public/img/posters/<?php echo $TitleData["poster"] ?>">
                <div class="movie-list-desc">
                    <div class="movie-title-area">
                        <h1 class="title"><?php echo $TitleData["title"]; ?> <span class="premiere">(<?php echo $TitleData["premiere"]; ?>)</span></h1>
                        <div class="rating-area">
                            <div class="rating-area2">
                                <p>User Rating</p>
                                <?php if (!empty($RatingData) && $RatingData["avg_rating"] != 0) {
                                    $avg_rating = $RatingData["avg_rating"] . " / 5.0";
                                    $count_rating = $RatingData["count_rating"];
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
                    <p class="movie-list-metadata"><?php echo $TitleData["description"]; ?></p>
                    <ul class="movie-list-metadata">
                        <li><?php echo $TitleData["genre"] ?></li>
                        <li><?php echo (floor($TitleData["length"] / 60)) ?>h <?php echo $TitleData["length"] % 60 ?>min</li>
                        <li>From <?php echo $TitleData["age_limit"] ?> years</li>
                    </ul>
                    <div class='movie-list-metadata actors'>
                        <p>Starring:</p>
                        <ul class='starring-list'>
                            <?php foreach ($ActorData as $actor) : ?>
                                <li><?php echo $actor['full_name']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <form class="booking-form" method="post">
                        <button type="submit" id="booking" name="book" value="<?php echo $TitleData["movie_id"] ?>">Book ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="/cinema/public/js/redirect.js"></script>