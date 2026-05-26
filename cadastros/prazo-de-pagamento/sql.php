<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('codigo', 'descricao', 'parcela_1', 'parcela_2', 'parcela_3', 'parcela_4', 'parcela_5', 'parcela_6',  'id_empresa');
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

if (filter_input(INPUT_POST, "parcela_1", FILTER_DEFAULT) == "") {
    $parcela_1 = 0;
} else {
    $parcela_1 = filter_input(INPUT_POST, "parcela_1", FILTER_DEFAULT);
}
if (filter_input(INPUT_POST, "parcela_2", FILTER_DEFAULT) == "") {
    $parcela_2 = 0;
} else {
    $parcela_2 = filter_input(INPUT_POST, "parcela_2", FILTER_DEFAULT);
}
if (filter_input(INPUT_POST, "parcela_3", FILTER_DEFAULT) == "") {
    $parcela_3 = 0;
} else {
    $parcela_3 = filter_input(INPUT_POST, "parcela_3", FILTER_DEFAULT);
}
if (filter_input(INPUT_POST, "parcela_4", FILTER_DEFAULT) == "") {
    $parcela_4 = 0;
} else {
    $parcela_4 = filter_input(INPUT_POST, "parcela_4", FILTER_DEFAULT);
}
if (filter_input(INPUT_POST, "parcela_5", FILTER_DEFAULT) == "") {
    $parcela_5 = 0;
} else {
    $parcela_5 = filter_input(INPUT_POST, "parcela_5", FILTER_DEFAULT);
}
if (filter_input(INPUT_POST, "parcela_6", FILTER_DEFAULT) == "") {
    $parcela_6 = 0;
} else {
    $parcela_6 = filter_input(INPUT_POST, "parcela_6", FILTER_DEFAULT);
}

/* Begin::Adicionar */
if ($type == 'prazo_de_pagamento_add') {

    try {
        $sql = 'INSERT INTO prazo_de_pagamento (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':parcela_1' => $parcela_1,
            ':parcela_2' => $parcela_2,
            ':parcela_3' => $parcela_3,
            ':parcela_4' => $parcela_4,
            ':parcela_5' => $parcela_5,
            ':parcela_6' => $parcela_6,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|prazo_de_pagamento_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'prazo_de_pagamento_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE prazo_de_pagamento SET ' . $param_update . ' WHERE id_pagamento = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':parcela_1' => $parcela_1,
            ':parcela_2' => $parcela_2,
            ':parcela_3' => $parcela_3,
            ':parcela_4' => $parcela_4,
            ':parcela_5' => $parcela_5,
            ':parcela_6' => $parcela_6,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|prazo_de_pagamento_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'prazo_de_pagamento_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM prazo_de_pagamento WHERE id_pagamento = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|prazo_de_pagamento_delete|DELETE FROM prazo_de_pagamento WHERE id_pagamento = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */

/* end::Outro */