<?php
$dades = require __DIR__ . "/dades.php";

function buscaUsuari(array $usuaris, string $email): ?array {
    foreach ($usuaris as $u) {
        if ($u["email"] == $email) {
            return $u;
        }
    }
    return null;
}

function unitatsCistell(array $cistell): int {
    return count($cistell);
}

function totalCistell(array $cistell, array $productes): float {
    $total = 0;
    foreach($cistell as $id => $quantitat) {
        if (isset($productes[$id])) {
            $total += $productes[$id]["preu"] * $quantitat;
        }
    }

    return $total;
}

function afegeixMissatge(string $text): void {
    $_SESSION["missatge_flash"] = $text;
}

function llegeixMissatge(): ?string {
    if (isset($_SESSION["missatge_flash"])) {
        $missatge = (string) $_SESSION["missatge_flash"];
        unset($_SESSION["missatge_flash"]);
        return $missatge;
    }
    return null;
}