<?php
$dades = require __DIR__ . '/inc/dades.php';
session_start();

if (isset($_SESSION["usuari_autenticat"])) {
    header("Location: cataleg.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $contrasenya = $_POST["contrasenya"] ?? "";

    foreach ($dades["usuaris"] as $usuari) {
        if ($email === $usuari["email"] && $contrasenya === $usuari["contrasenya"]) {
            session_regenerate_id(true);
            $_SESSION["usuari_autenticat"] = true;
            $_SESSION["usuari_nom"] = $usuari["nom"];
            $_SESSION["usuari_email"] = $email;
            $_SESSION["usuari_rol"] = $usuari["rol"];
            $_SESSION["hora_login"] = time();

            header("Location: cataleg.php");
            exit;
        }
    }
    $error = "Credencials incorrectes";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Login:</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error)?></p>
    <?php endif; ?>
    <form action="" method="post">
        Email: <input type="text" name="email" required value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>">
        Contrasenya: <input type="password" name="contrasenya" required>
        <input type="submit" value="enviar">
    </form>
</body>
</html>