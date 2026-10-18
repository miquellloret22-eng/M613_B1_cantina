<?php
session_start();

require_once __DIR__ . "/inc/protegeix.php";
require_once __DIR__ . "/inc/funcions.php";

$dades = require __DIR__ . "/inc/dades.php";
$productes = $dades["productes"];

// Inicialitzem el cistell si encara no existeix
$_SESSION["cistell"] ??= [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $quantitat = filter_input(INPUT_POST, "quantitat", FILTER_VALIDATE_INT);

    // Validem l'identificador i la quantitat
    if (
        $id !== false &&
        $id !==null &&
        isset($productes[$id]) &&
        $quantitat !== false &&
        $quantitat !== null &&
        $quantitat >= 1 &&
        $quantitat <= 5
    ) {
        // Si ja hi es sumem la quantitat
        $_SESSION["cistell"][$id] = ($_SESSION["cistell"][$id] ?? 0) + $quantitat;

        $nom = $productes[$id]["nom"];

        afegeixMissatge("S'han afegit {$quantitat} × {$nom}");
    } else {
        afegeixMissatge("Producte o quantitat no valids.");
    }

    header("Location: cataleg.php");
    exit;
}

require_once __DIR__ . "/inc/capcalera.php";
?>

<h2>Catàleg de productes</h2>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Producte</th>
            <th>Preu</th>
            <th>Quantitat</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($productes as $id => $producte):?>
            <tr>
                <td>
                    <?=htmlspecialchars($producte["nom"]) ?>
                </td>

                <td>
                    <?= number_format($producte["preu"], 2, ",", ".") ?> €
                </td>

                <td>
                    <form method="POST" action="cataleg.php">
                        <input type="hidden" name="id" value="<?= (int) $id?>">
                        <select name="quantitat">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>">
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                        <button type="submit">Afegir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . "/inc/peu.php"; ?>