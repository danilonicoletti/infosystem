<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('id_orcamento', 'os', 'complemento', 'data', 'emissor', 'vendedor', 'cliente', 'telefone', 'email', 'validade', 'obra', 'endereco', 'tipo_contato', 'contato', 'prazo', 'condicao', 'desconto', 'frete', 'custos', 'acrescimo', 'acabamento', 'observacao', 'obs_frete', 'obs_custos', 'tempo', 'sem', 'status', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$columns_ = array('complemento', 'emissor', 'vendedor', 'cliente', 'telefone', 'email', 'validade', 'obra', 'endereco',  'tipo_contato', 'contato', 'prazo', 'condicao', 'desconto', 'frete', 'custos', 'acrescimo', 'acabamento', 'observacao', 'obs_frete', 'obs_custos', 'tempo', 'sem');

$param_update = join(', ', array_map(
    function ($columns_) {
        return "$columns_ = :$columns_";
    },
    $columns_
));

/* Begin::Adicionar */
if ($type == 'orcamento_add') {

    $dataNow = new DateTime('now');

    if (filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT) == '') {
        $desconto = 0;
    } else {
        $desconto = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT)));
    }
    if (filter_input(INPUT_POST, 'frete', FILTER_DEFAULT) == '') {
        $frete = 0;
    } else {
        $frete = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'frete', FILTER_DEFAULT)));
    }
    if (filter_input(INPUT_POST, 'custos', FILTER_DEFAULT) == '') {
        $custos = 0;
    } else {
        $custos = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'custos', FILTER_DEFAULT)));
    }
    if (filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT) == '') {
        $acrescimo = 0;
    } else {
        $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT)));
    }

    //$acabamentoJson = json_encode($_POST['acabamentoGeral']);

    if (isset($_POST['sem'])) {
        $sem = 1;

        $columnsC = array('codigo', 'pessoa',  'nome', 'nomes', 'documento', 'documentos', 'email', 'contato', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cod_municipio', 'municipio', 'uf',  'cep2', 'endereco2', 'numero2', 'complemento2', 'bairro2', 'cod_municipio2', 'municipio2', 'uf2', 'sem', 'id_empresa');
        $columnC_insert = join(', ', $columnsC);

        $paramC_insert = join(', ', array_map(function ($columnsC) {
            return ":$columnsC";
        }, $columnsC));

        $sqlC = 'INSERT INTO clientes (' . $columnC_insert . ') VALUES(' . $paramC_insert . ')';
        $stmtC = $pdo->prepare($sqlC);
        $parametersC = array(
            ':codigo' => "",
            ':pessoa' => "fisica",
            ':nome' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
            ':documento' => "",
            ':documentos' => "",
            ':email' => "",
            ':contato' => "",
            ':telefone' => "",
            ':celular' => "",
            ':cep' => "",
            ':endereco' => "",
            ':numero' => "",
            ':complemento' => "",
            ':bairro' => "",
            ':cod_municipio' => "",
            ':municipio' => "",
            ':uf' => "",
            ':cep2' => "",
            ':endereco2' => "",
            ':numero2' => "",
            ':complemento2' => "",
            ':bairro2' => "",
            ':cod_municipio2' => "",
            ':municipio2' => "",
            ':uf2' => "",
            ':sem' => 1,
            ':id_empresa' => $company,
        );
        $stmtC->execute($parametersC);

        $id_cliente = $pdo->lastInsertId();
        $cliente = $id_cliente;
    } else {
        $sem = 0;

        $cliente = filter_input(INPUT_POST, "cliente", FILTER_DEFAULT);
    }

    try {
        $sql = 'INSERT INTO orcamentos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':id_orcamento' => filter_input(INPUT_POST, "id_orcamento", FILTER_DEFAULT),
            ':os' => filter_input(INPUT_POST, "os", FILTER_DEFAULT),
            ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
            ':data' => $dataNow->format('Y-m-d'),
            ':emissor' => filter_input(INPUT_POST, "emissor", FILTER_DEFAULT),
            ':vendedor' => filter_input(INPUT_POST, "vendedor", FILTER_DEFAULT),
            ':cliente' => $cliente,
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':validade' => filter_input(INPUT_POST, "validade", FILTER_DEFAULT),
            ':obra' => filter_input(INPUT_POST, "obra", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':tipo_contato' => filter_input(INPUT_POST, "tipo_contato", FILTER_DEFAULT),
            ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
            ':prazo' => filter_input(INPUT_POST, "prazo", FILTER_DEFAULT),
            ':condicao' => filter_input(INPUT_POST, "condicao", FILTER_DEFAULT),
            ':desconto' => $desconto,
            ':frete' => $frete,
            ':custos' => $custos,
            ':acrescimo' => $acrescimo,
            ':acabamento' => "null",
            ':observacao' => filter_input(INPUT_POST, 'observacao', FILTER_DEFAULT),
            ':obs_frete' => filter_input(INPUT_POST, 'obs_frete', FILTER_DEFAULT),
            ':obs_custos' => filter_input(INPUT_POST, 'obs_custos', FILTER_DEFAULT),
            ':tempo' => filter_input(INPUT_POST, 'tempo', FILTER_DEFAULT),
            ':sem' => $sem,
            ':status' => "1",
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);

        $stmt_ = $pdo->prepare('UPDATE orcamentos_produtos SET temp = 0 WHERE id_orcamento = ' . filter_input(INPUT_POST, "id_orcamento", FILTER_DEFAULT));
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|orcamento_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'add_vendedor') {

    $columnsE = array('nome', 'documento', 'email', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'funcao', 'p_comissao', 'status', 'id_empresa');
    $columnE_insert = join(', ', $columnsE);

    $paramE_insert = join(', ', array_map(function ($columnsE) {
        return ":$columnsE";
    }, $columnsE));

    try {
        $sqlE = 'INSERT INTO vendedor (' . $columnE_insert . ') VALUES(' . $paramE_insert . ')';
        $stmtE = $pdo->prepare($sqlE);
        $parametersE = array(
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':documento' => '',
            ':email' => '',
            ':telefone' => '',
            ':celular' => '',
            ':cep' => '',
            ':endereco' => '',
            ':numero' => '',
            ':bairro' => '',
            ':cidade' => '',
            ':estado' => '',
            ':funcao' => '',
            ':p_comissao' => 0,
            ':status' => "1",
            ':id_empresa' => $company,
        );
        $stmtE->execute($parametersE);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_add|" . debugPDO($sqlE, $parametersE) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'add_cliente') {

    $columnsC = array('codigo', 'pessoa',  'nome', 'nomes', 'documento', 'documentos', 'email', 'contato', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cod_municipio', 'municipio', 'uf', 'cep2', 'endereco2', 'numero2', 'complemento2', 'bairro2', 'cod_municipio2', 'municipio2', 'uf2', 'sem', 'id_empresa');
    $columnC_insert = join(', ', $columnsC);

    $paramC_insert = join(', ', array_map(function ($columnsC) {
        return ":$columnsC";
    }, $columnsC));

    try {
        $sqlC = 'INSERT INTO clientes (' . $columnC_insert . ') VALUES(' . $paramC_insert . ')';
        $stmtC = $pdo->prepare($sqlC);
        $parametersC = array(
            ':codigo' => "",
            ':pessoa' => "fisica",
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':documento' => "",
            ':documentos' => "",
            ':email' => "",
            ':contato' => "",
            ':telefone' => "",
            ':celular' => "",
            ':cep' => "",
            ':endereco' => "",
            ':numero' => "",
            ':complemento' => "",
            ':bairro' => "",
            ':cod_municipio' => "",
            ':municipio' => "",
            ':uf' => "",
            ':cep2' => "",
            ':endereco2' => "",
            ':numero2' => "",
            ':complemento2' => "",
            ':bairro2' => "",
            ':cod_municipio2' => "",
            ':municipio2' => "",
            ':uf2' => "",
            ':sem' => 1,
            ':id_empresa' => $company,
        );
        $stmtC->execute($parametersC);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cliente_add|" . debugPDO($sqlC, $parametersC) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_add') {

    $id_orcamento = filter_input(INPUT_POST, 'id_orcamento', FILTER_DEFAULT);
    $id_material = filter_input(INPUT_POST, 'id_material', FILTER_DEFAULT);
    $ambiente = filter_input(INPUT_POST, 'ambiente', FILTER_DEFAULT);

    $sqlAmbiente = $pdo->query("SELECT ambiente FROM orcamentos_produtos WHERE ambiente = '" . $ambiente . "' AND material = " . $id_material . " AND id_orcamento = " . $id_orcamento);
    $rowColAmbiente = $sqlAmbiente->rowCount() + 1;

    $columns = array('item', 'ambiente', 'material', 'descricao', 'qtd', 'unid', 'valor', 'metro', 'acabamento', 'outros', 'acrescimo', 'observacao', 'temp', 'id_orcamento');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    try {

        $item = $rowColAmbiente;

        if ($_POST['acrescimo'] != '') {
            $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT)));
        } else {
            $acrescimo = 0;
        }

        if ($_POST['acabamento'] == "") {
            $acabamento = 0;
        } else {
            $acabamento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acabamento', FILTER_DEFAULT)));
        }

        if ($_POST['outros'] == "") {
            $outros = 0;
        } else {
            $outros = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'outros', FILTER_DEFAULT)));
        }

        $sql = 'INSERT INTO orcamentos_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':item' => $item,
            ':ambiente' => $ambiente,
            ':material' => $id_material,
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtd', FILTER_DEFAULT))),
            ':unid' => filter_input(INPUT_POST, 'unid', FILTER_DEFAULT),
            ':valor' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT))),
            ':metro' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metro', FILTER_DEFAULT))),
            ':acabamento' => $acabamento,
            ':outros' => $outros,
            ':acrescimo' => $acrescimo,
            ':observacao' => filter_input(INPUT_POST, 'observacao', FILTER_DEFAULT),
            ':temp' =>  "1",
            ":id_orcamento" => $id_orcamento
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|materiais_add|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_add_edit') {

    $id_orcamento = filter_input(INPUT_POST, 'id_orcamento', FILTER_DEFAULT);
    $id_material = filter_input(INPUT_POST, 'id_material', FILTER_DEFAULT);
    $ambiente = filter_input(INPUT_POST, 'ambiente', FILTER_DEFAULT);

    $sqlAmbiente = $pdo->query("SELECT ambiente FROM orcamentos_produtos WHERE ambiente = '" . $ambiente . "' AND material = " . $id_material . " AND id_orcamento = " . $id_orcamento);
    $rowColAmbiente = $sqlAmbiente->rowCount() + 1;

    $columns = array('item', 'ambiente', 'material', 'descricao', 'qtd', 'unid', 'valor', 'metro', 'acabamento', 'outros', 'acrescimo', 'observacao', 'temp', 'id_orcamento');
    $column_insert = join(', ', $columns);

    $param_insert = join(', ', array_map(function ($columns) {
        return ":$columns";
    }, $columns));

    try {

        $item = $rowColAmbiente;

        if ($_POST['acrescimo'] != '') {
            $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT)));
        } else {
            $acrescimo = 0;
        }

        if ($_POST['acabamento'] == "") {
            $acabamento = 0;
        } else {
            $acabamento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acabamento', FILTER_DEFAULT)));
        }

        if ($_POST['outros'] == "") {
            $outros = 0;
        } else {
            $outros = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'outros', FILTER_DEFAULT)));
        }

        $sql = 'INSERT INTO orcamentos_produtos (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);

        $parameters = array(
            ':item' => $item,
            ':ambiente' => htmlspecialchars($ambiente),
            ':material' => $id_material,
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtd', FILTER_DEFAULT))),
            ':unid' => filter_input(INPUT_POST, 'unid', FILTER_DEFAULT),
            ':valor' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT))),
            ':metro' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metro', FILTER_DEFAULT))),
            ':acabamento' => $acabamento,
            ':outros' => $outros,
            ':acrescimo' => $acrescimo,
            ':observacao' => filter_input(INPUT_POST, 'observacao', FILTER_DEFAULT),
            ':temp' =>  "0",
            ":id_orcamento" => $id_orcamento
        );

        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|materiais_add_edit|" . debugPDO($sql, $parameters) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'orcamento_edit') {

    if (isset($_POST['salvar_novo'])) {
        $erroNovo = 0;

        $dataNow = new DateTime('now');

        if (filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT) == '') {
            $desconto = 0;
        } else {
            $desconto = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'frete', FILTER_DEFAULT) == '') {
            $frete = 0;
        } else {
            $frete = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'frete', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'custos', FILTER_DEFAULT) == '') {
            $custos = 0;
        } else {
            $custos = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'custos', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT) == '') {
            $acrescimo = 0;
        } else {
            $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT)));
        }

        //$acabamentoJson = json_encode($_POST['acabamentoGeral']);

        if (isset($_POST['sem'])) {
            $sem = 1;

            $columnsC = array('codigo', 'pessoa',  'nome', 'nomes', 'documento', 'documentos', 'email', 'contato', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cod_municipio', 'municipio', 'uf', 'cep2', 'endereco2', 'numero2', 'complemento2', 'bairro2', 'cod_municipio2', 'municipio2', 'uf2', 'sem', 'id_empresa');
            $columnC_insert = join(', ', $columnsC);

            $paramC_insert = join(', ', array_map(function ($columnsC) {
                return ":$columnsC";
            }, $columnsC));

            $sqlC = 'INSERT INTO clientes (' . $columnC_insert . ') VALUES(' . $paramC_insert . ')';
            $stmtC = $pdo->prepare($sqlC);
            $parametersC = array(
                ':codigo' => "",
                ':pessoa' => "fisica",
                ':nome' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
                ':nomes' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
                ':documento' => "",
                ':documentos' => "",
                ':email' => "",
                ':contato' => "",
                ':telefone' => "",
                ':celular' => "",
                ':cep' => "",
                ':endereco' => "",
                ':numero' => "",
                ':complemento' => "",
                ':bairro' => "",
                ':cod_municipio' => "",
                ':municipio' => "",
                ':uf' => "",
                ':cep2' => "",
                ':endereco2' => "",
                ':numero2' => "",
                ':complemento2' => "",
                ':bairro2' => "",
                ':cod_municipio2' => "",
                ':municipio2' => "",
                ':uf2' => "",
                ':sem' => 1,
                ':id_empresa' => $company,
            );
            $stmtC->execute($parametersC);

            $id_cliente = $pdo->lastInsertId();
            $cliente = $id_cliente;
        } else {
            $sem = 0;

            $cliente = filter_input(INPUT_POST, "cliente", FILTER_DEFAULT);
        }

        $consulta = $pdo->query("SELECT id_orcamento FROM orcamentos WHERE id_empresa = " . $company . " ORDER BY id_orcamento DESC LIMIT 0,1");
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

        $id_orcamento = $linha['id_orcamento'] + 1;

        try {
            $sql = 'INSERT INTO orcamentos (' . $column_insert . ') VALUES(' . $param_insert . ')';
            $stmt = $pdo->prepare($sql);
            $parameters = array(
                ':id_orcamento' => $id_orcamento,
                ':os' => filter_input(INPUT_POST, "os_novo", FILTER_DEFAULT),
                ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
                ':data' => $dataNow->format('Y-m-d'),
                ':emissor' => filter_input(INPUT_POST, "emissor", FILTER_DEFAULT),
                ':vendedor' => filter_input(INPUT_POST, "vendedor", FILTER_DEFAULT),
                ':cliente' => $cliente,
                ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
                ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
                ':validade' => filter_input(INPUT_POST, "validade", FILTER_DEFAULT),
                ':obra' => filter_input(INPUT_POST, "obra", FILTER_DEFAULT),
                ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
                ':tipo_contato' => filter_input(INPUT_POST, "tipo_contato", FILTER_DEFAULT),
                ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
                ':prazo' => filter_input(INPUT_POST, "prazo", FILTER_DEFAULT),
                ':condicao' => filter_input(INPUT_POST, "condicao", FILTER_DEFAULT),
                ':desconto' => $desconto,
                ':frete' => $frete,
                ':custos' => $custos,
                ':acrescimo' => $acrescimo,
                ':acabamento' => "null",
                ':observacao' => filter_input(INPUT_POST, 'observacao', FILTER_DEFAULT),
                ':obs_frete' => filter_input(INPUT_POST, 'obs_frete', FILTER_DEFAULT),
                ':obs_custos' => filter_input(INPUT_POST, 'obs_custos', FILTER_DEFAULT),
                ':tempo' => filter_input(INPUT_POST, 'tempo', FILTER_DEFAULT),
                ':sem' => $sem,
                ':status' => "1",
                ':id_empresa' => $company,
            );
            $stmt->execute($parameters);
        } catch (PDOException $e) {

            $erroNovo++;

            $debugParam = debugPDO($sql, $parameters);
        }

        $columns_add_p = array('item', 'ambiente',  'material', 'descricao', 'qtd', 'unid', 'valor', 'metro', 'acabamento', 'outros', 'acrescimo', 'observacao', 'temp', 'id_orcamento');
        $column_insert_add_p = join(', ', $columns_add_p);

        $param_insert_add_p = join(', ', array_map(function ($columns_add_p) {
            return ":$columns_add_p";
        }, $columns_add_p));

        $sql = $pdo->query("SELECT * FROM orcamentos_produtos WHERE id_orcamento = " . $_POST['id_orcamento']);
        while ($rowCol = $sql->fetch(PDO::FETCH_ASSOC)) {
            try {
                $sql_ = 'INSERT INTO orcamentos_produtos (' . $column_insert_add_p . ') VALUES(' . $param_insert_add_p . ')';
                $stmt_ = $pdo->prepare($sql_);

                $parameters_add_p = array(
                    ':item' => $rowCol['item'],
                    ':ambiente' => $rowCol['ambiente'],
                    ':material' => $rowCol['material'],
                    ':descricao' => $rowCol['descricao'],
                    ':qtd' => $rowCol['qtd'],
                    ':unid' => $rowCol['unid'],
                    ':valor' => $rowCol['valor'],
                    ':metro' => $rowCol['metro'],
                    ':acabamento' => $rowCol['acabamento'],
                    ':outros' => $rowCol['outros'],
                    ':acrescimo' => $rowCol['acrescimo'],
                    ':observacao' => $rowCol['observacao'],
                    ':temp' =>  "0",
                    ":id_orcamento" => $id_orcamento
                );

                $stmt_->execute($parameters_add_p);
            } catch (PDOException $e) {

                $erroNovo++;

                $debuGOP .= debugPDO($sql_, $parameters_add_p) . '\n';
            }
        }

        if ($erroNovo == 0) {
            echo 'success||';
        } else {

            $number_error = rand();

            $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
            $escreve = fwrite($fp, $number_error . "|orcamento_add_novo|" . $debugParam . "\n" . $debuGOP . "\n\n");
            fclose($fp);

            echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
        }
    } else {

        if (filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT) == '') {
            $desconto = 0;
        } else {
            $desconto = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'frete', FILTER_DEFAULT) == '') {
            $frete = 0;
        } else {
            $frete = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'frete', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'custos', FILTER_DEFAULT) == '') {
            $custos = 0;
        } else {
            $custos = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'custos', FILTER_DEFAULT)));
        }
        if (filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT) == '') {
            $acrescimo = 0;
        } else {
            $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimo', FILTER_DEFAULT)));
        }

        //$acabamentoJson = json_encode($_POST['acabamentoGeral']);

        if (isset($_POST['sem'])) {

            $columns_C = array('nome', 'nomes');

            $param_updateC = join(', ', array_map(
                function ($columns_C) {
                    return "$columns_C = :$columns_C";
                },
                $columns_C
            ));

            $sqlC = 'UPDATE clientes SET ' . $param_updateC . ' WHERE nome = "' . filter_input(INPUT_POST, "clientesemOld", FILTER_DEFAULT) . '" AND nomes = "' . filter_input(INPUT_POST, "clientesemOld", FILTER_DEFAULT) . '" AND sem = 1 AND id_empresa = ' . $company;
            $stmtC = $pdo->prepare($sqlC);
            $parametersC = array(
                ':nome' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
                ':nomes' => filter_input(INPUT_POST, "clientesem", FILTER_DEFAULT),
            );
            $stmtC->execute($parametersC);

            $sem = 1;
            $cliente = filter_input(INPUT_POST, "id_cliente", FILTER_DEFAULT);
        } else {

            $sem = 0;
            $cliente = filter_input(INPUT_POST, "cliente", FILTER_DEFAULT);
        }

        try {
            $sql = 'UPDATE orcamentos SET ' . $param_update . ' WHERE id_orcamento = ' . $_POST['id_orcamento'];
            $stmt = $pdo->prepare($sql);
            $parameters = array(
                ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
                ':emissor' => filter_input(INPUT_POST, "emissor", FILTER_DEFAULT),
                ':vendedor' => filter_input(INPUT_POST, "vendedor", FILTER_DEFAULT),
                ':cliente' => $cliente,
                ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
                ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
                ':validade' => filter_input(INPUT_POST, "validade", FILTER_DEFAULT),
                ':obra' => filter_input(INPUT_POST, "obra", FILTER_DEFAULT),
                ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
                ':tipo_contato' => filter_input(INPUT_POST, "tipo_contato", FILTER_DEFAULT),
                ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
                ':prazo' => filter_input(INPUT_POST, "prazo", FILTER_DEFAULT),
                ':condicao' => filter_input(INPUT_POST, "condicao", FILTER_DEFAULT),
                ':desconto' => $desconto,
                ':frete' => $frete,
                ':custos' => $custos,
                ':acrescimo' => $acrescimo,
                ':acabamento' => "null",
                ':observacao' => filter_input(INPUT_POST, 'observacao', FILTER_DEFAULT),
                ':obs_frete' => filter_input(INPUT_POST, 'obs_frete', FILTER_DEFAULT),
                ':obs_custos' => filter_input(INPUT_POST, 'obs_custos', FILTER_DEFAULT),
                ':tempo' => filter_input(INPUT_POST, 'tempo', FILTER_DEFAULT),
                ':sem' => $sem,
            );
            $stmt->execute($parameters);

            echo "success||";
        } catch (PDOException $e) {
            $number_error = rand();

            $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
            $escreve = fwrite($fp, $number_error . "|orcamento_edit|" . debugPDO($sql, $parameters) . "\n\n");
            fclose($fp);

            echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
        }
    }
}

if ($type == 'materiais_edit_edit') {

    $id_orcamento = filter_input(INPUT_POST, 'id_orcamento', FILTER_DEFAULT);
    $id_produto = filter_input(INPUT_POST, 'id_materialE', FILTER_DEFAULT);

    $columnsE = array('ambiente', 'material', 'descricao', 'qtd', 'unid', 'valor', 'metro', 'acabamento', 'outros', 'acrescimo', 'observacao');

    $param_updateE = join(', ', array_map(
        function ($columnsE) {
            return "$columnsE = :$columnsE";
        },
        $columnsE
    ));

    try {

        if ($_POST['acrescimoE'] != '') {
            $acrescimo = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acrescimoE', FILTER_DEFAULT)));
        } else {
            $acrescimo = 0;
        }

        if ($_POST['acabamentoE'] == "") {
            $acabamento = 0;
        } else {
            $acabamento = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'acabamentoE', FILTER_DEFAULT)));
        }

        if ($_POST['outrosE'] == "") {
            $outros = 0;
        } else {
            $outros = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'outrosE', FILTER_DEFAULT)));
        }

        $sqlE = 'UPDATE orcamentos_produtos SET ' . $param_updateE . ' WHERE id_o_produto = ' . $_POST['idE'];
        $stmtE = $pdo->prepare($sqlE);

        $parametersE = array(
            ':ambiente' => htmlspecialchars(filter_input(INPUT_POST, 'ambienteE', FILTER_DEFAULT)),
            ':material' => $id_produto,
            ':descricao' => htmlspecialchars(filter_input(INPUT_POST, 'descricaoE', FILTER_DEFAULT)),
            ':qtd' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'qtdE', FILTER_DEFAULT))),
            ':unid' => filter_input(INPUT_POST, 'unidE', FILTER_DEFAULT),
            ':valor' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valorE', FILTER_DEFAULT))),
            ':metro' => str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'metroE', FILTER_DEFAULT))),
            ':acabamento' => $acabamento,
            ':outros' => $outros,
            ':acrescimo' => $acrescimo,
            ':observacao' => filter_input(INPUT_POST, 'observacaoE', FILTER_DEFAULT),
        );

        $stmtE->execute($parametersE);

        echo "success||";
    } catch (PDOException $e) {

        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        fwrite($fp, $number_error . "|materiais_edit_edit|" . debugPDO($sqlE, $parametersE) . "\n\n");

        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'orcamentos_remove') {

    try {

        $sql = $pdo->query("SELECT cliente FROM orcamentos WHERE id_orcamento = " . $_POST['id']);
        $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

        $stmt0 = $pdo->prepare('DELETE FROM clientes WHERE id_cliente = ' . $rowCol['cliente'] . ' AND sem = 1');
        $stmt0->execute();

        $stmt = $pdo->prepare('DELETE FROM orcamentos WHERE id_orcamento = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        $stmt_ = $pdo->prepare('DELETE FROM orcamentos_produtos WHERE id_orcamento = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|orcamento_delete|DELETE FROM orcamentos WHERE id_orcamento = " . $_POST['id'] . "\nDELETE FROM orcamentos_produtos WHERE id_orcamento = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_remove') {

    try {

        $stmt_ = $pdo->prepare('DELETE FROM orcamentos_produtos WHERE id_o_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|materiais_remove|DELETE FROM orcamentos_produtos WHERE id_o_produto = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

/* end::Remover */

/* begin::Outros */
if ($type == 'materiais_copiar') {

    try {

        $stmt = $pdo->prepare('TRUNCATE TABLE orcamentos_produtos_temp');
        $stmt->execute();

        $stmt_ = $pdo->prepare('INSERT INTO orcamentos_produtos_temp (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento) SELECT item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento FROM orcamentos_produtos WHERE id_o_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|materiais_copiar|INSERT INTO orcamentos_produtos_temp (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento) SELECT item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento FROM orcamentos_produtos WHERE id_o_produto =" . $_POST['id'] . "\nTRUNCATE TABLE orcamentos_produtos_temp\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_colar') {

    try {

        $stmt = $pdo->prepare('UPDATE orcamentos_produtos_temp SET id_orcamento = :id WHERE id_o_produto = 1');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        $stmt_ = $pdo->prepare('INSERT INTO orcamentos_produtos (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo,  observacao, temp, id_orcamento) SELECT item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo,  observacao, temp, id_orcamento FROM orcamentos_produtos_temp WHERE id_o_produto = 1');
        $stmt_->execute();

        $stmt__ = $pdo->prepare('TRUNCATE TABLE orcamentos_produtos_temp');
        $stmt__->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|materiais_colar|UPDATE orcamentos_produtos_temp SET id_orcamento = " . $_POST['id'] . " WHERE id_o_produto = 1\nINSERT INTO orcamentos_produtos (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo,  observacao, temp, id_orcamento) SELECT item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento FROM orcamentos_produtos_temp WHERE id_o_produto = 1\nTRUNCATE TABLE orcamentos_produtos_temp\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_duplicar') {

    try {

        $stmt_ = $pdo->prepare('INSERT INTO orcamentos_produtos (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento) SELECT item, ambiente, material,  descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento FROM orcamentos_produtos WHERE id_o_produto = :id');
        $stmt_->bindParam(':id', $_POST['id']);
        $stmt_->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|materiais_duplicar|INSERT INTO orcamentos_produtos (item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento) SELECT item, ambiente, material, descricao, qtd, unid, valor, metro, acabamento, outros, acrescimo, observacao, temp, id_orcamento FROM orcamentos_produtos WHERE id_o_produto  " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'ativar') {
    try {
        $stmt = $pdo->prepare('UPDATE orcamentos SET status = 1 WHERE id_orcamento = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|orcamentos_ativar|UPDATE orcamentos SET status = 1 WHERE id_orcamento = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'desativar') {
    try {
        $stmt = $pdo->prepare('UPDATE orcamentos SET status = 0 WHERE id_orcamento = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|orcamentos_desativar|UPDATE orcamentos SET status = 0 WHERE id_orcamento = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'materiais_load') {

    $c = $pdo->query("SELECT * FROM base_orcamento");
    $l = $c->fetch(PDO::FETCH_ASSOC);

    $sql = $pdo->query("SELECT * FROM produtos WHERE id_produto = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    if ($rowCol['id_produto'] == 1) {
        $valor = 0;
    } else {
        if ($rowCol['moeda'] == 'U$') {
            $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'USD' AND data = '" . date('Y-m-d') . "'");
            $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

            $valorMoeda = $rowCol['valor'] * $linhaMoeda['valor'];
            $valorPrevia = $valorMoeda * $l['coeficiente'];
        } else if ($rowCol['moeda'] == '€') {
            $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'EUR' AND data = '" . date('Y-m-d') . "'");
            $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

            $valorMoeda = $rowCol['valor'] * $linhaMoeda['valor'];
            $valorPrevia = $valorMoeda * $l['coeficiente'];
        } else {
            $valorPrevia = $rowCol['valor'] * $l['coeficiente'];
        }

        if ($valorPrevia < $l['valor']) {
            $valor = $l['valor'];
        } else {
            $valor = $valorPrevia;
        }
    }

    echo $rowCol['id_produto'] . "|"; //0
    echo $rowCol['descricao'] . "|"; //1
    echo number_format($valor, 2, ",", ".") . "|";  //2
    echo $rowCol['unidade'] . "|"; //3
    echo $rowCol['observacao']; //4

}

if ($type == 'materiais_load_edit') {

    $sql = $pdo->query("SELECT id_o_produto, ambiente, id_produto, t2.descricao as material, unid, t1.valor, t2.observacao, t1.descricao as descricao, qtd, metro, acabamento, outros, acrescimo FROM orcamentos_produtos as t1 INNER JOIN produtos as t2 ON t1.material = t2.id_produto WHERE id_o_produto = " . $_POST['id']);
    $rowCol = $sql->fetch(PDO::FETCH_ASSOC);

    echo $rowCol['ambiente'] . "|"; //0
    echo $rowCol['id_produto'] . "|"; //1
    echo $rowCol['material'] . "|"; //2
    echo number_format($rowCol['valor'], 2, ",", ".")  . "|";  //3
    echo $rowCol['unid'] . "|"; //4
    echo $rowCol['observacao'] . "|"; //5
    echo $rowCol['descricao'] . "|"; //6
    echo $rowCol['qtd'] . "|"; //7
    echo str_replace(".", ",", $rowCol['metro']) . "|"; //8
    if ($rowCol['acabamento'] != '0.00') {
        echo number_format($rowCol['acabamento'], 2, ",", ".") . "|"; //9
    } else {
        echo "|"; //9
    }
    if ($rowCol['outros'] != '0.00') {
        echo number_format($rowCol['outros'], 2, ",", ".") . "|"; //10
    } else {
        echo "|"; //10
    }
    if ($rowCol['acrescimo'] != '0') {
        echo $rowCol['acrescimo'] . "|"; //11
    } else {
        echo "|"; //11
    }

    echo "|"; //12

    echo $rowCol['id_o_produto'] . "|"; //13

}

if ($type == 'materiais_view') {

    $id_orcamento = filter_input(INPUT_POST, 'id_orcamento', FILTER_DEFAULT);

    $consultaProd = $pdo->query("SELECT id_o_produto, item, ambiente, material, t1.descricao, unid, qtd, metro, t1.valor, acabamento, outros, acrescimo  FROM orcamentos_produtos as t1 INNER JOIN produtos as t2 ON t1.material = t2.id_produto  WHERE id_orcamento = " . $id_orcamento . " ORDER BY id_o_produto ASC");
    $total = $consultaProd->rowCount();
    if ($total >= 1) {

        while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

            $arrayArea[] = $linhaProd['material'] . "_" . $linhaProd['ambiente'];

            $quantidade = $linhaProd['qtd'];
            $metro = $linhaProd['metro'];

            $valor = $linhaProd['valor'];

            $outros = $linhaProd['outros'];

            if ($linhaProd['acrescimo'] != 0) {
                $mod_acr_dec = $linhaProd['acrescimo'] / 100;
            } else {
                $mod_acr_dec = 0;
            }

            if ($linhaProd['acabamento'] != 0) {
                $Acabamento10 = ($linhaProd['acabamento'] / ($linhaProd['acabamento'] - ($mod_acr_dec * $linhaProd['acabamento']))) * $linhaProd['acabamento'];
            } else {
                $Acabamento10 = 0;
            }
            if ($valor != 0) {
                $Valor10 = ($valor / ($valor - ($mod_acr_dec * $valor))) * $valor;

                $vl_unitario = (($metro * $Valor10) + $Acabamento10) + $outros;
                $vl_total = $quantidade * $vl_unitario;
            } else {
                $vl_unitario = 0;
                $vl_total = 0;
            }

            if ($linhaProd['descricao'] == '') {
                $descricao = '-- Sem Descrição --';
            } else {
                $descricao = nl2br($linhaProd['descricao']);
            }

            $loopProdArray[$linhaProd['material'] . "_" . $linhaProd['ambiente']] .= '<tr>
                    <td class="text-center">' . $linhaProd['item'] . '</td>
                    <td style="width:500px; text-align:center;">' . $descricao . '</td>
                    <td>' .  number_format($linhaProd['qtd'], 2, ",", "") . '</td>
                    <td>' . $linhaProd['unid'] . '</td>
                    <td class="text-nowrap text-right">R$ ' . number_format($vl_unitario, 2, ",", ".") . '</td>
                    <td class="text-nowrap text-right">R$ ' . number_format($vl_total, 2, ",", ".") . '</td>
                    <td >
                        <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon CopiarMaterial" title="Copiar">
                            <i class="la la-file-text-o"></i>
                        </a>
                        <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon DuplicarMaterial" title="Duplicar">
                            <i class="la la-clone"></i>
                        </a>
                        <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon EditMaterial" title="Editar">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon RemoverMaterial" title="Remover" id="' . $linhaProd['id_o_produto'] . '">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>';

            $subtotal[$linhaProd['material'] . "_" . $linhaProd['ambiente']] += $vl_total;

            // $total += $vl_total;
            $loopProd .= '';
        }
    } else {
        $loopProd = '<tr>
                    <th class="text-center" colspan="7">Sem Materiais cadastrados</th>
                </tr>';
    }

    if ($arrayArea !== null) {

        if (count($arrayArea) >= 1) {

            $arrayAreaUniq = array_unique($arrayArea);
            $a = 1;
            foreach ($arrayAreaUniq as $key => $value) {

                $itemComposto = $a++;

                $produtoArea = explode("_", $value);

                $consultaProduto = $pdo->query("SELECT descricao FROM produtos WHERE id_produto = " . $produtoArea[0]);
                $linhaProduto = $consultaProduto->fetch(PDO::FETCH_ASSOC);

                if ($linhaProduto['descricao'] == 'Sem Material') {
                    $descProduto = '';
                } else {
                    $descProduto = " - " . $linhaProduto['descricao'];
                }

                $loopProd .= '<tr style="background-color: #c9f7f5;">
                            <td class="text-center"><b>' . $itemComposto . '</b></td>
                            <td style="width:500px; text-align:center;"><b>' . $produtoArea[1] . '</b>' . $descProduto . '</td>
                            <td class="td-center"></td>
                            <td class="td-center"></td>
                            <td class="td-center"></td>
                            <td class="td-center"></td>
                            <td class="td-center"></td>
                         </tr>
                        ' . $loopProdArray[$value] . '<tr>
                            <td colspan="7" style="height:7px;"></td>
                        </tr>
                        <tr style="background-color:#D9D9D9;">
                            <td class="td-center"></td>
                            <td colspan="4" class="text-right">SUB TOTAL</td>
                            <td class="text-right">&nbsp;&nbsp;R$ ' . number_format($subtotal[$value], 2, ",", ".") . '</td>
                            <td class="td-center"></td>
                        </tr>';
            }
        }
    }

    echo $loopProd;
}
if ($type == 'load_cliente') {

    $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);

    $consulta = $pdo->query("SELECT celular, email FROM clientes WHERE id_cliente = " . $id);
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);

    echo $linha['celular'] . "||" . $linha['email'];
}

if ($type == 'valor_base') {

    $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));

    try {
        $stmt = $pdo->prepare('UPDATE base_orcamento SET valor = "' . $valor . '"');
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|valor_base|UPDATE base_orcamento SET valor = '" . $valor . "' \n\n");
        fclose($fp);
    }
}

if ($type == 'coeficiente_base') {

    $valor = str_replace(",", ".", str_replace(".", "", filter_input(INPUT_POST, 'valor', FILTER_DEFAULT)));

    try {
        $stmt = $pdo->prepare('UPDATE base_orcamento SET coeficiente = "' . $valor . '"');
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|coeficiente_base|UPDATE base_orcamento SET coeficiente = '" . $valor . "' \n\n");
        fclose($fp);
    }
}

/* end::Outro */