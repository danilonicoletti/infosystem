<?php

include '../../inc/functions.php';

$id = anti_injection($_GET['id']);

// DB table to use

$table = array(
    array('table' => 'romaneio_estoque', 'as' => 't1'),
    array('table' => 'romaneio', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't2.id_romaneio = t1.id_romaneio'),
    array('table' => 'fornecedor', 'as' => 't3', 'join_type' => 'INNER', 'join_on' => 't3.id_fornecedor = t2.id_fornecedor'),

);

// Table's primary key
$primaryKey = 'id_r_produto';

$columns = array(

    array('db' => 't1.codigo', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't2.dt_emissao',  'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'espessura', 'dt' => 2, 'formatter' => function ($d, $row) {

        return 'Esp ' . number_format($d, 1, ",", "") . " Cm - " . number_format($row[4], 2, ",", "") . " x " . number_format($row[5], 2, ",", "") . " Metros";
    }),
    array('db' => 'comprimento', 'dt' => 3, 'formatter' => function ($d, $row) {
        $total = $row[4] * $row[5];
        return number_format($total, 3, ",", "");
    }),
    array('db' => 'comprimento', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'altura', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'chapas', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't3.nome', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),


);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.id_produto = ' . $id . '';

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
