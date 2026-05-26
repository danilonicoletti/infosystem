<?php

include '../../inc/functions.php';

$id = anti_injection($_GET['id']);

$table = array(
    array('table' => 'entrada_e_saida', 'as' => 't1'),
    array('table' => 'produtos', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't1.id_produto = t2.id_produto'),
);

// Table's primary key
$primaryKey = 't1.id_produto';

$columns = array(

    array('db' => 'lancamento', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'movimento', 'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'documento', 'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'es', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'cliente_fornecedor', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'medidas', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't2.valor', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $row[11] . ' ' . number_format($d, 2, ",", ".");
        // return 'R$ 80,00 ';
    }),
    array('db' => 'chapas', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'metros', 'dt' => 8, 'formatter' => function ($d, $row) {
        return '' . number_format($d, 3, ",", ".");
    }),
    array('db' => 'saldo_chapas', 'dt' => 9, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'saldo_metros', 'dt' => 10, 'formatter' => function ($d, $row) {
        return '' . number_format($d, 3, ",", ".");
    }),
    array('db' => 't2.moeda', 'dt' => 11, 'formatter' => function ($d, $row) {
        return $d;
        // return 'R$';
    }),

);

require __DIR__ . '/../../ssp.class.php';

$where = 't1.id_produto = ' . $id;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
