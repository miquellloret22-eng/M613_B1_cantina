<?php
$missatge = llegeixMissatge();
$nomUsuari = $_SESSION["usuari"]["nom"] ?? "Usuari";
$cistell = $_SESSION["cistell"] ?? [];
$unitats = unitatsCistell($cistell);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantina de l'institut</title>
</head>
<body>

<header>
    <h1>Cantina de l'institut</h1>

    <p>Benvingut/da, <?= htmlspecialchars($nomUsuari) ?></p>

    <nav>
        <a href="cataleg.php">Catàleg</a> |
        <a href="cistell.php">Cistell (<?= $unitats?>unitats)</a>|
            <a href="logout.php">Tancarsessió</a>
    </nav>
</header>
    <hr>
<?php if($missatge !== null): ?>
    <p><?= htmlspecialchars($missatge) ?></p>
<?php endif; ?>