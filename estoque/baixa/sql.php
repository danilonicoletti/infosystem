<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

/* Begin::Adicionar */
if ($type == 'baixa_add') {

    $baixa_error = 0;

    $id_baixa = filter_input(INPUT_POST, "id_baixa", FILTER_DEFAULT);

    $columns = array('id_baixa', 'saida', 'dt_emissao', 'id_fornecedor', 'id_empresa');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    try {
        $sql = 'INSERT INTO baixa_estoque (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_baixa' => $id_baixa,
            ':saida' => filter_input(INPUT_POST, "saida", FILTER_DEFAULT),
            ':dt_emissao' => implode("-", array_reverse(explode("/", filter_input(INPUT_POST, "dt_emissao", FILTER_DEFAULT)))),
            ':id_fornecedor' => filter_input(INPUT_POST, "id_fornecedor", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
    } catch (PDOException $e) {
        $baixa_error++;
        $error_1 = debugPDO($sql, $parameters);
    }

    if ($baixa_error == 0) {
        $stmt_ = $pdo->prepare('UPDATE baixa_produtos SET temp = 0 WHERE id_baixa = ' . $id_baixa);
        $stmt_->execute();

        $columns_ = array('id_produto', 'lancamento', 'movimento', 'documento', 'es', 'cliente_fornecedor', 'medidas', 'chapas', 'metros', 'saldo_chapas', 'saldo_metros');
        $column_insert_ = join(', ', $columns_);

        $param_insert_ = join(', ', array_map(function ($columns_) {
            return ":$columns_";
        }, $columns_));

        $sql = $pdo->query("SELECT * FROM baixa_produtos WHERE id_baixa = " . $id_baixa);
        while ($rowCol = $sql->fetch(PDO::FETCH_ASSOC)) {

            $sql_ = $pdo->query("SELECT saida,id_fornecedor FROM baixa_estoque WHERE id_baixa = " . $id_baixa);
            $rowCol_ = $sql_->fetch(PDO::FETCH_ASSOC);

            $_sql_ = $pdo->query("SELECT nome FROM fornecedor WHERE id_fornecedor = " .  $rowCol_['id_fornecedor']);
            $_rowCol_ = $_sql_->fetch(PDO::FETCH_ASSOC);

            $_sql = $pdo->query("SELECT saldo_chapas, saldo_metros FROM entrada_e_saida WHERE id_produto = " .  $rowCol['id_produto'] . " ORDER BY id DESC LIMIT 0,1");
            $_rowCol = $_sql->fetch(PDO::FETCH_ASSOC);

            $saldo_chapas = $_rowCol['saldo_chapas'] - $rowCol['chapas'];
            $saldo_metros = $_rowCol['saldo_metros'] - $rowCol['metro'];

            try {
                $sql_ = 'INSERT INTO entrada_e_saida (' . $column_insert_ . ') VALUES(' . $param_insert_ . ')';
                $stmt = $pdo->prepare($sql_);
                $parameters_ = array(
                    ':id_produto' => $rowCol['id_produto'],
                    ':lancamento' => date('Y-m-d'),
                    ':movimento' => $rowCol_['saida'],
                    ':documento' => 'SAIDA',
                    ':es' => 'S ',
                    ':cliente_fornecedor' => $_rowCol_['nome'],
                    ':medidas' => 'Esp ' . number_format($rowCol['espessura'], 1, ",", "") . ' Cm - ' . number_format($rowCol['comprimento'], 2, ",", "") . ' x ' . number_format($rowCol['altura'], 2, ",", "") . ' Metros',
                    ':chapas' => $rowCol['chapas'],
                    ':metros' => $rowCol['metro'],
                    ':saldo_chapas' => $saldo_chapas,
                    ':saldo_metros' => $saldo_metros
                );
                $stmt->execute($parameters_);

                echo 'success||';

                $stmt_ = $pdo->prepare('DELETE FROM romaneio_estoque WHERE codigo = :codigo');
                $stmt_->bindParam(':codigo', $rowCol['codigo']);
                $stmt_->execute();
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
        $escreve = fwrite($fp, $number_error . "|baixa_add|" . $error_1 . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
if ($type == 'produtos_add') {

    $sql = $pdo->query("SELECT * FROM romaneio_produtos WHERE id_r_produto = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    $columns = array('id_produto', 'codigo', 'chapas', 'unidade', 'espessura', 'comprimento', 'altura', 'metro', 'temp', 'id_baixa');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    try {

        $sql = 'INSERT INTO baixa_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':id_produto' => $rowCol['id_produto'],
            ':codigo' => $rowCol['codigo'],
            ':chapas' => $rowCol['chapas'],
            ':unidade' => $rowCol['unidade'],
            ':espessura' => $rowCol['espessura'],
            ':comprimento' => $rowCol['comprimento'],
            ':altura' => $rowCol['altura'],
            ':metro' => $rowCol['metro'],
            ':temp' =>  "1",
            ":id_baixa" => $_POST['id_baixa'],
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|produtos_baixa|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
/* End::Editar */

/* Begin::Remover */
/* end::Remover */

/* begin::Outros */
if ($type == 'fornecedores_load') {

    $sql = $pdo->query("SELECT * FROM fornecedor WHERE id_fornecedor = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['id_fornecedor'] . "|"; //0
    echo $rowCol['nome'] . "|"; //1

}

if ($type == 'produtos_view') {

    $id_baixa = filter_input(INPUT_POST, 'id_baixa', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_r_produto, t1.codigo, descricao, chapas, t1.unidade, t1.espessura, comprimento, altura, metro FROM baixa_produtos as t1 INNER JOIN produtos as t2 ON t1.id_produto = t2.id_produto WHERE id_baixa = " . $id_baixa . " ORDER BY t1.codigo ASC");
    $total = $consultaProd->rowCount();

    if ($total >= 1) {
        $i = 1;
        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            $loopProd .= '<tr>
                            <td class="text-center">' . $i . '</td>
                            <td>' . $linhaProd['codigo'] . '</td>
                            <td>' . $linhaProd['descricao'] . '</td>
                            <td>Esp ' . number_format($linhaProd['espessura'], 1, ",", "") . ' Cm - ' . number_format($linhaProd['comprimento'], 2, ",", "") . ' x ' . number_format($linhaProd['altura'], 2, ",", "") . ' Metros</td>                            
                            <td>' .  number_format($linhaProd['metro'], 3, ",", "") . '</td>
                            <td>' . $linhaProd['chapas'] . '</td>
                        </tr>';
            $i++;

            $totalChapas += $linhaProd['chapas'];
            $totalMetros += $linhaProd['metro'];
        }
        $loopProd .= '<tr style="background-color: #4a78c885;">
                        <td class="text-center"></td>
                        <td></td>
                        <td></td>
                        <td><b>TOTAL</b></td>                        
                        <td>' .  number_format($totalMetros, 3, ",", "") . '</td>
                        <td>' . $totalChapas . '</td>
                        
                    </tr>';
        echo $loopProd;
    } else {
        echo "<tr>
                 <th class='text-center' colspan='7'>Sem produtos cadastrados</th>
             </tr>";
    }
}

/* end::Outro */