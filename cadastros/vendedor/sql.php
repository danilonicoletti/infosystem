<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('nome', 'documento', 'email', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'funcao', 'p_comissao', 'status', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$columns_ = array('nome', 'documento', 'email', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'funcao', 'p_comissao');

$param_update = join(', ', array_map(
    function ($columns_) {
        return "$columns_ = :$columns_";
    },
    $columns_
));

/* Begin::Adicionar */
if ($type == 'vendedor_add') {
    $p_comissao = str_replace(",", ".", substr(filter_input(INPUT_POST, "p_comissao", FILTER_DEFAULT), 0, -1));
    try {
        $sql = 'INSERT INTO vendedor (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cidade' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':funcao' => filter_input(INPUT_POST, "funcao", FILTER_DEFAULT),
            ':p_comissao' => $p_comissao,
            ':status' => "1",
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'vendedor_edit') {
    $cliente_error = 0;

    $p_comissao = str_replace(",", ".", substr(filter_input(INPUT_POST, "p_comissao", FILTER_DEFAULT), 0, -1));
    try {
        $sql = 'UPDATE vendedor SET ' . $param_update . ' WHERE id_vendedor = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cidade' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':funcao' => filter_input(INPUT_POST, "funcao", FILTER_DEFAULT),
            ':p_comissao' => $p_comissao,
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'vendedor_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM vendedor WHERE id_vendedor = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_delete|DELETE FROM vendedor WHERE id_vendedor = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
if ($type == 'ativar') {
    try {
        $stmt = $pdo->prepare('UPDATE vendedor SET status = 1 WHERE id_vendedor = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_ativar|UPDATE vendedor SET status = 1 WHERE id_vendedor = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}

if ($type == 'desativar') {
    try {
        $stmt = $pdo->prepare('UPDATE vendedor SET status = 0 WHERE id_vendedor = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|vendedor_desativar|UPDATE vendedor SET status = 0 WHERE id_vendedor = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Outro */