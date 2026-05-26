<?php

// DB table to use
$table = array(
    array('table' => 'romaneio_produtos', 'as' => 't1'),
    array('table' => 'produtos', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't1.id_produto = t2.id_produto'),

);
// Table's primary key
$primaryKey = 'id_r_produto';

$columns = array(

    array('db' => 't2.codigo', 'dt' => 0, 'formatter' => function ($d, $row) {

        return $d;
    }),
    array('db' => 'descricao', 'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d != '') {

            return  mb_strtoupper($d);
        } else {
            return '';
        }
    }),

    array('db' => 't1.espessura', 'dt' => 2, 'formatter' => function ($d, $row) {
        return 'Esp ' . number_format($d, 1, ",", "") . " Cm - " . number_format($row[3], 2, ",", "") . " x " . number_format($row[4], 2, ",", "") . " Metros";
    }),
    array('db' => 'comprimento', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'altura', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_r_produto', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 'id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
