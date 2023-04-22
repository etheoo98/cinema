<main>
    <div class="page-info">
        <h1 class="page-title">Movies</h1>
        <p class="page-desc">These movies are currently being screened</p>
    </div>
    <div class="search-bar container">
        <div class="search-order">
            <div class="svg-container">
                <svg class="list-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" onclick="listOrder()">
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z"/>
                </svg>
            </div>
            <div class="svg-container">
                <svg class="column-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" onclick="columnOrder()">
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path d="M224 80c0-26.5-21.5-48-48-48H80C53.5 32 32 53.5 32 80v96c0 26.5 21.5 48 48 48h96c26.5 0 48-21.5 48-48V80zm0 256c0-26.5-21.5-48-48-48H80c-26.5 0-48 21.5-48 48v96c0 26.5 21.5 48 48 48h96c26.5 0 48-21.5 48-48V336zM288 80v96c0 26.5 21.5 48 48 48h96c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48H336c-26.5 0-48 21.5-48 48zM480 336c0-26.5-21.5-48-48-48H336c-26.5 0-48 21.5-48 48v96c0 26.5 21.5 48 48 48h96c26.5 0 48-21.5 48-48V336z"/>
                </svg>
            </div>
        </div>
            <form id="search-form">
                <input type="text" id="search-input" placeholder="Search Title or Genre">
                <button class="search-button" type="submit" id="search-button">
                    <div class="svg-container">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                    </div>
                </button>
            </form>
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