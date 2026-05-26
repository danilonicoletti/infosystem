<?
session_start();
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';

$type = $_POST['type'];
$data = date('Y-m-d');

$columns = array('fantasia', 'razao', 'pessoa', 'documento', 'email', 'responsavel', 'telefone', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'cod_municipio', 'estado', 'logo', 'crt', 'institucional', 'status');
$column_insert = join(', ', $columns);

$param_insert = join(', ', array_map(function ($columns) {
    return ":$columns";
}, $columns));

$columns_user = array('nome', 'sobrenome', 'email', 'avatar', 'senha', 'ativo', 'departamento', 'empresa', 'data');
$column_insert_user = join(', ', $columns_user);

$param_insert_user = join(', ', array_map(function ($columns_user) {
    return ":$columns_user";
}, $columns_user));


/* Begin::Adicionar */
if ($type == 'empresa_add') {
    $empresa = 0;

    $fileUpload = $_FILES['logotipo'];

    $allowedTypes = [
        "image/jpg",
        "image/jpeg",
        "image/png",
    ];

    $newFilename = time() . $fileUpload['name'];

    if (!empty($fileUpload['name'])) {

        if (in_array($fileUpload['type'], $allowedTypes)) {

            if (filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT) == "juridica") {

                $responsavel = filter_input(INPUT_POST, "nome_responsavel", FILTER_DEFAULT) . ' ' . filter_input(INPUT_POST, "sobrenome_responsavel", FILTER_DEFAULT);
                $nome = filter_input(INPUT_POST, "nome_responsavel", FILTER_DEFAULT);
                $sobrenome = filter_input(INPUT_POST, "sobrenome_responsavel", FILTER_DEFAULT);
            } else {
                $responsavel = filter_input(INPUT_POST, "fantasia", FILTER_DEFAULT) . ' ' . filter_input(INPUT_POST, "social", FILTER_DEFAULT);
                $nome = filter_input(INPUT_POST, "fantasia", FILTER_DEFAULT);
                $sobrenome = filter_input(INPUT_POST, "social", FILTER_DEFAULT);
            }

            try {
                $sql = 'INSERT INTO empresa (' . $column_insert . ') VALUES(' . $param_insert . ')';
                $stmt = $pdo->prepare($sql);
                $parameters = array(
                    ':fantasia' => filter_input(INPUT_POST, "fantasia", FILTER_DEFAULT),
                    ':razao' => filter_input(INPUT_POST, "social", FILTER_DEFAULT),
                    ':pessoa' => filter_input(INPUT_POST, "pessoa", FILTER_DEFAULT),
                    ':documento' => filter_input(INPUT_POST, "documento", FILTER_DEFAULT),
                    ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
                    ':responsavel' => $responsavel,
                    ':telefone' => filter_input(INPUT_POST, "telefone", FILTER_DEFAULT),
                    ':cep' => filter_input(INPUT_POST, "cep", FILTER_DEFAULT),
                    ':endereco' => filter_input(INPUT_POST, "endereco", FILTER_DEFAULT),
                    ':numero' => filter_input(INPUT_POST, "numero", FILTER_DEFAULT),
                    ':complemento' => filter_input(INPUT_POST, "complemento", FILTER_DEFAULT),
                    ':bairro' => filter_input(INPUT_POST, "bairro", FILTER_DEFAULT),
                    ':cidade' => filter_input(INPUT_POST, "cidade", FILTER_DEFAULT),
                    ':cod_municipio' => filter_input(INPUT_POST, "ibge", FILTER_DEFAULT),
                    ':estado' => filter_input(INPUT_POST, "estado", FILTER_DEFAULT),
                    ':logo' => $newFilename,
                    ':crt' => filter_input(INPUT_POST, "crt", FILTER_DEFAULT),
                    ':institucional' => filter_input(INPUT_POST, "institucional", FILTER_DEFAULT),
                    ':status' => '1',
                );
                $stmt->execute($parameters);

                $id_company = $pdo->lastInsertId();
            } catch (PDOException $e) {
                $empresa++;
            }

            try {
                $sql_user = 'INSERT INTO usuarios (' . $column_insert_user . ') VALUES(' . $param_insert_user . ')';
                $stmt_user = $pdo->prepare($sql_user);
                $parameters_user = array(
                    ':nome' => $nome,
                    ':sobrenome' =>  $sobrenome,
                    ':email' => filter_input(INPUT_POST, "email", FILTER_DEFAULT),
                    ':avatar' => $newFilename,
                    ':senha' => md5(filter_input(INPUT_POST, "senha", FILTER_DEFAULT)),
                    ':ativo' => "1",
                    ':departamento' => "1",
                    ':empresa' =>  $id_company,
                    ':data' => date('Y-m-d'),
                );
                $stmt_user->execute($parameters_user);
            } catch (PDOException $e) {
                $empresa++;
            }

            if (move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/../assets/media/company/{$newFilename}")) {
                if ($empresa == 0) {
                    echo 'success||';
                } else {
                    $number_error = rand();

                    $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
                    $escreve = fwrite($fp, $number_error . "|empresa_add|" . debugPDO($sql, $parameters) . "\n" . debugPDO($sql_user, $parameters_user) . "\n\n");
                    fclose($fp);

                    echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
                }
            } else {
                $number_error = rand();

                $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
                $escreve = fwrite($fp, $number_error . "|empresa_add_image|Não é possível mover o arquivo, na pasta " . __DIR__ . "/../assets/media/company/{$newFilename}!!!\n\n");
                fclose($fp);

                echo "error||Algo deu errado, informe o numero <b>" . $number_error . "</b> para o Administrador do sistema!";
            }
        } else {
            echo "error||Tipo de logotipo não permitido!";
        }
    } else {
        $number_error = rand();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|empresa_add_image|Imagem não encontrada!!!\n\n");
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

/* end::Outro */