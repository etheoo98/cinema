<?php
/**
 * This file acts as a middleware between the view and controller.
 *
 * The if-statement determines whether an AJAX POST request has been made. If that is the case,
 * a new instance of the controller class is created, and a call to the controller's ajaxHandler
 * method is made.
 *
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $controller = new EditTitleController($conn);
    $controller->ajaxHandler();
    die();
}
