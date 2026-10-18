<?php
session_start();
$dades = require __DIR__ . '/inc/dades.php';

$_SESSION = [];
session_destroy();
header("Location: login.php");
exit;