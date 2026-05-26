<?php
// Incluindo arquivo de conexão/configuração
//require_once('conn.php');

spl_autoload_register(function ($class) {
    require_once(__DIR__ . "/{$class}.class.php");
});

//session_start();

// Instanciando novo objeto da classe Login
$objLogin = new login();

// Recuperando informações enviadas
$email = $_POST['email'];
$senha = md5($_POST['senha']);

// Se conseguir encontrar o usuário e a senha estiver correta
if ($objLogin->logar($email, $senha)) {
    echo 'success||';
} else {
    // Retornando mensagem de erro
    echo 'error||Login ou senha inválidos!';
}
