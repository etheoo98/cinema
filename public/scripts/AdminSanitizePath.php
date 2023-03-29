<?php
# This function is called from admin.js
$page = $_POST['page'];

$sanitizedPath = filter_var($page, FILTER_SANITIZE_URL);
$sanitizedPath = preg_replace('/[^a-zA-Z\-]/', '', $sanitizedPath);

$sanitizedPath = "/cinema/views/admin/" . $sanitizedPath . ".php";

echo $sanitizedPath;