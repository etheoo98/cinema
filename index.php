<?php

require_once('./controllers/HomeController.php');

# parse the URL parameter
if (isset($_GET['url'])) {
  $url = rtrim($_GET['url'], '/');
  $url_parts = explode('/', $url);
} else {
  $url_parts = [];
}

# determine the controller and action based on the URL
if (empty($url_parts)) {
  require_once('./controllers/HomeController.php');
  $controller = new HomeController();
  $controller->index();
} elseif ($url_parts[0] == 'sign-in') {
  require_once('./controllers/SignInController.php');
  $controller = new SignInController($conn);
  $controller->index();
} elseif ($url_parts[0] == 'sign-up') {
  require_once('./controllers/SignUpController.php');
  $controller = new SignUpController($conn);
  $controller->index();
}elseif ($url_parts[0] == 'sign-out') {
    require_once('./controllers/SignOutController.php');
    $controller = new SignOutController($conn);
    $controller->index();
} elseif ($url_parts[0] == 'movies') {
  require_once('./controllers/MoviesController.php');
  $controller = new MoviesController($conn);
  $controller->index();
} elseif ($url_parts[0] == 'title') {
  require_once('./controllers/TitleController.php');
  $controller = new TitleController($conn);
  $controller->index();
} elseif ($url_parts[0] == 'bookings') {
  require_once('./controllers/BookingsController.php');
  $controller = new BookingsController($conn);
  $controller->index();
} elseif ($url_parts[0] == 'profile') {
  require_once('./controllers/ProfileController.php');
  $controller = new ProfileController($conn);
  $controller->index();
} elseif ($url_parts[0] == 'settings') {
    require_once('./controllers/SettingsController.php');
    $controller = new SettingsController($conn);
    $controller->index();
}
else {
  header('HTTP/1.1 404 Not Found');
  echo 'Page not found';
}
