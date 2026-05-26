<?php

//include '../../login/conn.php';

// DB table to use
$table = 'moedas';
// Table's primary key
$primaryKey = 'id';

$columns = array(

    array('db' => 'moeda', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'data',  'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'valor', 'dt' => 2, 'formatter' => function ($d, $row) {
        return number_format($d, 2, ',', '.');
    }),
    array('db' => 'id', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 'id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
