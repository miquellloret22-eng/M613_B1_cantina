<?php
session_start();

require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php';

$dades = require __DIR__ . '/inc/dades.php';
$productes = $dades['productes'];

// Inicialitzem el cistell i l'historial de comandes
$_SESSION['cistell'] ??= [];
$_SESSION['comandes'] ??= [];

// Processar els formularis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Treure un producte del cistell
    if (isset($_POST['treure'])) {
        $id = filter_input(INPUT_POST,'treure', FILTER_VALIDATE_INT);
        if ($id !== false && $id !== null) {
            unset($_SESSION['cistell'][$id]);
            afegeixMissatge('Producte eliminat del cistell.');
        }

    // Buidar tot el cistell
    } elseif (isset($_POST['buidar'])) {
        $_SESSION['cistell'] = [];
        afegeixMissatge('S\'ha buidat el cistell.');

    // Finalitzar la comanda
    } elseif (isset($_POST['finalitzar'])) {

        if (!empty($_SESSION['cistell'])) {

            $cistell = $_SESSION['cistell'];
            $total = totalCistell($cistell, $productes);

            // Guardem la comanda a la sessio
            $_SESSION['comandes'][] = [
                'hora' => date('d/m/Y H:i:s'),
                'linies' => $cistell,
                'total' => $total
            ];

            // Buidem el cistell
            $_SESSION['cistell'] = [];

            $totalFormatat = number_format($total, 2, ',', '.');

            afegeixMissatge("Comanda enviada: {$totalFormatat} €");

        } else {
            afegeixMissatge('El cistell es buit.');
        }
    }

    // Evitem tornar a enviar el formulari en recarregar
    header('Location: cistell.php');
    exit;
}

$cistell = $_SESSION['cistell'];
$total = totalCistell($cistell, $productes);

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>El meu cistell</h2>

<?php if (empty($cistell)): ?>

    <p>El cistell es buit.</p>

    <p>
        <a href="cataleg.php">Tornar al cataleg</a>
    </p>

<?php else: ?>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Producte</th>
                <th>Preu unitari</th>
                <th>Quantitat</th>
                <th>Subtotal</th>
                <th>Accio</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($cistell as $id => $quantitat):?>
                <?php if(isset($productes[$id])):
                    $producte = $productes[$id];
                    $subtotal = $producte['preu'] * $quantitat;?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($producte['nom']) ?>
                        </td>

                        <td>
                            <?= number_format($producte['preu'], 2, ',', '.') ?> €
                        </td>

                        <td><?= (int) $quantitat ?></td>

                        <td>
                            <?= number_format($subtotal, 2, ',', '.') ?> €
                        </td>

                        <td>
                            <form method="POST" action="cistell.php">
                                <button
                                    type="submit"
                                    name="treure"
                                    value="<?= (int) $id?>">
                                    Treure
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
        </tbody>
    </table>
    <h3>Total: <?=number_format($total, 2, ',', '.') ?> €</h3>

    <form method="POST" action="cistell.php">
        <button type="submit" name="buidar" value="1">Buidar el cistell</button>
    </form>

    <form method="POST" action="cistell.php">
        <button type="submit" name="finalitzar" value="1">Finalitzar comanda</button>
    </form>

<?php endif; ?>

<hr>

<h2>Historial de comandes</h2>

<?php if (empty($_SESSION['comandes'])): ?>

    <p>Encara no has fet cap comanda.</p>

<?php else: ?>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Data i hora</th>
                <th>Productes</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($_SESSION['comandes'] as $comanda):?>
                <tr>
                    <td>
                        <?=htmlspecialchars($comanda['hora']) ?>
                    </td>

                    <td>
                        <?php
                        $noms=[];
                        foreach($comanda['linies'] as $id => $quantitat) {
                            if (isset($productes[$id])) {
                                $noms[] = htmlspecialchars($productes[$id]['nom']) . ' × ' . (int) $quantitat;
                            }
                        }

                        echo implode(', ', $noms);
                        ?>
                    </td>

                    <td>
                        <?= number_format($comanda['total'], 2, ',', '.') ?> €
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php require_once __DIR__ . '/inc/peu.php'; ?>