<div class="update-title-container">
    <form class="admin-form" id="edit-title-form" enctype="multipart/form-data" data-action="edit-title" method="POST">
        <input type="hidden" name="movie_id" value="<?php echo $this->titleData['movie_id']; ?>">
        <div class="add-movie-container">
            <div class="input-row">

                <div class="input-column" id="title-column">
                    <label for="title">Title</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Title of the movie.</span>
                    </div>
                    <input type="text" name="title" id="title" autocomplete="off" value="<?php echo $this->titleData['title'] ?>">
                </div>

                <div class="input-column" id="premiere-column">
                    <label for="premiere">Premiere
                        <div class="tooltip">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                            <span class="tooltiptext">Year of the premiere.</span>
                        </div>
                    </label>
                    <input id="premiere" name="premiere" type="number" min="1900" max="2050" pattern="[0-9]*" value="<?php echo $this->titleData['premiere'] ?>">
                </div>

            </div>

            <div class="input-row">
                <div class="input-column">
                    <label for="description">Description</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Movie description.</span>
                    </div>
                    <textarea name="description" id="description"><?php echo $this->titleData['description'] ?></textarea>
                </div>
            </div>
            <div class="input-row" id="metadata-row">
                <div class="input-column">
                    <label for="genre">Genre</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Eg., Action, Drama, Sci-fi/Adventure.</span>
                    </div>
                    <input type="text" name="genre" id="genre" autocomplete="off" value="<?php echo $this->titleData['genre'] ?>">
                </div>
                <div class="input-column">
                    <label for="length">Length</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">The movie length in minutes.</span>
                    </div>
                    <input type="text" name="length" id="length" autocomplete="off" value="<?php echo $this->titleData['length'] ?>">
                </div>
                <div class="input-column">
                    <label for="language">Language</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Original language, e.g., English, German, French.</span>
                    </div>
                    <input id="language" name="language" type="text" autocomplete="off" value="<?php echo $this->titleData['language'] ?>">
                </div>
                <div class="input-column">
                    <label for="age-limit">Age Limit</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Recommended viewer age. Based on movie rating.</span>
                    </div>
                    <input id="age-limit" name="age_limit" type="text" autocomplete="off" value="<?php echo $this->titleData['age_limit'] ?>">
                </div>
            </div>
            <div class="input-row" id="subtitles">
                <div class="input-column">
                    <label for="title">Subtitles</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Do we offer subtitles for this movie?</span>
                    </div>
                    <div>
                        <input type="radio" id="subtitles-yes" name="subtitles" value="1" <?php if ($this->titleData['subtitles'] === 1) echo 'checked'; ?>>
                        <label for="subtitles-yes">Yes</label>
                        <input type="radio" id="subtitles-no" name="subtitles" value="0" <?php if ($this->titleData['subtitles'] === 0) echo 'checked'; ?>>
                        <label for="subtitles-no">No</label>
                    </div>
                </div>
            </div>
            <div class="input-row" id="subtitles">
                <div class="input-column">
                    <label for="title">Screening</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltiptext">Is this movie currently being screened? This will affect the visibility of the entry.</span>
                    </div>
                    <div>
                        <input type="radio" id="screening-yes" name="screening" value="1" <?php if ($this->titleData['showing'] === 1) echo 'checked'; ?>>
                        <label for="screening-yes">Yes</label>
                        <input type="radio" id="screening-no" name="screening" value="0" <?php if ($this->titleData['showing'] === 0) echo 'checked'; ?>>
                        <label for="screening-no">No</label>
                    </div>
                </div>
            </div>
            <div class="input-row">
                <button id="submit-btn" type="submit" class="btn">Update Movie</button>
            </div>
        </div>
        <div class="admin-form-right">
            <div class="input-row">
                <label>Poster</label>
                <img class="poster" id="poster-img" src="/cinema/public/img/title/poster/<?php echo $this->titleData['poster'] ?>">
                <input type="file" name="poster" id="poster" onchange="onFileSelected(event, 'poster-img')" accept="image/webp, image/jpeg">
            </div>
            <div class="input-row">
                <label>Hero</label>
                <img class="poster" id="hero-img" src="/cinema/public/img/title/hero/<?php echo $this->titleData['hero'] ?>">
                <input type="file" name="hero" id="hero" onchange="onFileSelected(event, 'hero-img')" accept="image/webp, image/jpeg">
            </div>
            <div class="input-row">
                <label>Logo</label>
                <img class="poster" id="logo-img" src="/cinema/public/img/title/logo/<?php echo $this->titleData['logo'] ?>">
                <input type="file" name="logo" id="logo" onchange="onFileSelected(event, 'logo-img')" accept="image/webp, image/png">
            </div>
        </div>
    </form>
</div>
</div>
</main>
</div>