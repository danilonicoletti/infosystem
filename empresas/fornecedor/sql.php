<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('codigo', 'pessoa', 'tipo_fornecedor', 'contato', 'nome', 'nomes', 'documento', 'documentos', 'email', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$columns_ = array('codigo', 'pessoa', 'tipo_fornecedor', 'contato', 'nome', 'nomes', 'documento', 'documentos', 'email', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado');

$param_update = join(', ', array_map(
    function ($columns_) {
        return "$columns_ = :$columns_";
    },
    $columns_
));

/* Begin::Adicionar */
if ($type == 'fornecedor_add') {

    try {
        $sql = 'INSERT INTO fornecedor (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':pessoa' => filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT),
            ':tipo_fornecedor' => filter_input(INPUT_POST, "tipo_fornecedor", FILTER_DEFAULT),
            ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "nomes", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':documentos' => filter_input(INPUT_POST, "documentos", FILTER_DEFAULT),
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cidade' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|fornecedor_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'fornecedor_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE fornecedor SET ' . $param_update . ' WHERE id_fornecedor = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':pessoa' => filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT),
            ':tipo_fornecedor' => filter_input(INPUT_POST, "tipo_fornecedor", FILTER_DEFAULT),
            ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "nomes", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':documentos' => filter_input(INPUT_POST, "documentos", FILTER_DEFAULT),
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cidade' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|fornecedor_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'fornecedor_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM fornecedor WHERE id_fornecedor = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|fornecedor_delete|DELETE FROM fornecedor WHERE id_fornecedor = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
/* end::Outro */