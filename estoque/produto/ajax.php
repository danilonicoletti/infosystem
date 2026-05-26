<?php

//include '../../login/conn.php';

// DB table to use

$table = array(
    array('table' => 'produtos', 'as' => 't1'),
    array('table' => 'ncm', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't2.id_ncm = t1.id_ncm'),

);

// Table's primary key
$primaryKey = 'id_produto';

$columns = array(

    array('db' => 't1.codigo', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't1.descricao',  'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d != '') {
            return  mb_strtoupper($d);
        } else {
            return '';
        }
    }),
    array('db' => 'unidade', 'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'valor',  'dt' => 3, 'formatter' => function ($d, $row) {
        if ($d != '0.00') {
            return number_format($d, 2, ",", ".");
        } else {
            return '0,00';
        }
    }),
    array('db' => 'ncm', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_produto', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'moeda', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.status = 1 AND t1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
