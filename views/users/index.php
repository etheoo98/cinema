<main>
    <div class="container">
        <div class="page-header">
            <a href="/cinema/"><button><i class="fa-solid fa-chevron-left"></i></button></a>

            <h2><?php echo $this->profile['username'] ?></h2>
            <a href="/cinema/settings"><button><i class="fa-solid fa-pen-to-square"></i></button></a>
        </div>
        <div class="profile2">
            <a href="#"><img id="avatar" src="<?php echo $this->profile['gravatar'] ?>" alt="User avatar"></a>
        </div>
    </div>
</main>