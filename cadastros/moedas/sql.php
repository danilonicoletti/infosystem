<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('data', 'moeda', 'valor', 'status', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$param_update = join(', ', array_map(
    function ($columns) {
        return "$columns = :$columns";
    },
    $columns
));

/* Begin::Adicionar */
if ($type == 'moeda_add') {

    try {
        $sql = 'INSERT INTO moedas (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':data' =>  implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "data", FILTER_DEFAULT)))),
            ':moeda' => filter_input(INPUT_POST, "moeda", FILTER_DEFAULT),
            ':valor' =>  str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "valor", FILTER_DEFAULT))),
            ':status' => 1,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|moeda_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'moeda_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE moedas SET ' . $param_update . ' WHERE id = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':data' =>  implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "data", FILTER_DEFAULT)))),
            ':moeda' => filter_input(INPUT_POST, "moeda", FILTER_DEFAULT),
            ':valor' =>  str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "valor", FILTER_DEFAULT))),
            ':status' => 1,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|moeda_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'moeda_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM moedas WHERE id = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|moeda_delete|DELETE FROM moedas WHERE id = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */

/* end::Outro */