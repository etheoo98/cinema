<?php
function filter_var1($str, $filter) {
    return filter_var($str, $filter, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php if (isset($title)) echo $title; ?> - Cinema</title>
    <meta charset="UTF-8">
    <?php if (isset($css)):
        foreach ($css as $file): ?>
            <link rel="stylesheet" type="text/css" href="/cinema/public/css/<?php echo $file; ?>">
        <?php endforeach;
    endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body onresize="handleCheckbox();">
<header>
    <div class="container">
        <div class="master-bar">
            <label for="nav-toggle" class="dashboard-text">Dashboard</label>
        </div>
    </div>
</header>
<div class="page-container">
    <input type="checkbox" id="nav-toggle" checked>
    <nav>
        <ul>
            <li><a href="/cinema/admin/add-title/">Add New Title</a></li>
            <li><a href="/cinema/admin/browse-titles/">Browse Titles</a></li>
            <li><a href="/cinema/admin/manage-roles/">Manage Roles</a></li>
            <li><a href="/cinema/admin/view-statistics/">View Statistics</a></li>
            <li><a href="http://localhost/cinema">Leave This Plain</a></li>
        </ul>
    </nav>

    <main>
        <div class="container" id="page-content">