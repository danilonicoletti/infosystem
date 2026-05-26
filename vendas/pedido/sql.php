<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

/* Begin::Adicionar */

if ($type == 'pedido_add') {

    $columns = array('id_pedido', 'codigo', 'emissor', 'cliente', 'icms', 'consumidor', 'dt_emissao', 'comprador', 'vendedor', 'transportadora', 'frete', 'vl_ipi', 'vl_icms', 'vl_desconto', 'vl_outros', 'vl_pedido', 'status', 'id_empresa');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    if (filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT) == '0,00') {
        $vl_ipi = '0.00';
    } else {
        $vl_ipi = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_ipi', FILTER_DEFAULT)));
    }

    if (filter_input(INPUT_POST, 'vl_icms', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'vl_icms', FILTER_DEFAULT) == '0,00') {
        $vl_icms = '0.00';
    } else {
        $vl_icms = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_icms', FILTER_DEFAULT)));
    }

    if (filter_input(INPUT_POST, 'vl_desconto', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'vl_desconto', FILTER_DEFAULT) == '0,00') {
        $vl_desconto = '0.00';
    } else {
        $vl_desconto = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_desconto', FILTER_DEFAULT)));
    }

    if (filter_input(INPUT_POST, 'vl_outros', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'vl_outros', FILTER_DEFAULT) == '0,00') {
        $vl_outros = '0.00';
    } else {
        $vl_outros = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_outros', FILTER_DEFAULT)));
    }

    if (filter_input(INPUT_POST, 'vl_pedido', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'vl_pedido', FILTER_DEFAULT) == '0,00') {
        $vl_pedido = '0.00';
    } else {
        $vl_pedido = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'vl_pedido', FILTER_DEFAULT)));
    }

    try {
        $sql = 'INSERT INTO pedidos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_pedido' => filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT),
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':emissor' => filter_input(INPUT_POST, "emissor", FILTER_DEFAULT),
            ':cliente' => filter_input(INPUT_POST, "cliente", FILTER_DEFAULT),
            ':icms' => 0,
            ':consumidor' => 0,
            ':dt_emissao' => date('Y-m-d'),
            ':comprador' => filter_input(INPUT_POST, "comprador", FILTER_DEFAULT),
            ':vendedor' => filter_input(INPUT_POST, "vendedor", FILTER_DEFAULT),
            ':transportadora' => filter_input(INPUT_POST, "transportadora", FILTER_DEFAULT),
            ':frete' => filter_input(INPUT_POST, "frete", FILTER_DEFAULT),
            ':vl_ipi' => $vl_ipi,
            ':vl_icms' => $vl_icms,
            ':vl_desconto' => $vl_desconto,
            ':vl_outros' => $vl_outros,
            ':vl_pedido' => $vl_pedido,
            ':status' => 1,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        $_stmt_ = $pdo->prepare('UPDATE pedidos_produtos SET temp = 0 WHERE id_pedido = ' . filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT));
        $_stmt_->execute();

        $columns_ = array('id_produto', 'lancamento', 'movimento', 'documento', 'es', 'cliente_fornecedor', 'medidas', 'chapas', 'metros', 'saldo_chapas', 'saldo_metros');
        $column_insert_ = join(', ', $columns_);

        $param_insert_ = join(', ', array_map(function ($columns_) {
            return ":$columns_";
        }, $columns_));

        $sql = $pdo->query("SELECT * FROM pedidos_produtos WHERE id_pedido = " . filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT));
        while ($rowCol = $sql->fetch(PDO::FETCH_ASSOC)) {

            $sql_ = $pdo->query("SELECT codigo, cliente FROM pedidos WHERE id_pedido = " . filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT));
            $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

            $_sql_ = $pdo->query("SELECT nome FROM clientes WHERE id_cliente = " .  $rowCol_['cliente']);
            $_rowCol_ = $_sql_->fetch(PDO::FETCH_ASSOC);

            $_sql = $pdo->query("SELECT saldo_chapas, saldo_metros FROM entrada_e_saida WHERE id_produto = " .  $rowCol['id_produto'] . " ORDER BY id DESC LIMIT 0,1");
            $_rowCol = $_sql->fetch(PDO::FETCH_ASSOC);

            $saldo_chapas = $_rowCol['saldo_chapas'] - $rowCol['chapas'];
            $saldo_metros = $_rowCol['saldo_metros'] - $rowCol['metro'];

            try {
                $sql_ = 'INSERT INTO entrada_e_saida (' . $column_insert_ . ') VALUES(' . $param_insert_ . ')';
                $stmt_ = $pdo->prepare($sql_);
                $parameters_ = array(
                    ':id_produto' => $rowCol['id_produto'],
                    ':lancamento' => date('Y-m-d'),
                    ':movimento' => $rowCol_['codigo'],
                    ':documento' => 'PEDIDO',
                    ':es' => 'S ',
                    ':cliente_fornecedor' => $_rowCol_['nome'],
                    ':medidas' => $rowCol['medidas'],
                    ':chapas' => $rowCol['chapas'],
                    ':metros' => $rowCol['metro'],
                    ':saldo_chapas' => $saldo_chapas,
                    ':saldo_metros' => $saldo_metros
                );
                $stmt_->execute($parameters_);

                echo 'success||';

                // $stmt_ = $pdo->prepare('DELETE FROM romaneio_estoque WHERE codigo = :codigo');
                // $stmt_->bindParam(':codigo', $rowCol['codigo']);
                // $stmt_->execute();

                $stmt__ = $pdo->prepare('UPDATE romaneio_estoque SET unidade = "TESTE" WHERE codigo = "' . $rowCol['codigo'] . '"');
                $stmt__->execute();
            } catch (PDOException $e) {

                $number_error = rand();

                $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
                $escreve = fwrite($fp, $number_error . "|entrada_saida_add|" . debugPDO($sql_, $parameters_) . "\n\n");
                fclose($fp);

                echo "error||Algo deu errado, ao manipulara o romaneio informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
            }
        }

        $_stmt__ = $pdo->prepare('UPDATE pedidos_pagamentos SET temp = 0 WHERE id_pedido = ' . filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT));
        $_stmt__->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|pedido_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'pagamento_add') {

    if (filter_input(INPUT_POST, 'valor', FILTER_DEFAULT) == '' || filter_input(INPUT_POST, 'valor', FILTER_DEFAULT) == '0,00') {
        $valor = '0.00';
    } else {
        $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));
    }

    $columnsE = array('forma', 'id_prazo', 'valor', 'id_pedido', 'temp');
    $columnE_insert = join(', ', $columnsE);

    $paramE_insert = join(', ', array_map(function ($columnsE) {
        return ":$columnsE";
    }, $columnsE));

    try {
        $sqlE = 'INSERT INTO pedidos_pagamentos (' . $columnE_insert . ') VALUES(' . $paramE_insert . ')';
        $stmtE = $pdo->prepare($sqlE);
        $parametersE = array(
            ':forma' => filter_input(INPUT_POST, "forma", FILTER_DEFAULT),
            ':id_prazo' => filter_input(INPUT_POST, "prazo", FILTER_DEFAULT),
            ':valor' => $valor,
            ':id_pedido' => filter_input(INPUT_POST, "id_pedido", FILTER_DEFAULT),
            ':temp' => '1',

        );
        $stmtE->execute($parametersE);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|pagamento_add|" . debugPDO($sqlE, $parametersE) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'produto_add') {

    $produto_erro = 0;

    $id_pedido = filter_input(INPUT_POST, 'id_pedido', FILTER_DEFAULT);

    $columns = array('item', 'chapas', 'id_produto', 'codigo', 'descricao', 'medidas', 'metro', 'valor', 'subtotal', 'id_pedido', 'temp');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    $item = 1;

    foreach ($_SESSION['produtos'] as $key => $value) {
        $consulta = $pdo->query("SELECT * FROM romaneio_estoque WHERE id_r_estoque = " . $value);
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta_ = $pdo->query("SELECT descricao,valor FROM produtos WHERE id_produto = " . $linha['id_produto']);
        $linha_ = $consulta_->fetch(PDO::FETCH_ASSOC);

        $subtotal = $linha['metro'] * $linha_['valor'];
        try {

            $sql = 'INSERT INTO pedidos_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
            $stmt = $pdo->prepare($sql);

            $parameters = array(
                ':item' => $item,
                ':chapas' => $linha['chapas'],
                ':id_produto' => $linha['id_produto'],
                ':codigo' => $linha['codigo'],
                ':descricao' => $linha_['descricao'],
                ':medidas' => 'Esp ' . number_format($linha['espessura'], 1, ',', '') . ' Cm - ' . number_format($linha['comprimento'], 2, ',', '') . ' x ' . number_format($linha['altura'], 2, ',', ''),
                ':metro' => $linha['metro'],
                ':valor' => $linha_['valor'],
                ':subtotal' => $subtotal,
                ":id_pedido" => $id_pedido,
                ':temp' =>  "1",
            );

            $stmt->execute($parameters);
        } catch (PDOException $e) {
            $produto_erro++;

            $produto_debug .= debugPDO($sql, $parameters) . "\n";
        }

        $item++;
    }

    if ($produto_erro == 0) {
        echo "success||";
    } else {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produto_add|" . $produto_debug . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

/* End::Adicionar */

/* Begin::Editar */
if ($type == 'produtos_edit') {
    $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT);

    $columns = array('medidas', 'metro', 'valor', 'subtotal');
    $column_insert = join(', ', $columns);

    $param_update = join(', ', array_map(
        function ($columns) {
            return "$columns = :$columns";
        },
        $columns
    ));

    // $espessura = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'espessura', FILTER_DEFAULT)));
    // $comprimento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'comprimento', FILTER_DEFAULT)));
    // $altura = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'altura', FILTER_DEFAULT)));
    $metro = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metro', FILTER_DEFAULT)));
    $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor_real', FILTER_DEFAULT)));
    $subtotal = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'subtotal', FILTER_DEFAULT)));

    try {
        $sql = 'UPDATE pedidos_produtos SET ' . $param_update . ' WHERE id_p_produto = ' . $id_produto;
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':medidas' => 'Esp ' . filter_input(INPUT_POST, 'espessura', FILTER_DEFAULT) . ' Cm - ' . filter_input(INPUT_POST, 'comprimento', FILTER_DEFAULT) . ' x ' . filter_input(INPUT_POST, 'altura', FILTER_DEFAULT),
            ':metro' => $metro,
            ':valor' =>  $valor,
            ':subtotal' => $subtotal,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|produtos_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'pagamento_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM pedidos_pagamentos WHERE id_pedido_pagamento = ' . filter_input(INPUT_POST, "id", FILTER_DEFAULT));
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|pagamento_remove|DELETE FROM pedidos_pagamentos WHERE id_pedido_pagamento = " . filter_input(INPUT_POST, "id", FILTER_DEFAULT) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'materiais_remove') {

    try {

        $stmt_ = $pdo->prepare('DELETE FROM pedidos_produtos WHERE id_p_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|materiais_remove|DELETE FROM pedidos_produtos WHERE id_p_produto = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
if ($type == 'produtos_session_add') {

    $_SESSION['produtos'][] = $_POST['produtos'];
}
if ($type == 'produtos_session_remove') {

    if (($key = array_search($_POST['produtos'], $_SESSION['produtos'])) !== false) {
        unset($_SESSION['produtos'][$key]);
    }
}
if ($type == 'produtos_session_remove_all') {

    unset($_SESSION['produtos']);
}
if ($type == 'materiais_view') {
    $id_pedido = filter_input(INPUT_POST, 'id_pedido', FILTER_DEFAULT);

    $total_chapas = 0;
    $total_metro = 0;
    $total_valor = 0;

    // $consultaProd = $pdo->query("SELECT * FROM pedidos_produtos as t1 INNER JOIN produtos as t2 ON t1.id_produto = t2.id_produto  WHERE id_pedido = " . $id_pedido . " ORDER BY item ASC");
    $consultaProd = $pdo->query("SELECT id_p_produto, SUM(chapas) as chapas, descricao, medidas, SUM(metro) as metro, SUM(valor) as valor, SUM(subtotal) as subtotal FROM pedidos_produtos  WHERE id_pedido = " . $id_pedido . " GROUP BY descricao,medidas ORDER BY item ASC");
    $total = $consultaProd->rowCount();
    $item = 1;
    if ($total >= 1) {
        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            $loopProd .= '<tr>
                <td class="text-center">' . $item . '</td>
                <td class="text-center">' . $linhaProd['chapas'] . '</td>
                <td style="width:300px; text-align:center;">' . mb_strtoupper($linhaProd['descricao']) . '</td>
                <td>' . $linhaProd['medidas'] . '</td>
                <td class="text-nowrap text-right">' . number_format($linhaProd['metro'], 3, ",", "") . '</td>
                <td class="text-nowrap text-right">R$ ' . number_format($linhaProd['valor'], 2, ",", ".") . '</td>
                <td class="text-nowrap text-right">R$ ' . number_format($linhaProd['subtotal'], 2, ",", ".") . '</td>
                <td class="text-center" >
                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon viewProduto" data-toggle="modal" data-group="' . $linhaProd['id_p_produto'] . '" data-target="#viewProduto' . $linhaProd['id_p_produto'] . '" title="Visualizar">
                        <i class="la la-search"></i>
                    </a>
                </td>
            </tr>';

            $consultaProd_ = $pdo->query("SELECT id_p_produto, chapas, descricao, medidas, metro, valor, subtotal FROM pedidos_produtos  WHERE descricao = '" . $linhaProd['descricao'] . "' AND medidas = '" . $linhaProd['medidas'] . "' ORDER BY item ASC");

            while ($linhaProd_ = $consultaProd_->fetch(PDO::FETCH_ASSOC)) {

                $loopProd_ .= '<tr>
                    <td class="text-center">' . $linhaProd_['chapas'] . '</td>
                    <td style="width:300px; text-align:center;">' . mb_strtoupper($linhaProd_['descricao']) . '</td>
                    <td>' . $linhaProd_['medidas'] . '</td>
                    <td class="text-nowrap text-right">' . number_format($linhaProd_['metro'], 3, ",", "") . '</td>
                    <td class="text-nowrap text-right">R$ ' . number_format($linhaProd_['valor'], 2, ",", ".") . '</td>
                    <td class="text-nowrap text-right">R$ ' . number_format($linhaProd_['subtotal'], 2, ",", ".") . '</td>
                    <td class="text-center" >
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon editProduto" title="Visualizar" id="' . $linhaProd_['id_p_produto'] . '" >
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon removeProduto" title="Remover" data-group="' . $linhaProd['id_p_produto'] . '" id="' . $linhaProd_['id_p_produto'] . '">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>';
            }

            $modal .= '<div class="modal fade" id="viewProduto' . $linhaProd['id_p_produto'] . '" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Chapas <b>(' . mb_strtoupper($linhaProd['descricao']) . ')</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div data-scroll="true" data-height="500">
                                <div class="row mb-0">
                                    <div class="col-12">
                                            
                                        <table class="table table-striped table-bordered ">
                                            <thead style="background: #9acfea !important;">
                                                <tr>
                                                    <th class="text-center">Chapas</th>
                                                    <th style="width:300px; text-align:center;">Descrição</th>
                                                    <th scope="col">Medidas</th>
                                                    <th class="text-nowrap text-right" scope="col">M²</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Unitário</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Total</th>
                                                    <th scope="col" style="width:90px; text-align:center;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                ' . $loopProd_ . '
                                            </tbody>
                                        </table>    

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            $total_chapas += $linhaProd['chapas'];
            $total_metro += $linhaProd['metro'];
            $total_valor += $linhaProd['subtotal'];

            $item++;
        }

        $loopProd .= '<tr>
                <th class="text-center">Totais</th>
                <th class="text-center">' . $total_chapas . '</th>
                <th></th>
                <th></th>
                <th class="text-nowrap text-right">' . number_format($total_metro, 3, ",", "") . '</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>';
    } else {
        $loopProd = '<tr>
            <th class="text-center" colspan="8">Sem Produtos cadastrados</th>
        </tr>';
    }

    $txt_total = '<div class="mr-5 text-right" style="line-height: 1.25;">
        <span>Total do Pedido</span><br>
        <span style="font-weight: 800;font-size: 22px;">R$ ' . number_format($total_valor, 2, ",", ".") . '</span>
    </div>';

    echo $loopProd . "||" . $modal . "||" . number_format($total_valor, 2, ",", ".") . "||" . $txt_total;
}
if ($type == 'produtos_edit_view') {
    $id_p_produto = filter_input(INPUT_POST, 'id_p_produto', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_p_produto, chapas, descricao, medidas, metro, valor, subtotal FROM pedidos_produtos WHERE id_p_produto = " . $id_p_produto);
    $linhaProd_ = $consultaProd->fetch(PDO::FETCH_ASSOC);

    $medidas = $linhaProd_['medidas'];

    $esp = explode(' Cm - ', $medidas);
    $espessura = substr($esp[0], 4, 3);

    $com = explode(' x ', $esp[1]);

    $comprimento =  $com[0];
    $altura =  $com[1];

    echo "Editar <b>(" . mb_strtoupper($linhaProd_['descricao']) . ")</b>||" . mb_strtoupper($linhaProd_['descricao']) . "||" . $id_p_produto . "||" . $espessura . "||" . $linhaProd_['chapas'] . "||" . $comprimento . "||" . $altura . "||" . number_format($linhaProd_['metro'], 3, ",", "") . "||" . number_format($linhaProd_['valor'], 2, ",", ".") . "||" . number_format($linhaProd_['subtotal'], 2, ",", ".");
}
if ($type == 'calcular_valor') {

    if ($_POST['moeda'] == 'U$') {
        $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'USD' AND data = '" . date('Y-m-d') . "'");
        $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

        $valorMoeda = $_POST['custo'] * $linhaMoeda['valor'];
    } else if ($_POST['moeda'] == '€') {
        $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'EUR' AND data = '" . date('Y-m-d') . "'");
        $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

        $valorMoeda = $_POST['custo'] * $linhaMoeda['valor'];
    } else {
        $valorMoeda = $_POST['custo'];
    }

    $valorTotal = $valorMoeda * $_POST['metro'];

    echo number_format($valorMoeda, 2, ",", ".") . "||" . number_format($valorTotal, 2, ",", ".");
}
/* end::Outro */