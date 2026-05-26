<?
session_start();
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

/*

0 - Aguardando Assinatura
1 - Aguardando Transmissão
2 - Transmitido e Autorizado
3 - Cancelado

*/
/* Begin::Adicionar */
if ($type == 'nota_add') {

    $consultaNfe = $pdo->query("SELECT nota_fiscal FROM nfe ORDER BY id_nfe DESC LIMIT 0,1");
    $linhaNfe = $consultaNfe->fetch(PDO::FETCH_ASSOC);

    if ($linhaNfe['nota_fiscal'] == "") {
        $nota_fiscal = 2;
    } else {
        $nota_fiscal = $linhaNfe['nota_fiscal'] + 1;
    }

    $columns = array('es', 'nota_fiscal', 'id_cfop', 'data_emissao', 'data_saida', 'emissor', 'destinatario', 'frete', 'infcpl', 'status', 'recibo', 'protocolo', 'chave_acesso', 'removido', 'id_empresa');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    $dt_emissao = new DateTime(implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "dt_emissao", FILTER_DEFAULT)))));

    try {
        $sql = 'INSERT INTO nfe (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':es' => filter_input(INPUT_POST, "es", FILTER_DEFAULT),
            ':nota_fiscal' => $nota_fiscal,
            ':id_cfop' => filter_input(INPUT_POST, "id_cfop", FILTER_DEFAULT),
            ':data_emissao' => $dt_emissao->format('Y-m-d') . date('\TH:i:s'),
            ':data_saida' => '0000-00-00 00:00:00',
            ':emissor' => filter_input(INPUT_POST, "emissor", FILTER_DEFAULT),
            ':destinatario' => filter_input(INPUT_POST, "cliente", FILTER_DEFAULT),
            ':frete' => filter_input(INPUT_POST, "frete", FILTER_DEFAULT),
            ':infcpl' => filter_input(INPUT_POST, "infcpl", FILTER_DEFAULT),
            ':status' => '0',
            ':recibo' => '',
            ':protocolo' => '',
            ':chave_acesso' => '',
            ':removido' => '0',
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        $id_nfe = $pdo->lastInsertId();

        $stmt_ = $pdo->prepare('UPDATE nfe_produtos SET temp = 0 WHERE id_nfe = ' . $id_nfe);
        $stmt_->execute();

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|nfe_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'produtos_add') {

    $columns = array('codigo', 'descricao', 'unid', 'id_ncm', 'cstcson', 'qtd', 'valor', 'orig', 'p_icms',  'p_ipi', 'enq_ipi', 'vl_ipi', 'temp', 'id_nfe');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    $id_nfe = filter_input(INPUT_POST, 'id_nfe', FILTER_DEFAULT);
    $id_ncm = filter_input(INPUT_POST, 'id_ncm', FILTER_DEFAULT);

    try {

        if ($_POST['valor'] == "") {
            $valor = 0;
        } else {
            $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));
        }

        if ($_POST['vl_ipi'] == "") {
            $vl_ipi = 0;
        } else {
            $vl_ipi = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT)));
        }

        if ($_POST['p_icms'] == "") {
            $p_icms = 0;
        } else {
            $p_icms = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_icms', FILTER_DEFAULT))), 0, -1);
        }

        if ($_POST['p_ipi'] == "") {
            $p_ipi = 0;
        } else {
            $p_ipi = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_ipi', FILTER_DEFAULT))), 0, -1);
        }

        $sql = 'INSERT INTO nfe_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT),
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
            ':unid' => filter_input(INPUT_POST, 'unid', FILTER_DEFAULT),
            ':id_ncm' => $id_ncm,
            ':cstcson' => htmlspecialchars(filter_input(INPUT_POST, 'cstcson', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtd', FILTER_DEFAULT))),
            ':valor' => $valor,
            ':orig' => htmlspecialchars(filter_input(INPUT_POST, 'orig', FILTER_DEFAULT)),
            ':p_icms' => $p_icms,
            ':p_ipi' => $p_ipi,
            ':enq_ipi' => htmlspecialchars(filter_input(INPUT_POST, 'enq_ipi', FILTER_DEFAULT)),
            ':vl_ipi' => $vl_ipi,
            ':temp' => '1',
            ":id_nfe" => $id_nfe
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produtos_nfe_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'produtos_add_edit') {

    $columns = array('codigo', 'descricao', 'unid', 'id_ncm', 'cstcson', 'qtd', 'valor', 'orig', 'p_icms',  'p_ipi', 'enq_ipi', 'vl_ipi', 'temp', 'id_nfe');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    $id_nfe = filter_input(INPUT_POST, 'id_nfe', FILTER_DEFAULT);
    $id_ncm = filter_input(INPUT_POST, 'id_ncm', FILTER_DEFAULT);

    try {

        if ($_POST['valor'] == "") {
            $valor = 0;
        } else {
            $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));
        }

        if ($_POST['vl_ipi'] == "") {
            $vl_ipi = 0;
        } else {
            $vl_ipi = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT)));
        }

        if ($_POST['p_icms'] == "") {
            $p_icms = 0;
        } else {
            $p_icms = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_icms', FILTER_DEFAULT))), 0, -1);
        }

        if ($_POST['p_ipi'] == "") {
            $p_ipi = 0;
        } else {
            $p_ipi = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_ipi', FILTER_DEFAULT))), 0, -1);
        }

        $sql = 'INSERT INTO nfe_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT),
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
            ':unid' => filter_input(INPUT_POST, 'unid', FILTER_DEFAULT),
            ':id_ncm' => $id_ncm,
            ':cstcson' => htmlspecialchars(filter_input(INPUT_POST, 'cstcson', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtd', FILTER_DEFAULT))),
            ':valor' => $valor,
            ':orig' => htmlspecialchars(filter_input(INPUT_POST, 'orig', FILTER_DEFAULT)),
            ':p_icms' => $p_icms,
            ':p_ipi' => $p_ipi,
            ':enq_ipi' => htmlspecialchars(filter_input(INPUT_POST, 'enq_ipi', FILTER_DEFAULT)),
            ':vl_ipi' => $vl_ipi,
            ':temp' => '0',
            ":id_nfe" => $id_nfe
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produtos_nfe_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

/* End::Adicionar */

/* Begin::Editar */
if ($type == 'nota_edit') {

    $columnsE = array( 'id_cfop', 'infcpl');
    
    $param_updateE = join(', ', array_map(
        function ($columnsE) {
            return "$columnsE = :$columnsE";
        },
        $columnsE
    ));

    try {
        $sql = 'UPDATE nfe SET ' . $param_updateE . ' WHERE id_nfe = ' . filter_input(INPUT_POST, "id_nota", FILTER_DEFAULT);
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_cfop' => filter_input(INPUT_POST, "id_cfop", FILTER_DEFAULT),
            ':infcpl' => filter_input(INPUT_POST, "infcpl", FILTER_DEFAULT),
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|nfe_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'produtos_edit') {

    $columnsE = array('codigo', 'descricao', 'unid', 'id_ncm', 'cstcson', 'qtd', 'valor', 'orig', 'p_icms',  'p_ipi', 'enq_ipi', 'vl_ipi');

    $param_updateE = join(', ', array_map(
        function ($columnsE) {
            return "$columnsE = :$columnsE";
        },
        $columnsE
    ));

    $id_produto = filter_input(INPUT_POST, 'id_produtoE', FILTER_DEFAULT);
    $id_ncm = filter_input(INPUT_POST, 'id_ncm', FILTER_DEFAULT);

    try {

        if ($_POST['valor'] == "") {
            $valor = 0;
        } else {
            $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));
        }

        if ($_POST['vl_ipi'] == "") {
            $vl_ipi = 0;
        } else {
            $vl_ipi = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT)));
        }

        if ($_POST['p_icms'] == "") {
            $p_icms = 0;
        } else {
            $p_icms = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_icms', FILTER_DEFAULT))), 0, -1);
        }

        if ($_POST['p_ipi'] == "") {
            $p_ipi = 0;
        } else {
            $p_ipi = substr(str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'p_ipi', FILTER_DEFAULT))), 0, -1);
        }

        $sql = 'UPDATE nfe_produtos SET ' . $param_updateE . ' WHERE id_produto = ' . $id_produto;
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT),
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
            ':unid' => filter_input(INPUT_POST, 'unid', FILTER_DEFAULT),
            ':id_ncm' => $id_ncm,
            ':cstcson' => htmlspecialchars(filter_input(INPUT_POST, 'cstcson', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtd', FILTER_DEFAULT))),
            ':valor' => $valor,
            ':orig' => htmlspecialchars(filter_input(INPUT_POST, 'orig', FILTER_DEFAULT)),
            ':p_icms' => $p_icms,
            ':p_ipi' => $p_ipi,
            ':enq_ipi' => htmlspecialchars(filter_input(INPUT_POST, 'enq_ipi', FILTER_DEFAULT)),
            ':vl_ipi' => $vl_ipi,
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produtos_nfe_edit|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'produtos_remove') {

    try {

        $stmt_ = $pdo->prepare('DELETE FROM nfe_produtos WHERE id_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|produto_nfe_delete|DELETE FROM nfe_produtos WHERE id_produto = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */

if ($type == 'cfops_load') {

    $sql = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_cfop'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1

}

if ($type == 'ncms_load') {

    $sql = $pdo->query("SELECT * FROM ncm WHERE id_ncm = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_ncm'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1

}

// if ($type == 'cfops_load_edit') {

//     $sql_ = $pdo->query("SELECT id_cfop FROM nfe_produtos WHERE id_produto = " . $_POST['id']);
//     $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

//     $sql = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $rowCol_['id_cfop']);
//     $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

//     echo $rowCol['id_cfop'] . "|"; //0
//     echo $rowCol['descricao'] . "|"; //1
//     echo $rowCol['icms'] . "|";  //2
//     echo $rowCol['ipi'] . "|"; //3
//     echo $rowCol['cofins']; //4

// }

if ($type == 'ncms_load_edit') {

    $sql_ = $pdo->query("SELECT id_ncm FROM nfe_produtos WHERE id_produto = " . $_POST['id']);
    $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

    $sql = $pdo->query("SELECT * FROM ncm WHERE id_ncm = " . $rowCol_['id_ncm']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_ncm'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1

}

if ($type == 'produtos_load_edit') {

    $sql = $pdo->query("SELECT id_produto, t1.codigo, t1.descricao as descricao, cstcson, qtd, unid,  valor, orig, vl_ipi, p_ipi, enq_ipi, p_icms FROM nfe_produtos as t1  WHERE id_produto = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_produto'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1
    echo $rowCol['cstcson'] . "|"; //2
    echo number_format($rowCol['qtd'], 3, ",", "") . "|"; //3
    echo $rowCol['unid'] . "|"; //4
    echo number_format($rowCol['valor'], 4, ",", "") . "|"; //5
    if ($rowCol['p_icms'] == 0) {
        echo "|"; //6
    } else {
        echo number_format($rowCol['p_icms'], 2, ",", "") . "%|"; //6
    }
    if ($rowCol['vl_ipi'] == 0) {
        echo "|"; //7
    } else {
        echo number_format($rowCol['vl_ipi'], 4, ",", "") . "|"; //7
    }
    if ($rowCol['p_ipi'] == 0) {
        echo "|"; //8
    } else {
        echo number_format($rowCol['p_ipi'], 2, ",", "") . "%|"; //8
    }
    echo $rowCol['enq_ipi'] . "|"; //9
    echo $rowCol['codigo'] . "|"; //10
    echo $rowCol['orig'] . "|"; //10
}
/*
if ($type == 'produtos_view') {

    $id_orcamento = filter_input(INPUT_POST, 'id_orcamento', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_o_produto, item, area, t1.descricao, unid, qtd, metro, valor, acabamento, outros, acrescimo, decrescimo FROM orcamentos_produtos as t1 INNER JOIN produtos as t2 ON t1.produto = t2.id_produto WHERE temp = 1 AND id_orcamento = " . $id_orcamento . " ORDER BY item ASC");
    $total = $consultaProd->rowCount();

    if ($total >= 1) {

        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            $arrayArea[] = $linhaProd['area'];

            $quantidade = $linhaProd['qtd'];
            $metro = $linhaProd['metro'];
            $valor = $linhaProd['valor'];
            $acabamento = $linhaProd['acabamento'];
            $outros = $linhaProd['outros'];

            if ($linhaProd['acrescimo'] != 0) {
                $mod_acr_dec = $linhaProd['acrescimo'] / 100;
            } elseif ($linhaProd['decrescimo'] != 0) {
                $mod_acr_dec = -$linhaProd['decrescimo'] / 100;
            } else {
                $mod_acr_dec = 0;
            }

            $material = ($metro * $quantidade) + ($metro * $quantidade * 0);

            $vl_unitario = (($metro * $valor) + $acabamento) + ($metro * $valor * 0) + ((($metro * $valor) + $acabamento) * $mod_acr_dec) + (($outros + ($outros * $mod_acr_dec)));

            $vl_total = $quantidade * $vl_unitario;

            $loopProdArray[$linhaProd['area']] .= '<tr>
                        <td class="text-center">' . $linhaProd['item'] . '</td>
                        <td>' . $linhaProd['descricao'] . '</td>
                        <td>' .  number_format($linhaProd['qtd'], 2, ",", "") . '</td>
                        <td>' . $linhaProd['unid'] . '</td>
                        <td class="text-nowrap">R$ ' . number_format($vl_unitario, 2, ",", ".") . '</td>
                        <td class="text-nowrap">R$ ' . number_format($vl_total, 2, ",", ".") . '</td>
                        <td>
                            <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon EditProduto" title="Editar">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon remover-produto" title="Remover" id="' . $linhaProd['id_o_produto'] . '">
                                <i class="la la-trash"></i>
                            </a>
                        </td>
                    </tr>';
        }

        $arrayAreaUniq = array_unique($arrayArea);

        $i = 1;
        foreach ($arrayAreaUniq as $value) {
            $loopProd .=    '<tr class="table-primary">
                                <th class="text-center">' . $i . '</th>
                                <th colspan="6">' . $value . ' - Lamina Ultra Compacta</th>
                            </tr>' . $loopProdArray[$value];
            $i++;
        }

        echo $loopProd;
    } else {
        echo "<tr>
                 <th class='text-center' colspan='7'>Sem produtos cadastrados</th>
             </tr>";
    }
}
*/
if ($type == 'produtos_view_edit') {

    $id_nfe = filter_input(INPUT_POST, 'id_nfe', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_produto, t1.codigo, t1.descricao as descricao, qtd, unid, valor, vl_ipi, p_icms, ncm FROM nfe_produtos as t1  INNER JOIN ncm as t3 ON t1.id_ncm = t3.id_ncm WHERE id_nfe = " . $id_nfe . " ORDER BY id_produto ASC");
    $total = $consultaProd->rowCount();

    if ($total >= 1) {
        // $i = 1;
        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            $quantidade = $linhaProd['qtd'];
            $valor = $linhaProd['valor'];

            $vl_total = $valor * $quantidade;
            $vl_total_ipi = $vl_total + $linhaProd['vl_ipi'];

            if ($linhaProd['p_icms'] != 0) {
                $vl_total_icms = ($vl_total / 100) * $linhaProd['p_icms'];
            }

            $loopProd .= '<tr>
                            <td class="text-center">' . $linhaProd['codigo'] . '</td>
                            <td>' . $linhaProd['descricao'] . '</td>
                            <td>' .  number_format($quantidade, 3, ",", "") . '</td>
                            <td>' . $linhaProd['unid'] . '</td>
                            <td>' . $linhaProd['ncm'] . '</td>
                            <td class="text-nowrap">R$ ' . number_format($valor, 2, ",", ".") . '</td>
                            <td class="text-nowrap">R$ ' . number_format($vl_total_ipi, 2, ",", ".") . '</td>
                            <td>
                                <a href="javascript:;" id="' . $linhaProd['id_produto'] . '" class="btn btn-sm btn-clean btn-icon EditProduto" title="Editar">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon remover-produto" title="Remover" id="' . $linhaProd['id_produto'] . '">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        </tr>';

            $total_ipi += $linhaProd['vl_ipi'];
            $total_icms += $vl_total_icms;

            $total_total = $vl_total + $total_ipi;
        }

        $totalProd = '<div class="d-flex align-items-center flex-lg-fill mr-5 my-1 ml-15">
                        <span class="mr-4">
                            <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Base de Calculo ICMS</span>
                            <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span>' . number_format($total_total, 2, ",", ".") . '</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Total ICMS</span>
                            <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span>' . number_format($total_icms, 2, ",", ".") . '</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Total IPI</span>
                            <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span>' . number_format($total_ipi, 2, ",", ".") . '</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Total dos Produtos</span>
                            <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span>' . number_format($total_total, 2, ",", ".") . '</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                        <span class="mr-4">
                            <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Total da Nota</span>
                            <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span>' . number_format($total_total, 2, ",", ".") . '</span>
                        </div>
                    </div>';
        echo $loopProd . "||" . $totalProd;
    } else {
        echo "<tr>
                 <th class='text-center' colspan='9'>Sem produtos cadastrados</th>
             </tr>||";
    }
}

if ($type == 'carta_correcao') {

    $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);

    $consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);

    $id_nfe = $linha['id_nfe'];

    $consultaEmissor = $pdo->query("SELECT * FROM usuarios WHERE id = " . $linha['emissor']);
    $linhaEmissor = $consultaEmissor->fetch(PDO::FETCH_ASSOC);

    $consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['destinatario']);
    $linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);

    if ($linhaCliente['pessoa'] == 'fisica') {
        $cliente = $linhaCliente['nome'] . " " . $linhaCliente['nomes'];
    } else {
        $cliente = $linhaCliente['nomes'];
    }

    $consultaCFOP = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $linha['id_cfop']);
    $linhaCFOP = $consultaCFOP->fetch(PDO::FETCH_ASSOC);

    $data = new DateTime($linha['data_emissao']);

    switch ($linha['frete']) {
        case '0':
            $frete = 'Emitente';
            break;
        case '1':
            $frete = 'Destinatário';
            break;
        case '2':
            $frete = 'Terceiros';
            break;
        case '9':
            $frete = 'Sem Transporte';
            break;
    }

    echo $data->format('d/m/Y') . '||' . $linhaEmissor['nome'] . '||' . $cliente . '||' . $frete . '||' . $linhaCFOP['descricao'] . '||' .  $id_nfe;
}

if ($type == 'retornar_assinatura') {

    $sql = $pdo->query("SELECT chave_acesso FROM nfe WHERE id_nfe = " . filter_input(INPUT_POST, "id", FILTER_DEFAULT));
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    unlink('assinadas/' . $rowCol['chave_acesso'] . '.xml');
    // unlink('/nota-fiscal/assinadas/' . $rowCol['chave_acesso'] . '.xml');

    $_columns_ = array('status', 'chave_acesso');
    $_param_update_ = join(', ', array_map(
        function ($_columns_) {
            return "$_columns_ = :$_columns_";
        },
        $_columns_
    ));

    try {

        $sql = 'UPDATE nfe SET ' . $_param_update_ . ' WHERE id_nfe = ' . filter_input(INPUT_POST, "id", FILTER_DEFAULT);
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':status' => 0,
            ':chave_acesso' => '',
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Outro */