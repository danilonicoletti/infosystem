<?php
// DB table to use
$table = 'vendedor';
// Table's primary key
$primaryKey = 'id_vendedor';

$columns = array(
    array('db' => 'id_vendedor', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'nome',  'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d != '') {
            return $d;
        } else {
            return '';
        }
    }),
    array('db' => 'documento', 'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'email', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'celular', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'cidade', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'status', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_vendedor', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 'id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
