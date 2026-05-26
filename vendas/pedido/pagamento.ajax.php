<?php

$id_pedido = $_GET['id_pedido'];

// DB table to use
$table = array(
    array('table' => 'pedidos_pagamentos', 'as' => 't1', 'join_type' => '', 'join_on' => ''),
    array('table' => 'prazo_de_pagamento', 'as' => 't2', 'join_type' => 'INNER', 'join_on' => 't1.id_prazo = t2.id_pagamento'),
);
// Table's primary key
$primaryKey = 'id_pedido_pagamento';

$columns = array(
    array('db' => 't1.forma', 'as' => 'nome', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't2.descricao', 'as' => 'descricao', 'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 't1.valor', 'as' => 'valor', 'dt' => 2, 'formatter' => function ($d, $row) {
        // return 'R$' . number_format($d, 2, ",", ".");
        if ($d != "") {
            return $d;
        } else {
            return 0;
        }
    }),

    array('db' => 'id_pedido_pagamento', 'as' => 'id_pedido_pagamento', 'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),

);

require __DIR__ . '/../../ssp.class.php';

$where = "id_pedido = " . $id_pedido;
$whereAll = "";

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $whereAll, NULL, NULL)
);
