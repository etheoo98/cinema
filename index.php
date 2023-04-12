<?php
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
        $controller->index();
        break;
    case 'sign-in':
        require_once('./controllers/SignInController.php');
        $controller = new SignInController($conn);
        $controller->index();
        break;
    case 'sign-out':
        require_once('./controllers/SignOutController.php');
        $controller = new SignOutController($conn);
        $controller->index();
        break;
    case 'catalog':
        require_once('./controllers/CatalogController.php');
        $controller = new CatalogController($conn);
        $controller->index();
        break;
    case 'title':
        require_once('./controllers/TitleController.php');
        $controller = new TitleController($conn);
        $controller->index();
        break;
    case 'bookings':
        require_once('./controllers/BookingsController.php');
        $controller = new BookingsController($conn);
        $controller->index();
        break;
    case 'users':
        require_once('./controllers/UserController.php');
        $controller = new UserController($conn);
        $controller->index();
        break;
    case 'settings':
        require_once('./controllers/SettingsController.php');
        $controller = new SettingsController($conn);
        $controller->index();
        break;
    case 'admin':
        if (isset($url_parts[1]) && $url_parts[1] == 'edit-title') {
            require_once('./controllers/admin/EditTitleController.php');
            $controller = new EditTitleController($conn);
            $controller->index();
        } elseif (isset($url_parts[1]) && $url_parts[1] == 'add-title') {
            require_once('./controllers/admin/AddTitleController.php');
            $controller = new AddTitleController($conn);
            $controller->index();
        } elseif (isset($url_parts[1]) && $url_parts[1] == 'browse-titles') {
            require_once('./controllers/admin/BrowseTitlesController.php');
            $controller = new BrowseTitlesController($conn);
            $controller->index();
        }
        elseif (isset($url_parts[1]) && $url_parts[1] == 'manage-roles') {
            require_once('./controllers/admin/ManageRolesController.php');
            $controller = new ManageRolesController($conn);
            $controller->index();
        }
        elseif (isset($url_parts[1]) && $url_parts[1] == 'view-statistics') {
            require_once('./controllers/admin/ViewStatisticsController.php');
            $controller = new ViewStatisticsController($conn);
            $controller->index();
        }
        else {
            require_once('./controllers/admin/AdminController.php');
            $controller = new AdminController($conn);
            $controller->index();
        }
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        echo 'Page not found';
        break;
}