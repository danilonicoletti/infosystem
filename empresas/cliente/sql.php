<?
session_start();
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';

$type = $_POST['type'];
$data = date('Y-m-d');

if (isset($_POST['isento'])) {
    $ie = 'Isento';
} else {
    $ie = filter_input(INPUT_POST, "documentos", FILTER_DEFAULT);
}

$columns = array('codigo', 'pessoa',  'nome', 'nomes', 'documento', 'documentos', 'email', 'contato', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cod_municipio', 'municipio', 'uf', 'cep2', 'endereco2', 'numero2', 'complemento2', 'bairro2', 'cod_municipio2', 'municipio2', 'uf2', 'sem', 'id_empresa');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$columns_ = array('codigo', 'pessoa',  'nome', 'nomes', 'documento', 'documentos', 'email', 'contato', 'telefone', 'celular', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cod_municipio', 'municipio', 'uf', 'cep2', 'endereco2', 'numero2', 'complemento2', 'bairro2', 'cod_municipio2', 'municipio2', 'uf2');

$param_update = join(', ', array_map(
    function ($columns_) {
        return "$columns_ = :$columns_";
    },
    $columns_
));

/* Begin::Adicionar */
if ($type == 'cliente_add') {

    try {
        $sql = 'INSERT INTO clientes (' . $column_insert . ') VALUES(' . $param_insert . ')';
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':pessoa' => filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT),
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "nomes", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':documentos' => $ie,
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cod_municipio' => filter_input(INPUT_POST, "ibge", FILTER_DEFAULT),
            ':municipio' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':uf' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':cep2' => filter_input(INPUT_POST, "cep2", FILTER_DEFAULT),
            ':endereco2' => filter_input(INPUT_POST, "endereco2", FILTER_DEFAULT),
            ':numero2' => filter_input(INPUT_POST, "numero2", FILTER_DEFAULT),
            ':complemento2' => filter_input(INPUT_POST, "complemento2", FILTER_DEFAULT),
            ':bairro2' => filter_input(INPUT_POST, "bairro2", FILTER_DEFAULT),
            ':cod_municipio2' => filter_input(INPUT_POST, "ibge2", FILTER_DEFAULT),
            ':municipio2' => filter_input(INPUT_POST, "cidade2", FILTER_DEFAULT),
            ':uf2' => filter_input(INPUT_POST, "estado2", FILTER_DEFAULT),
            ':sem' => 0,
            ':id_empresa' => $company,
        );
        $stmt->execute($parameters);
        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cliente_add|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Adicionar */

/* Begin::Editar */
if ($type == 'cliente_edit') {
    $cliente_error = 0;

    try {
        $sql = 'UPDATE clientes SET ' . $param_update . ' WHERE id_cliente = ' . $_POST['id'];
        $stmt = $pdo->prepare($sql);
        $parameters = array(
            ':codigo' => filter_input(INPUT_POST, "codigo", FILTER_DEFAULT),
            ':pessoa' => filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT),
            ':nome' => filter_input(INPUT_POST, "nome", FILTER_DEFAULT),
            ':nomes' => filter_input(INPUT_POST, "nomes", FILTER_DEFAULT),
            ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
            ':documentos' => $ie,
            ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
            ':contato' => filter_input(INPUT_POST, "contato", FILTER_DEFAULT),
            ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
            ':celular' => filter_input(INPUT_POST, "celular", FILTER_DEFAULT),
            ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
            ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
            ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
            ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
            ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
            ':cod_municipio' => filter_input(INPUT_POST, "ibge", FILTER_DEFAULT),
            ':municipio' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
            ':uf' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
            ':cep2' => filter_input(INPUT_POST, "cep2", FILTER_DEFAULT),
            ':endereco2' => filter_input(INPUT_POST, "endereco2", FILTER_DEFAULT),
            ':numero2' => filter_input(INPUT_POST, "numero2", FILTER_DEFAULT),
            ':complemento2' => filter_input(INPUT_POST, "complemento2", FILTER_DEFAULT),
            ':bairro2' => filter_input(INPUT_POST, "bairro2", FILTER_DEFAULT),
            ':cod_municipio2' => filter_input(INPUT_POST, "ibge2", FILTER_DEFAULT),
            ':municipio2' => filter_input(INPUT_POST, "cidade2", FILTER_DEFAULT),
            ':uf2' => filter_input(INPUT_POST, "estado2", FILTER_DEFAULT),
        );
        $stmt->execute($parameters);

        echo "success||";
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cliente_edit|" . debugPDO($sql, $parameters) . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* End::Editar */

/* Begin::Remover */
if ($type == 'cliente_remove') {

    try {
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id_cliente = :id');
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        echo 'success||';
    } catch (PDOException $e) {
        $number_error = rand();

        $fp = fopen("../../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|cliente_delete|DELETE FROM clientes WHERE id_cliente = " . $_POST['id'] . "\n\n");
        fclose($fp);

        echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
    }
}
/* end::Remover */

/* begin::Outros */
/* end::Outro */