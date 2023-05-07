<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php if (isset($title)) echo $title; ?> - Cinema</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (isset($css)):
        foreach ($css as $file): ?>
            <link rel="stylesheet" type="text/css" href="/cinema/public/css/<?php echo $file; ?>">
        <?php endforeach;
    endif; ?>

    <script src="/cinema/public/lib/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php if (isset($js)):
        foreach ($js as $file): ?>
            <script src="/cinema/public/js/<?php echo $file; ?>"></script>
        <?php endforeach;
    endif; ?>
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
            <li><a href="/cinema/admin/add-movie">Add New Movie</a></li>
            <li><a href="/cinema/admin/browse-movies">Browse Movies</a></li>
            <li><a href="/cinema/admin/manage-roles">Manage Roles</a></li>
            <li><a href="/cinema/admin/view-statistics">View Statistics</a></li>
            <li><a href="http://localhost/cinema">Leave This Plain</a></li>
        </ul>
    </nav>

    <main>
        <div class="container" id="page-content">