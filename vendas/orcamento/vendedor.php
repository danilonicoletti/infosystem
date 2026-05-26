<?

require __DIR__ . '/../../inc/config.php';
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

if (isset($_GET['term'])) {
    $consultaVendedor = $pdo->query("SELECT * FROM vendedor WHERE status = 1 AND id_empresa = " . $company . " AND nome LIKE '%" . $_GET['term'] . "%' ORDER BY nome ASC");
} else {
    $consultaVendedor = $pdo->query("SELECT * FROM vendedor WHERE status = 1 AND id_empresa = " . $company . " ORDER BY nome ASC");
}
while ($linhaVendedor = $consultaVendedor->fetch(PDO::FETCH_ASSOC)) {


    $loopVendedor .= '{"itemName":"' . mb_strtoupper($linhaVendedor['nome']) . '","id":' . $linhaVendedor['id_vendedor'] . '},';
}

echo '[';
echo substr($loopVendedor, 0, -1);
echo ']';
