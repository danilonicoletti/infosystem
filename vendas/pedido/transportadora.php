<?

require __DIR__ . '/../../inc/config.php';
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

if (isset($_GET['term'])) {
    $consultaTransportadora = $pdo->query("SELECT * FROM transportadora WHERE id_empresa = " . $company . " AND nome LIKE '%" . $_GET['term'] . "%' ORDER BY nome ASC");
} else {
    $consultaTransportadora = $pdo->query("SELECT * FROM transportadora WHERE id_empresa = " . $company . " ORDER BY nome ASC");
}
while ($linhaTransportadora = $consultaTransportadora->fetch(PDO::FETCH_ASSOC)) {


    $loopTransportadora .= '{"itemName":"' . mb_strtoupper($linhaTransportadora['nome']) . '","id":' . $linhaTransportadora['id_transportadora'] . '},';
}

echo '[';
echo substr($loopTransportadora, 0, -1);
echo ']';
