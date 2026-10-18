<?php

if (!isset($_SESSION["usuari_autenticat"])) {
    header("Location: login.php");
    exit;
};

$timeout = 5;

if (isset($_SESSION["ultima_activitat"])) {
    $temps_inactiu = time() - $_SESSION["ultima_activitat"];

    if ($temps_inactiu > $timeout) {
        $_SESSION = [];
        session_destroy();
        header("Location: login.php");
        exit;
    };
};

$_SESSION["ultima_activitat"] = time();