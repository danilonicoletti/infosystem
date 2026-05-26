<?php
// DB table to use
$table = 'clientes';
// Table's primary key
$primaryKey = 'id_cliente';

$columns = array(
    array('db' => 'codigo', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'nome',  'dt' => 1, 'formatter' => function ($d, $row) {
        if ($row[7] == 'fisica') {
            return mb_strtoupper($d . ' ' . $row[6]);
        } else {
            return mb_strtoupper($d);
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
    array('db' => 'id_cliente', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'nomes', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'pessoa', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),


);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 'sem = 0 AND id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
