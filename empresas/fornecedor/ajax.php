<?php
// DB table to use
$table = 'fornecedor AS t1 INNER JOIN tipo_de_fornecedor AS t2 ON t2.codigo = t1.tipo_fornecedor';
// Table's primary key
$primaryKey = 'id_fornecedor';

$columns = array(
    array('db' => 't1.codigo', 'dt' => 0, 'formatter' => function ($d, $row) {
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
    array('db' => 'descricao', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),

    array('db' => 'id_fornecedor', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
