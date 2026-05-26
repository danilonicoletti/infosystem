<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('codigo', 'cfop', 'descricao', 'icms', 'ipi', 'cofins', 'id_empresa');
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
if ($type == 'cfop_add') {

    try {
        $sql = 'INSERT INTO cfop (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':cfop' => filter_input(INPUT_POST, "cfop", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':icms' =>  filter_input(INPUT_POST, "icms", FILTER_DEFAULT),
            ':ipi' =>  filter_input(INPUT_POST, "ipi", FILTER_DEFAULT),
            ':cofins' =>  filter_input(INPUT_POST, "cofins", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cfop_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'cfop_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE cfop SET ' . $param_update . ' WHERE id_cfop = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':cfop' => filter_input(INPUT_POST, "cfop", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':icms' =>  filter_input(INPUT_POST, "icms", FILTER_DEFAULT),
            ':ipi' =>  filter_input(INPUT_POST, "ipi", FILTER_DEFAULT),
            ':cofins' =>  filter_input(INPUT_POST, "cofins", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cfop_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'cfop_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM cfop WHERE id_cfop = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cfop_delete|DELETE FROM cfop WHERE id_cfop = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */

/* end::Outro */