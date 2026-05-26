<?php

// DB table to use
$table = 'ncm';
// Table's primary key
$primaryKey = 'id_ncm';

$columns = array(

    array('db' => 'ncm', 'dt' => 0, 'formatter' => function ($d, $row) {

        return $d;
    }),
    array('db' => 'descricao', 'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d != '') {

            return  mb_strtoupper($d);
        } else {
            return '';
        }
    }),

    array('db' => 'id_ncm', 'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../ssp.class.php';
include __DIR__ . '/../inc/company.php';

$where = 'id_empresa = ' . $company;

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where, $whereAll)
);
