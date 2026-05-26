<?

session_start();

include '../../login/conn.php';


$sql = $pdo->query("SELECT * FROM clientes WHERE documento = '" . $_POST['documento'] . "'");
$count = $sql->rowCount();

if ($count == 0) {

    $isAvailable = true;
} else {

    $isAvailable = false;
}

echo json_encode(array(
    'valid' => $isAvailable,
));
