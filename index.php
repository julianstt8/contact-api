<?php
header("Content-Type: application/json");
$route = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
require_once __DIR__ . "/routes.php";
