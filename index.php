<?php
# Display errors in browser for debugging purposes, this will be removed later.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = null; # Initialize to prevent IDE error
require_once('config/dbconnect.php');

# parse the URL parameter
if (isset($_GET['url'])) {
    $url = rtrim($_GET['url'], '/');
    $url_parts = explode('/', $url);
} else {
    $url_parts = [];
}

# Determine the controller and action based on the URL
switch ($url_parts[0]) {
    case 'home':
        require_once('./controllers/HomeController.php');
        $controller = new HomeController();
        $controller->initializeView();
        break;
    case 'sign-in':
        require_once('./controllers/SignInController.php');
        $controller = new SignInController($conn);
        $controller->initializeView();
        break;
    case 'sign-out':
        require_once('./controllers/SignOutController.php');
        $controller = new SignOutController($conn);
        $controller->initializeView();
    case 'catalog':
        require_once('./controllers/CatalogController.php');
        $controller = new CatalogController($conn);
        $controller->initializeView();
        break;
    case 'movie':
        require_once('./controllers/MovieController.php');
        $controller = new MovieController($conn);
        $controller->initializeView();
        break;
    case 'bookings':
        require_once('./controllers/BookingsController.php');
        $controller = new BookingsController($conn);
        $controller->initializeView();
        break;
    case 'users':
        require_once('./controllers/UserController.php');
        $controller = new UserController($conn);
        $controller->initializeView();
        break;
    case 'settings':
        require_once('./controllers/SettingsController.php');
        $controller = new SettingsController($conn);
        $controller->initializeView();
        break;
    case 'admin':
        if (isset($url_parts[1]) && $url_parts[1] == 'edit-movie') {
            require_once('./controllers/admin/EditMovieController.php');
            $controller = new EditMovieController($conn);
        } elseif (isset($url_parts[1]) && $url_parts[1] == 'add-movie') {
            require_once('./controllers/admin/AddMovieController.php');
            $controller = new AddMovieController($conn);
        } elseif (isset($url_parts[1]) && $url_parts[1] == 'browse-movies') {
            require_once('./controllers/admin/BrowseMoviesController.php');
            $controller = new BrowseMoviesController($conn);
        }
        elseif (isset($url_parts[1]) && $url_parts[1] == 'manage-roles') {
            require_once('./controllers/admin/ManageRolesController.php');
            $controller = new ManageRolesController($conn);
        }
        elseif (isset($url_parts[1]) && $url_parts[1] == 'view-statistics') {
            require_once('./controllers/admin/ViewStatisticsController.php');
            $controller = new ViewStatisticsController($conn);
        }
        else {
            require_once('./controllers/admin/AdminController.php');
            $controller = new AdminController($conn);
        }
        $controller->initializeView();
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        echo 'Page not found';
        break;
}