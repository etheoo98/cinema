<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title; ?> - Cinema</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/cinema/public/css/main.css">
    <?php foreach ($css as $file): ?>
        <link rel="stylesheet" type="text/css" href="/cinema/public/css/<?php echo $file; ?>">
    <?php endforeach; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/2354648d6d.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="container">
            <img class="logo" src="/cinema/public/img/cinema-logo.png" alt="Cinema logo">
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="check-button"><i class="fas fa-bars"></i></label>
                <ul>
                    <li><a href="/cinema/movies">Movies</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
            <?php
            # Gravatar Image Request
            if (isset($_SESSION['user_id'])) {

                $default = "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/b5/b5bd56c1aa4644a474a2e4972be27ef9e82e517e_full.jpg";
                $size = 184;
                $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($_SESSION['email']))) . "?d=" . urlencode($default) . "&s=" . $size;
            ?>

                <div class="dropdown">
                    <input type="image" onclick="myFunction()" class="dropbtn" src="<?php echo $grav_url; ?>" alt="Avatar">
                    <div id="myDropdown" class="dropdown-content">
                        <div class="dropdown-header">
                            <a href="#"><img id="avatar" src="<?php echo $grav_url; ?>" alt="Avatar"></a>
                            <p><?php echo $_SESSION['username'] ?></p>
                        </div>
                        <div class="dropdown-section">
                            <a href="/cinema/profile/<?php echo $_SESSION['user_id'] ?>"><i class="fa-solid fa-user"></i>Profile</a>
                            <a href="/cinema/bookings"><i class="fa-solid fa-ticket"></i>Bookings</a>
                        </div>
                        <div class="dropdown-section">
                            <a href="/cinema/settings"><i class="fa-solid fa-gear"></i>Settings</a>
                        </div>
                        <div class="dropdown-section">
                            <a href="#"><i class="fa-solid fa-language"></i>Language</a>
                            <a href="https://github.com/etheoo98"><i class="fa-solid fa-comments"></i>Send Feedback</a>
                        </div>
                        <div class="dropdown-section">
                            <a href="/cinema/sign-out"> <i class="fa-solid fa-right-from-bracket"></i>Sign Out</a>
                        </div>

                    </div>
                </div>
                <script src="/cinema/public/js/dropdown.js"></script>

            <?php
            } else {
            ?>
                <a id="signin" href="/cinema/sign-in">Sign In/Up</a>
            <?php } ?>
    </header>