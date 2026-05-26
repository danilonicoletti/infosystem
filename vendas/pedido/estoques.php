<?php
$id_produto = $_GET['id'];

// DB table to use
$table = 'romaneio_estoque';
// Table's primary key
$primaryKey = 'id_r_estoque';

$columns = array(

    array('db' => 'id_r_estoque', 'dt' => 0, 'formatter' => function ($d, $row) {
        if (isset($_SESSION['produtos'])) {
            if (in_array($d, $_SESSION['produtos'])) {
                return '<label class="checkbox checkbox-outline">
                    <input type="checkbox" name="produtos[]" value="' . $d . '" checked class="produtos" >
                    <span></span>
                </label>';
            } else {
                return '<label class="checkbox checkbox-outline">
                    <input type="checkbox" name="produtos[]" value="' . $d . '" class="produtos" >
                    <span></span>
                </label>';
            }
        } else {
            return '<label class="checkbox checkbox-outline">
                <input type="checkbox" name="produtos[]" value="' . $d . '" class="produtos" >
                <span></span>
            </label>';
        }
    }),
    array('db' => 'codigo', 'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'espessura', 'dt' => 2, 'formatter' => function ($d, $row) {
        return  'Esp ' . number_format($d, 1, ',', '') . ' Cm - ' . number_format($row[3], 2, ',', '') . ' x ' . number_format($row[4], 2, ',', '') . ' = ' . number_format($row[5], 2, ',', '');
    }),
    array('db' => 'comprimento', 'dt' => 3, 'formatter' => function ($d, $row) {
        return  $d;
    }),
    array('db' => 'altura', 'dt' => 4, 'formatter' => function ($d, $row) {
        return  $d;
    }),
    array('db' => 'metro', 'dt' => 5, 'formatter' => function ($d, $row) {
        return  number_format($d, 2, ',', '');
    }),

);

require __DIR__ . '/../../ssp.class.php';
include __DIR__ . '/../../inc/company.php';

$where = 'id_produto = ' . $id_produto;

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where, $where)
);
