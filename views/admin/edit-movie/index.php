<form class="admin-form" id="edit-movie-form" enctype="multipart/form-data" data-action="edit-movie" method="POST">
    <input type="hidden" name="movie_id" value="<?php echo $this->movieData['movie_id']; ?>">
    <div class="add-movie-container">
        <div class="input-row">

            <div class="input-column" id="title-column">
                <div class="input-label">
                    <label for="title">Title</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter Title of the movie.</span>
                    </div>
                </div>
                <input type="text" name="title" id="title" autocomplete="off" value="<?php echo $this->movieData['title'] ?>">
            </div>

            <div class="input-column" id="premiere-column">
                <div class="input-label">
                    <label for="premiere">Premiere</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter the premiere of the movie.</span>
                    </div>
                </div>
                <input id="premiere" name="premiere" type="number" min="1900" max="2050" pattern="[0-9]*" value="<?php echo $this->movieData['premiere'] ?>">
            </div>

        </div>

        <div class="input-row">
            <div class="input-column">
                <div class="input-label">
                    <label for="description">Description</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter the description of the movie.</span>
                    </div>
                </div>
                <textarea name="description" id="description"><?php echo $this->movieData['description'] ?></textarea>
            </div>
        </div>

        <div class="input-row" id="metadata-row">
            <div class="input-column">
                <div class="input-label">
                    <label for="genre">Genre</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter the genre of the movie: e.g. Action, Family/Drama.</span>
                    </div>
                </div>
                <input type="text" name="genre" id="genre" autocomplete="off" value="<?php echo $this->movieData['genre'] ?>">
            </div>
            <div class="input-column">
                <div class="input-label">
                    <label for="length">Length</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter the length of the movie in minutes.</span>
                    </div>
                </div>
                <input type="text" name="length" id="length" autocomplete="off" value="<?php echo $this->movieData['length'] ?>">
            </div>
            <div class="input-column">
                <div class="input-label">
                    <label for="language">Language</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Enter the primary language spoken in the movie.</span>
                    </div>
                </div>
                <input id="language" name="language" type="text" autocomplete="off" value="<?php echo $this->movieData['language'] ?>">
            </div>
            <div class="input-column">
                <div class="input-label">
                    <label for="age_limit">Age Restriction</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Age limit in years without a parent present.</span>
                    </div>
                </div>
                <input id="age-limit" name="age_limit" type="text" autocomplete="off" value="<?php echo $this->movieData['age_limit'] ?>">
            </div>
        </div>
        <div class="input-row" id="subtitles">
                <div class="input-label">
                    <label for="subtitles">Subtitles</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Do we offer subtitles for this movie?</span>
                    </div>
                </div>
                <div>
                    <input type="radio" id="subtitles-yes" name="subtitles" value="true" <?php if ($this->movieData['subtitles'] === 1) echo 'checked'; ?>>
                    <label for="subtitles-yes">Yes</label>
                    <input type="radio" id="subtitles-no" name="subtitles" value="false" <?php if ($this->movieData['subtitles'] === 0) echo 'checked'; ?>>
                    <label for="subtitles-no">No</label>
                </div>
        </div>
        <div class="input-row" id="screening">
                <div class="input-label">
                    <label for="screening">Screening</label>
                    <div class="tooltip">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                        <span class="tooltip-text">Is this movie ready for screening? This will affect the visibility of the movie.</span>
                    </div>
                </div>
                <div>
                    <input type="radio" id="screening-yes" name="screening" value="true" <?php if ($this->movieData['screening'] === 1) echo 'checked'; ?>>
                    <label for="screening-yes">Yes</label>
                    <input type="radio" id="screening-no" name="screening" value="false" <?php if ($this->movieData['screening'] === 0) echo 'checked'; ?>>
                    <label for="screening-no">No</label>
                </div>
        </div>

        <div class="actor-section">
            <?php if($this->actorData) {
            foreach ($this->actorData as $index => $actor) :
                ?>
                <div class="input-row">
                    <div class="input-column">
                        <div class="input-label">
                            <label for="actor-<?php echo $index+1 ?>">Actor</label>
                            <div class="tooltip">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                                <span class="tooltip-text">Full name of the lead actor(s). E.g. "John Smith"</span>
                            </div>
                        </div>
                        <input type="text" name="actor-<?php echo $index+1 ?>" id="actor-<?php echo $index+1 ?>" value="<?php echo $actor['full_name'] ?>">
                    </div>
                </div>
            <?php endforeach; } else { ?>
                <div class="input-row">
                    <div class="input-column">
                        <div class="input-label">
                            <label for="actor-1">Actor</label>
                            <div class="tooltip">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                                <span class="tooltip-text">Full name of the lead actor(s). E.g. "John Smith"</span>
                            </div>
                        </div>
                        <input type="text" name="actor-1>" id="actor-1">
                    </div>
                </div>
            <?php } ?>

            <button type="button" class="new-actor">
                <div class="svg-container">
                    <svg class="plus-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
                </div>
            </button>
        </div>

        <div class="error-message">
            <div class="svg-container">
                <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
            </div>
            <span>Error Message</span>
        </div>

        <div class="input-row">
            <button id="submit-btn" type="submit" class="btn">Update Movie</button>
        </div>
    </div>
    <div class="admin-form-right">
        <div class="input-row">
            <label>Poster</label>
            <img class="poster" id="poster-img" src="/cinema/public/img/movie/poster/<?php echo $this->movieData['poster'] ?>">
            <input type="file" name="poster" id="poster" onchange="onFileSelected(event, 'poster-img')" accept="image/webp, image/jpeg">
        </div>
        <div class="input-row">
            <label>Hero</label>
            <img class="poster" id="hero-img" src="/cinema/public/img/movie/hero/<?php echo $this->movieData['hero'] ?>">
            <input type="file" name="hero" id="hero" onchange="onFileSelected(event, 'hero-img')" accept="image/webp, image/jpeg">
        </div>
        <div class="input-row">
            <label>Logo</label>
            <img class="poster" id="logo-img" src="/cinema/public/img/movie/logo/<?php echo $this->movieData['logo'] ?>">
            <input type="file" name="logo" id="logo" onchange="onFileSelected(event, 'logo-img')" accept="image/webp, image/png">
        </div>
    </div>
</form>
</div>
</main>
</div>