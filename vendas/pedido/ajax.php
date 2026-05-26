<?php

$table = array(
    array('table' => 'pedidos', 'as' => 't1'),
    array('table' => 'usuarios', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't2.id = t1.emissor'),
    array('table' => 'vendedor', 'as' => 't3', 'join_type' => 'INNER', 'join_on' => 't3.id_vendedor = vendedor'),
    array('table' => 'clientes', 'as' => 't4', 'join_type' => 'INNER', 'join_on' => 't4.id_cliente = cliente'),
    // array('table' => 'condicao_de_pagamento', 'as' => 't5', 'join_type' => 'INNER', 'join_on' => 't5.id_condicao = condicao'),
);

// Table's primary key
$primaryKey = 'id_pedido';

$columns = array(
    array('db' => 't1.codigo', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't2.nome', 'as' => 'nome2', 'dt' => 1, 'formatter' => function ($d, $row) {
        if ($d == '') {
            return '';
        } else {
            return $d;
        }
    }),
    array('db' => 't4.nome', 'as' => 'nome4', 'dt' => 2, 'formatter' => function ($d, $row) {
        return mb_strtoupper($d);
    }),
    array('db' => 't3.nome', 'as' => 'nome3', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'dt_emissao', 'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'vl_pedido', 'dt' => 5, 'formatter' => function ($d, $row) {
        return number_format($d, 2, ",", ".");
    }),

    array('db' => 't1.status', 'as' => 'status', 'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'id_pedido', 'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 't1.id_empresa = ' . $company;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where, $group_by, $having)
);
