<?

require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';

if (isset($_GET['term'])) {
    $consultaCliente = $pdo->query("SELECT * FROM clientes WHERE sem = 0 AND id_empresa = " . $company . " AND (nome LIKE '%" . $_GET['term'] . "%' OR nomes LIKE '%" . $_GET['term'] . "%') ORDER BY nome, nomes ASC");
} else {
    $consultaCliente = $pdo->query("SELECT * FROM clientes WHERE sem = 0 AND id_empresa = " . $company . " ORDER BY nome, nomes ASC");
}
while ($linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC)) {

    if ($linhaCliente['pessoa'] == 'fisica') {
        $loopCliente .= '{"itemName":"' . mb_strtoupper($linhaCliente['nome'] . " " . $linhaCliente['nomes']) . '","id":' . $linhaCliente['id_cliente'] . '},';
    } else {
        $loopCliente .= '{"itemName":"' . mb_strtoupper($linhaCliente['nomes']) . '","id":' . $linhaCliente['id_cliente'] . '},';
    }
}

echo '[';
echo substr($loopCliente, 0, -1);
echo ']';
