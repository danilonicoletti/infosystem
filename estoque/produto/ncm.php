<?php

//include '../../login/conn.php';

// DB table to use
$table = 'ncm';
// Table's primary key
$primaryKey = 'id_ncm';

$columns = array(
    array('db' => 'codigo',  'dt' => 0),
    array('db' => 'descricao',  'dt' => 1, 'formatter' => function ($d, $row) {

        return mb_strtoupper(utf8_encode($d));
    }),
    array('db' => 'ncm',  'dt' => 2),
    array('db' => 'id_ncm', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require('../../ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
