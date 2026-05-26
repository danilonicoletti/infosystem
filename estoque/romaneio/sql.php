<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

/* Begin::Adicionar */
if ($type == 'romaneio_add') {

    $romaneio_error = 0;

    $id_romaneio = filter_input(INPUT_POST, "id_romaneio", FILTER_DEFAULT);

    $columns = array('id_romaneio', 'entrada', 'nota_fiscal', 'dt_emissao', 'id_fornecedor', 'comissao', 'frete', 'observacao', 'id_empresa');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    if ($_POST['comissao'] == "") {
        $comissao = 0;
    } else {
        $comissao = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "comissao", FILTER_DEFAULT)));
    }
    if ($_POST['frete'] == "") {
        $frete = 0;
    } else {
        $frete = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "frete", FILTER_DEFAULT)));
    }

    try {
        $sql = 'INSERT INTO romaneio (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_romaneio' => $id_romaneio,
            ':entrada' => filter_input(INPUT_POST, "entrada", FILTER_DEFAULT),
            ':nota_fiscal' => filter_input(INPUT_POST, "nota_fiscal", FILTER_DEFAULT),
            ':dt_emissao' => implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "dt_emissao", FILTER_DEFAULT)))),
            ':id_fornecedor' => filter_input(INPUT_POST, "id_fornecedor", FILTER_DEFAULT),
            ':comissao' => $comissao,
            ':frete' => $frete,
            ':observacao' => filter_input(INPUT_POST, "observacao", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
    } catch (PDOException $e) {
        $romaneio_error++;
        $error_1 = debugPDO($sql, $parameters);
    }

    if ($romaneio_error == 0) {
        $stmt_ = $pdo->prepare('UPDATE romaneio_produtos SET temp = 0 WHERE id_romaneio = ' . $id_romaneio);
        $stmt_->execute();

        $stmt_ = $pdo->prepare('UPDATE romaneio_estoque SET temp = 0 WHERE id_romaneio = ' . $id_romaneio);
        $stmt_->execute();

        $columns_ = array('id_produto', 'lancamento', 'movimento', 'documento', 'es', 'cliente_fornecedor', 'medidas', 'chapas', 'metros', 'saldo_chapas', 'saldo_metros', 'id_r_produto');
        $column_insert_ = join(', ', $columns_);

        $param_insert_ = join(', ', array_map(function ($columns_) {
            return ":$columns_";
        }, $columns_));

        $sql = $pdo->query("SELECT * FROM romaneio_produtos WHERE id_romaneio = " . $id_romaneio);
        while ($rowCol = $sql->fetch(PDO::FETCH_ASSOC)) {

            $sql_ = $pdo->query("SELECT entrada, nota_fiscal, id_fornecedor FROM romaneio WHERE id_romaneio = " . $id_romaneio);
            $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

            $_sql_ = $pdo->query("SELECT nome FROM fornecedor WHERE id_fornecedor = " .  $rowCol_['id_fornecedor']);
            $_rowCol_ = $_sql_->fetch(PDO::FETCH_ASSOC);

            $_sql = $pdo->query("SELECT saldo_chapas, saldo_metros FROM entrada_e_saida WHERE id_produto = " .  $rowCol['id_produto'] . " ORDER BY id DESC LIMIT 0,1");
            $_rowCol = $_sql->fetch(PDO::FETCH_ASSOC);

            $saldo_chapas = $_rowCol['saldo_chapas'] + $rowCol['chapas'];
            $saldo_metros = $_rowCol['saldo_metros'] + $rowCol['metro'];

            try {
                $sql_ = 'INSERT INTO entrada_e_saida (' . $column_insert_ . ') VALUES(' . $param_insert_ . ')';
                $stmt = $pdo->prepare($sql_);
                $parameters_ = array(
                    ':id_produto' => $rowCol['id_produto'],
                    ':lancamento' => date('Y-m-d'),
                    ':movimento' => $rowCol_['entrada'],
                    ':documento' => $rowCol_['nota_fiscal'],
                    ':es' => 'E',
                    ':cliente_fornecedor' => $_rowCol_['nome'],
                    ':medidas' => number_format($rowCol['espessura'], 1, ",", "") . ' - ' . number_format($rowCol['comprimento'], 2, ",", "") . ' x ' . number_format($rowCol['altura'], 2, ",", ""),
                    ':chapas' => $rowCol['chapas'],
                    ':metros' => $rowCol['metro'],
                    ':saldo_chapas' => $saldo_chapas,
                    ':saldo_metros' => $saldo_metros,
                    ':id_r_produto' => $rowCol['id_r_produto']
                );
                $stmt->execute($parameters_);

                echo 'success||';
            } catch (PDOException $e) {

                $number_error = rand();

                $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
                $escreve = fwrite($fp, $number_error . "|entrada_saida_add|" . debugPDO($sql_, $parameters_) . "\n\n");
                fclose($fp);

                echo "error||Algo deu errado, ao manipulara o romaneio informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
            }
        }
    } else {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|romaneio_add|" . $error_1 . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'produtos_add') {

    $id_romaneio = filter_input(INPUT_POST, 'id_romaneio', FILTER_DEFAULT);
    $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT);
    $chapas = filter_input(INPUT_POST, 'chapas', FILTER_DEFAULT);

    $consulta = $pdo->query("SELECT codigo FROM produtos WHERE id_produto = " . $id_produto);
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);

    $_consulta = $pdo->query("SELECT codigo FROM romaneio_estoque WHERE id_produto = " . $id_produto . " ORDER BY codigo DESC LIMIT 0,1");
    $_total = $_consulta->rowCount();

    if ($_total == 0) {
        $_count = 0;
    } else {
        $_array = $_consulta->fetch(PDO::FETCH_ASSOC);
        $_count = (int)substr($_array['codigo'], 9, 7);
    }

    $columns = array('id_produto', 'chapas', 'unidade', 'espessura', 'comprimento', 'altura', 'metro', 'custo', 'temp', 'id_romaneio');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    $comprimento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'comprimento', FILTER_DEFAULT)));
    $altura = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'altura', FILTER_DEFAULT)));
    $metro = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metro', FILTER_DEFAULT)));

    $_columns = array('id_produto', 'codigo', 'chapas', 'unidade', 'espessura', 'comprimento', 'altura', 'metro',  'temp', 'id_r_produto', 'id_romaneio');
    $_column_insert = join(', ', $_columns);

    $_param_insert = join(', ', array_map(function ($_columns) {
        return ":$_columns";
    }, $_columns));

    $metroEstoque = $comprimento * $altura;

    if ($_POST['custo'] == "") {
        $custo = 0;
    } else {
        $custo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'custo', FILTER_DEFAULT)));
    }

    try {

        $sql = 'INSERT INTO romaneio_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':id_produto' => filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT),
            ':chapas' => $chapas,
            ':unidade' => htmlspecialchars(filter_input(INPUT_POST, 'unidade', FILTER_DEFAULT)),
            ':espessura' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'espessura', FILTER_DEFAULT))),
            ':comprimento' => $comprimento,
            ':altura' => $altura,
            ':metro' => $metro,
            ':custo' => $custo,
            ':temp' =>  "1",
            ":id_romaneio" => $id_romaneio
        );

        $stmt->execute($parameters);
        $id_r_produto = $pdo->lastInsertId();

        for ($i = 1; $i <= $chapas; $i++) {
            $a = $i + $_count;
            try {

                $sql = 'INSERT INTO romaneio_estoque (' . $_column_insert . ') VALUES(' . $_param_insert . ')';
                $stmt = $pdo->prepare($sql);

                $parameters = array(
                    ':id_produto' => filter_input(INPUT_POST, 'id_produto', FILTER_DEFAULT),
                    ':codigo' => $linha['codigo'] . str_pad($a, 7, '0', STR_PAD_LEFT),
                    ':chapas' => 1,
                    ':unidade' => htmlspecialchars(filter_input(INPUT_POST, 'unidade', FILTER_DEFAULT)),
                    ':espessura' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'espessura', FILTER_DEFAULT))),
                    ':comprimento' => $comprimento,
                    ':altura' => $altura,
                    ':metro' => $metroEstoque,
                    ':temp' =>  "1",
                    ":id_r_produto" => $id_r_produto,
                    ":id_romaneio" => $id_romaneio
                );

                $stmt->execute($parameters);
            } catch (PDOException $e) {
                $produtos_error++;
                $produtos_debug .= debugPDO($sql, $parameters) . "\n";
            }
        }

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produtos_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'romaneio_edit') {

    $romaneio_error = 0;

    $columns = array('entrada', 'nota_fiscal', 'dt_emissao', 'id_fornecedor', 'comissao', 'frete', 'observacao', 'id_empresa');

    $param_update = join(', ', array_map(
        function ($columns) {
            return "$columns = :$columns";
        },
        $columns
    ));

    if ($_POST['comissao'] == "") {
        $comissao = 0;
    } else {
        $comissao = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "comissao", FILTER_DEFAULT)));
    }
    if ($_POST['frete'] == "") {
        $frete = 0;
    } else {
        $frete = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, "frete", FILTER_DEFAULT)));
    }

    try {
        $sql = 'UPDATE romaneio SET ' . $param_update . ' WHERE id_romaneio = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':entrada' => filter_input(INPUT_POST, "entrada", FILTER_DEFAULT),
            ':nota_fiscal' => filter_input(INPUT_POST, "nota_fiscal", FILTER_DEFAULT),
            ':dt_emissao' => implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "dt_emissao", FILTER_DEFAULT)))),
            ':id_fornecedor' => filter_input(INPUT_POST, "id_fornecedor", FILTER_DEFAULT),
            ':comissao' => $comissao,
            ':frete' => $frete,
            ':observacao' => filter_input(INPUT_POST, "observacao", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $romaneio_error++;
        $error_1 = debugPDO($sql, $parameters);
    }

    if ($romaneio_error == 0) {

        $columns_ = array('id_produto', 'lancamento', 'movimento', 'documento', 'es', 'cliente_fornecedor', 'medidas', 'chapas', 'metros', 'saldo_chapas', 'saldo_metros', 'id_r_produto');
        $column_insert_ = join(', ', $columns_);

        $param_insert_ = join(', ', array_map(function ($columns_) {
            return ":$columns_";
        }, $columns_));

        $sql = $pdo->query("SELECT * FROM romaneio_produtos WHERE temp = 1 AND id_romaneio = " . $_POST['id']);
        while ($rowCol = $sql->fetch(PDO::FETCH_ASSOC)) {

            $sql_ = $pdo->query("SELECT entrada, nota_fiscal, id_fornecedor FROM romaneio WHERE id_romaneio = " . $_POST['id']);
            $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

            $_sql_ = $pdo->query("SELECT nome FROM fornecedor WHERE id_fornecedor = " .  $rowCol_['id_fornecedor']);
            $_rowCol_ = $_sql_->fetch(PDO::FETCH_ASSOC);

            $_sql = $pdo->query("SELECT saldo_chapas, saldo_metros FROM entrada_e_saida WHERE id_produto = " .  $rowCol['id_produto'] . " ORDER BY id DESC LIMIT 0,1");
            $_rowCol = $_sql->fetch(PDO::FETCH_ASSOC);

            $saldo_chapas = $_rowCol['saldo_chapas'] + $rowCol['chapas'];
            $saldo_metros = $_rowCol['saldo_metros'] + $rowCol['metro'];

            try {
                $sql_ = 'INSERT INTO entrada_e_saida (' . $column_insert_ . ') VALUES(' . $param_insert_ . ')';
                $stmt = $pdo->prepare($sql_);
                $parameters_ = array(
                    ':id_produto' => $rowCol['id_produto'],
                    ':lancamento' => date('Y-m-d'),
                    ':movimento' => $rowCol_['entrada'],
                    ':documento' => $rowCol_['nota_fiscal'],
                    ':es' => 'E',
                    ':cliente_fornecedor' => $_rowCol_['nome'],
                    ':medidas' => number_format($rowCol['espessura'], 1, ",", "") . ' - ' . number_format($rowCol['comprimento'], 2, ",", "") . ' x ' . number_format($rowCol['altura'], 2, ",", ""),
                    ':chapas' => $rowCol['chapas'],
                    ':metros' => $rowCol['metro'],
                    ':saldo_chapas' => $saldo_chapas,
                    ':saldo_metros' => $saldo_metros,
                    ':id_r_produto' => $rowCol['id_r_produto']
                );
                $stmt->execute($parameters_);

                echo 'success||';
            } catch (PDOException $e) {

                $number_error = rand();

                $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
                $escreve = fwrite($fp, $number_error . "|entrada_saida_add|" . debugPDO($sql_, $parameters_) . "\n\n");
                fclose($fp);

                echo "error||Algo deu errado, ao manipulara o romaneio informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
            }
        }

        $stmt_ = $pdo->prepare('UPDATE romaneio_produtos SET temp = 0 WHERE id_romaneio = ' . $_POST['id']);
        $stmt_->execute();

        $stmt__ = $pdo->prepare('UPDATE romaneio_estoque SET temp = 0 WHERE id_romaneio = ' . $_POST['id']);
        $stmt__->execute();
    } else {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|romaneio_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/*
if ($type == 'produtos_add_edit') {

    $columns_ = array('id_produto', 'chapas', 'unidade', 'espessura', 'comprimento', 'altura', 'metro', 'moeda', 'custo');

    $param_update_ = join(', ', array_map(
        function ($columns_) {
            return "$columns_ = :$columns_";
        },
        $columns_
    ));

    $comprimento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'comprimentoE', FILTER_DEFAULT)));
    $altura = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'alturaE', FILTER_DEFAULT)));
    $metro = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metroE', FILTER_DEFAULT)));

    if ($_POST['custoE'] == "") {
        $custo = 0;
    } else {
        $custo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'custoE', FILTER_DEFAULT)));
    }

    try {
        $sql = 'UPDATE romaneio_produtos SET ' . $param_update_ . ' WHERE id_r_produto  = ' . $_POST['id_romaneioE'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_produto' => htmlspecialchars(filter_input(INPUT_POST, 'id_produtoE', FILTER_DEFAULT)),
            ':chapas' => htmlspecialchars(filter_input(INPUT_POST, 'chapasE', FILTER_DEFAULT)),
            ':unidade' => htmlspecialchars(filter_input(INPUT_POST, 'unidadeE', FILTER_DEFAULT)),
            ':espessura' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'espessuraE', FILTER_DEFAULT))),
            ':comprimento' => $comprimento,
            ':altura' => $altura,
            ':metro' => $metro,
            ':moeda' => htmlspecialchars(filter_input(INPUT_POST, 'moedaE', FILTER_DEFAULT)),
            ':custo' => $custo,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();
        //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|romaneio_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
*/
/* End::Editar */

/* Begin::Remover */
if ($type == 'romaneio_remove') {

    $sql = $pdo->query("SELECT id_produto FROM romaneio_produtos WHERE id_romaneio = " . $_POST['id']);
    $totalProdutos = $sql->rowCount();

    $sql = $pdo->query("SELECT id_produto FROM romaneio_estoque WHERE id_romaneio = " . $_POST['id']);
    $totalEstoque = $sql->rowCount();

    if ($totalProdutos == 0 && $totalEstoque == 0) {
        try {

            $stmt = $pdo->prepare('DELETE FROM romaneio WHERE id_romaneio = :id');
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();

            $stmt_ = $pdo->prepare('DELETE FROM romaneio_produtos WHERE id_romaneio = :id');
            $stmt_->bindParam(':id', $_POST['id']);
            $stmt_->execute();

            $stmt__ = $pdo->prepare('DELETE FROM romaneio_estoque WHERE id_romaneio = :id');
            $stmt__->bindParam(':id', $_POST['id']);
            $stmt__->execute();

            echo 'success||';
        } catch (PDOException $e) {
            $number_error = rand();
            //$erro_debug = htmlspecialchars(pdo_debugStrParams(sql_debug($stmt)));

            $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
            $escreve = fwrite($fp, $number_error . "|romaneio_delete|DELETE FROM romaneio WHERE id_romaneio = " . $_POST['id'] . "\nDELETE FROM romaneio_produtos WHERE id_romaneio = " . $_POST['id'] . "\nDELETE FROM romaneio_estoque WHERE id_romaneio = " . $_POST['id'] . "\n\n");
            fclose($fp);

            echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
        }
    } else {
        echo "error||Não é possível excluir romaneio com movimentação no estoque!";
    }
}
if ($type == 'produtos_remove') {

    try {

        $sql = $pdo->query("SELECT id_produto, chapas, metro FROM romaneio_produtos WHERE id_r_produto = " . $_POST['id']);
        $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

        $_stmt_ = $pdo->prepare('UPDATE entrada_e_saida SET saldo_chapas = saldo_chapas - ' . $rowCol['chapas'] . ' , saldo_metros = saldo_metros - ' . $rowCol['metro'] . ' WHERE id_r_produto > ' . $_POST['id'] . ' AND id_produto = ' . $rowCol['id_produto']);
        $_stmt_->execute();

        $_stmt = $pdo->prepare('DELETE FROM entrada_e_saida WHERE id_r_produto = :id_r_produto');
        $_stmt->bindParam(':id_r_produto', $_POST['id']);
        $_stmt->execute();

        $stmt_ = $pdo->prepare('DELETE FROM romaneio_produtos WHERE id_r_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        $stmt__ = $pdo->prepare('DELETE FROM romaneio_estoque WHERE id_r_produto = :id');
        $stmt__->bindParam(':id', $_POST['id']);
        $stmt__->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");

        $escreve = fwrite($fp, $number_error . "|produto_delete|UPDATE entrada_e_saida SET saldo_chapas = saldo_chapas - " . $rowCol['chapas'] . " , saldo_metros = saldo_metros - " . $rowCol['metro'] . " WHERE id_r_produto > " . $_POST['id'] . " AND id_produto = " . $rowCol['id_produto'] . "\nDELETE FROM entrada_e_saida WHERE id_r_produto = " . $_POST['id'] . "\nDELETE FROM romaneio_produtos WHERE id_r_produto = " . $_POST['id'] . "\nDELETE FROM romaneio_estoque WHERE id_r_produto = " . $_POST['id'] . " \n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
if ($type == 'fornecedores_load') {

    $sql = $pdo->query("SELECT * FROM fornecedor WHERE id_fornecedor = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_fornecedor'] . "|"; //0
    echo $rowCol['nome'] . "|"; //1

}
if ($type == 'produtos_load') {

    $sql = $pdo->query("SELECT * FROM produtos WHERE id_produto = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_produto'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1
    echo $rowCol['valor'] . "|";  //2
    echo $rowCol['unidade'] . "|"; //3
    echo $rowCol['espessura']; //4

}
if ($type == 'produtos_view') {

    $id_romaneio = filter_input(INPUT_POST, 'id_romaneio', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_r_produto, descricao, chapas, t1.unidade, t1.espessura, comprimento, altura, metro, t2.moeda, t1.custo FROM romaneio_produtos as t1 INNER JOIN produtos as t2 ON t1.id_produto = t2.id_produto WHERE id_romaneio = " . $id_romaneio . " ORDER BY t1.id_r_produto ASC");
    $total = $consultaProd->rowCount();

    if ($total >= 1) {
        $i = 1;
        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            if ($linhaProd['custo'] == 0) {
                $custo = '0,00';
            } else {
                $custo = number_format($linhaProd['custo'], 2, ",", ".");
            }

            $loopProd .= '<tr>
                            <td class="text-center">' . $i . '</td>
                            <td>' . mb_strtoupper($linhaProd['descricao']) . '</td>
                            <td>Esp ' . number_format($linhaProd['espessura'], 1, ",", "") . ' Cm - ' . number_format($linhaProd['comprimento'], 2, ",", "") . ' x ' . number_format($linhaProd['altura'], 2, ",", "") . ' Metros</td>  
                            <td>' .  number_format($linhaProd['metro'], 3, ",", "") . '</td>
                            <td>' . $linhaProd['chapas'] . '</td>
                            <td class="text-nowrap">R$ ' . $custo . '</td>
                            <td style="padding: 0px;text-align: center;">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon disabled" title="Remover" id="' . $linhaProd['id_r_produto'] . '">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        </tr>';
            $i++;

            $totalChapas += $linhaProd['chapas'];
            $totalMetros += $linhaProd['metro'];
            $totalCusto += $linhaProd['custo'];
        }
        $loopProd .= '<tr style="background-color: #4a78c885;">
                        <td class="text-center"></td>
                        <td><b>TOTAL</b></td>
                        <td></td>
                        <td>' .  number_format($totalMetros, 3, ",", "") . '</td>
                        <td>' . $totalChapas . '</td>
                        <td class="text-nowrap">R$ ' . number_format($totalCusto, 2, ",", ".") . '</td>
                        <td></td>
                    </tr>';
        echo $loopProd;
    } else {
        echo "<tr>
                 <th class='text-center' colspan='7'>Sem produtos cadastrados</th>
             </tr>";
    }
}

/* end::Outro */