<?php

$table = array(
    array('table' => 'nfe', 'as' => 't1'),
    array('table' => 'usuarios', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't2.id = t1.emissor'),
    array('table' => 'clientes', 'as' => 't4', 'join_type' => 'INNER', 'join_on' => 't4.id_cliente = t1.destinatario'),
    array('table' => 'cfop', 'as' => 't3', 'join_type' => 'INNER', 'join_on' => 't3.id_cfop = t1.id_cfop'),
);

// Table's primary key
$primaryKey = 'id_nfe';

$columns = array(
    array('db' => 'nota_fiscal',  'dt' => 0, 'formatter' => function ($d, $row) {
        if ($d != '') {
            return $d;
        } else {
            return '';
        }
    }),
    array('db' => 't4.nome', 'as' => 'nome4', 'dt' => 1, 'formatter' => function ($d, $row) {
        if ($row[7] == 'fisica') {
            return mb_strtoupper($d . ' ' . $row[6]);
        } else {
            return mb_strtoupper($d);
        }
    }),
    array('db' => 'data_emissao', 'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't2.nome', 'as' => 'nome2', 'dt' => 3, 'formatter' => function ($d, $row) {
        if ($d == '') {
            return '';
        } else {
            return $d;
        }
    }),
    array('db' => 't1.status', 'as' => 'status_o', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_nfe', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'nomes', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'pessoa', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../ssp.class.php';
include __DIR__ . '/../inc/company.php';

$where = 'removido = 0 AND t1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where, $group_by, $having)
);
