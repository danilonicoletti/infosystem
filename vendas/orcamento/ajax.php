<?php

$table = array(
    array('table' => 'orcamentos', 'as' => 't1'),
    array('table' => 'usuarios', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't2.id = t1.emissor'),
    array('table' => 'vendedor', 'as' => 't3', 'join_type' => 'INNER', 'join_on' => 't3.id_vendedor = vendedor'),
    array('table' => 'clientes', 'as' => 't4', 'join_type' => 'INNER', 'join_on' => 't4.id_cliente = cliente'),
    // array('table' => 'condicao_de_pagamento', 'as' => 't5', 'join_type' => 'INNER', 'join_on' => 't5.id_condicao = condicao'),
);

// Table's primary key
$primaryKey = 'id_orcamento';

$columns = array(
    array('db' => 'id_orcamento', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'os',  'dt' => 1, 'formatter' => function ($d, $row) {
        if ($row[11] != '') {
            return $d . '<br><b style="font-size: smaller;">' . $row[11] . '</b>';
        } else {
            return $d;
        }
    }),
    array('db' => 't4.nome', 'as' => 'nome4', 'dt' => 2, 'formatter' => function ($d, $row) {
        if ($row[9] == 'fisica') {
            if ($row[10] == '1') {
                return mb_strtoupper($d);
            } else {
                return mb_strtoupper($d . ' ' . $row[8]);
            }
        } else {
            return mb_strtoupper($d);
        }
    }),
    array('db' => 't2.nome', 'as' => 'nome2', 'dt' => 3, 'formatter' => function ($d, $row) {
        if ($d == '') {
            return '';
        } else {
            return $d;
        }
    }),

    array('db' => 'obra', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't3.nome', 'as' => 'nome3', 'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't1.status', 'as' => 'status_o', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_orcamento', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't4.nomes', 'dt' => 8, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't4.pessoa', 'dt' => 9, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't4.sem', 'dt' => 10, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't1.complemento', 'dt' => 11, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where, $group_by, $having)
);
