<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('codigo', 'especie', 'origem', 'formato', 'estado', 'espessura', 'id_fornecedor', 'descricao', 'moeda', 'unidade', 'id_ncm',  'valor', 'observacao', 'status', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$param_update = join(', ', array_map(
    function ($columns) {
        return "$columns = :$columns";
    },
    $columns
));

/* Begin::Adicionar */
if ($type == 'produto_add') {

    $sql = $pdo->query("SELECT * FROM produtos WHERE especie = '" . filter_input(INPUT_POST, "especie", FILTER_DEFAULT) . "' AND origem = '" . filter_input(INPUT_POST, "origem", FILTER_DEFAULT) . "' AND formato = '" . filter_input(INPUT_POST, "formato", FILTER_DEFAULT) . "' AND estado = '" . filter_input(INPUT_POST, "estado", FILTER_DEFAULT) . "' AND espessura = '" . filter_input(INPUT_POST, "espessura", FILTER_DEFAULT) . "'");
    $total = $sql->rowCount() + 1;

    $codigo = filter_input(INPUT_POST, "especie", FILTER_DEFAULT) . filter_input(INPUT_POST, "origem", FILTER_DEFAULT) . filter_input(INPUT_POST, "formato", FILTER_DEFAULT) . filter_input(INPUT_POST, "estado", FILTER_DEFAULT) . str_replace(".", "", filter_input(INPUT_POST, "espessura", FILTER_DEFAULT)) . str_pad($total, 3, '0', STR_PAD_LEFT);

    try {
        $sql = 'INSERT INTO produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => $codigo,
            ':especie' => filter_input(INPUT_POST, "especie", FILTER_DEFAULT),
            ':origem' => filter_input(INPUT_POST, "origem", FILTER_DEFAULT),
            ':formato' => filter_input(INPUT_POST, "formato", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':espessura' => filter_input(INPUT_POST, "espessura", FILTER_DEFAULT),
            ':id_fornecedor' => filter_input(INPUT_POST, "fornecedor", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':moeda' => filter_input(INPUT_POST, "moeda", FILTER_DEFAULT),
            ':unidade' => filter_input(INPUT_POST, "unidade", FILTER_DEFAULT),
            ':id_ncm' => filter_input(INPUT_POST, "id_ncm", FILTER_DEFAULT),
            ':valor' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT))),
            ':observacao' => filter_input(INPUT_POST, "observacao", FILTER_DEFAULT),
            ':status' => 1,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|produto_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'produto_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE produtos SET ' . $param_update . ' WHERE id_produto = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':especie' => filter_input(INPUT_POST, "especie", FILTER_DEFAULT),
            ':origem' => filter_input(INPUT_POST, "origem", FILTER_DEFAULT),
            ':formato' => filter_input(INPUT_POST, "formato", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':espessura' => filter_input(INPUT_POST, "espessura", FILTER_DEFAULT),
            ':id_fornecedor' => filter_input(INPUT_POST, "fornecedor", FILTER_DEFAULT),
            ':descricao' => filter_input(INPUT_POST, "descricao", FILTER_DEFAULT),
            ':moeda' => filter_input(INPUT_POST, "moeda", FILTER_DEFAULT),
            ':unidade' => filter_input(INPUT_POST, "unidade", FILTER_DEFAULT),
            ':id_ncm' => filter_input(INPUT_POST, "id_ncm", FILTER_DEFAULT),
            ':valor' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT))),
            ':observacao' => filter_input(INPUT_POST, "observacao", FILTER_DEFAULT),
            ':status' => 1,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|produto_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'produto_remove') {

    try {
        $_stmt = $pdo->prepare('UPDATE produtos SET status = 0 WHERE id_produto = :id');
        $_stmt->bindParam(':id', $_POST['id']);
        $_stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|produto_delete|UPDATE produtos SET status = 0 WHERE id_produto = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
if ($type == 'ncm_load') {

    $sql = $pdo->query("SELECT * FROM ncm WHERE id_ncm = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_ncm'] . "|"; //0
    echo $rowCol['ncm'] . "|"; //1
}

if ($type == 'totais_view') {

    $id = filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT);

    if (isset($_SESSION['complex']) && $_SESSION['complex'] != "") {
        $whereExplode = explode('WHERE ', $_SESSION['complex']);
        $where = $whereExplode[1];
    } else {
        $where = "id_produto = " . $id . "";
    }

    $consultaE = $pdo->query("SELECT SUM(chapas) AS chapas, SUM(metros) AS metros FROM entrada_e_saida WHERE es = 'E' AND " . $where);
    $linhaE = $consultaE->fetch(PDO::FETCH_ASSOC);

    if ($linhaE['chapas'] != NULL) {
        $entrada = '<div class="col-xl-3"><div class="d-flex align-items-center bg-light-success rounded p-5 mt-9">
            <span class="svg-icon svg-icon-success mr-5">
                <span class="svg-icon svg-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
                            <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>
            </span>
            <div class="d-flex flex-column flex-grow-1 mr-2">
                <span class="text-muted font-weight-bold">Chapas ' . $linhaE['chapas'] . '</span>
                <span class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">m² ' . number_format($linhaE['metros'], 3, ",", ".") . '</span>
            </div>
        </div></div>';
    }

    $consultaS = $pdo->query("SELECT SUM(chapas) AS chapas, SUM(metros) AS metros FROM entrada_e_saida WHERE es = 'S' AND " . $where);
    $linhaS = $consultaS->fetch(PDO::FETCH_ASSOC);

    if ($linhaS['chapas'] != NULL) {
        $saida .= '<div class="col-xl-3"><div class="d-flex align-items-center bg-light-danger rounded p-5 mt-9">
            <span class="svg-icon svg-icon-danger mr-5">
                <span class="svg-icon svg-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
                            <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>
            </span>

            <div class="d-flex flex-column flex-grow-1 mr-2">
                <span class="text-muted font-weight-bold">Chapas ' . $linhaS['chapas'] . '</span>
                <span class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">m² ' . number_format($linhaS['metros'], 3, ",", ".") . '</span>
            </div>
        </div></div>';
    }

    echo '<div class="col-xl">
        </div>
        
            ' . $entrada . '
        
            ' . $saida . '
        ';
}
if ($type == 'totais_view_estoque') {

    $id = filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT);

    $consulta = $pdo->query("SELECT SUM(chapas) AS chapas, SUM(metro) AS metros FROM romaneio_produtos WHERE id_produto = " . $id);
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($linha['chapas'] != NULL) {
        $entrada = '<div class="col-xl-3"><div class="d-flex align-items-center bg-light-success rounded p-5 mt-9">
            <span class="svg-icon svg-icon-success mr-5">
                <span class="svg-icon svg-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
                            <path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>
            </span>
            <div class="d-flex flex-column flex-grow-1 mr-2">
                <span class="text-muted font-weight-bold">Chapas ' . $linha['chapas'] . '</span>
                <span class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">m² ' . number_format($linha['metros'], 3, ",", ".") . '</span>
            </div>
        </div></div>';
    }


    echo '<div class="col-xl">
         </div>
        
            ' . $entrada . '
        ';
}
if ($type == 'kt_reset') {

    unset($_SESSION['complex']);
}
/* end::Outro */