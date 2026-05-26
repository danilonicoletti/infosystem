<?php

//include '../../login/conn.php';

// DB table to use
$table = array(
    array('table' => 'baixa_estoque', 'as' => 't1'),
    array('table' => 'fornecedor', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't1.id_fornecedor = t2.id_fornecedor'),

);
// Table's primary key
$primaryKey = 'id_baixa';

$columns = array(

    array('db' => 'saida', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),

    array('db' => 'dt_emissao',  'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d != '00000-00-00') {
            $dataNow = new DateTime($d);
            return $dataNow->format('d/m/Y');
        } else {
            return '';
        }
    }),

    array('db' => 'nome',  'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),

    array('db' => 'id_baixa', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
