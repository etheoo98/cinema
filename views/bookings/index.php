<main>
    <div class="container">
        <div class="page-info">
            <h1 class="page-title">Bookings</h1>
            <?php
            # If-sats för att kolla om antalet rader är = 0, dvs. om kunden inte har någon biljett.
            if (mysqli_num_rows($bookingsData) == 0) { ?>
                <p class="page-desc">It looks like you haven't made any bookings!</p>
                <p style='text-align:center;'>Explore our wide range of <a href='/cinema/movies' style='color:#a80005;'>movies</a> and book your ticket on the product page.</p>
            <?php
            } else {
            ?>
                <p class="page-desc">These are your current bookings</p>
            <?php } ?>
        </div>
        <?php
        while ($row = mysqli_fetch_array($bookingsData)) {
        ?>

            <div class="movie-list-content" id="booking-<?php echo $row['movie_id']; ?>" data-id="<?php echo $row['movie_id']; ?>">
                <img class="poster" src="/cinema/public/img/posters/<?php echo $row["poster"] ?>" alt="Poster of <?php echo $row['title']?>">
                <div class="movie-list-desc">
                    <a href="/cinema/title/<?php echo $row["movie_id"] ?>">
                        <h3 class="movie-list-title"><?php echo $row["title"] ?></h3>
                    </a>
                    <div class="movie-list-metadata">
                        <ul>
                            <li><?php echo $row["genre"] ?></li>
                            <li><?php echo (floor($row["length"] / 60)) . "h " . $row["length"] % 60 . "min" ?></li>
                        </ul>
                        <span>Booked on <?php echo date('d/m/Y', strtotime($row["date"])); ?></span>

                    </div>
                    <form id="remove-form" method="POST">
                        <button type="submit" class="remove" name="remove" value="<?php echo $row["movie_id"] ?>"><i class="fa-solid fa-trash"></i> Cancel</button>
                    </form>
                </div>
            </div>

        <?php } ?>
    </div>
</main>
<script src="/cinema/public/js/redirect.js"></script>