<?php

class login
{

    private $campoID, $campoEmail, $campoSenha;

    function  __construct($campoID = 'id', $campoEmail = 'email', $campoSenha = 'senha')
    {

        // Iniciando sessão
        session_start();

        // Definindo atributos

        $this->campoID = $campoID;
        $this->campoEmail = $campoEmail;
        $this->campoSenha = $campoSenha;
    }

    // ------------------------------------------------------------------------


    function logar($email, $senha)
    {

        include 'conn.php';
        // Verifica se o usuário existe
        $query = mysqli_query($conn, "SELECT {$this->campoID}, {$this->campoEmail}, {$this->campoSenha} FROM usuarios WHERE {$this->campoEmail} = '{$email}' AND {$this->campoSenha} = '{$senha}'");
        $total = mysqli_num_rows($query);

        // Se encontrado um usuário
        if ($total > 0) {
            // Instanciando usuário
            $usuario = mysqli_fetch_object($query);

            setcookie($this->campoID . '_is', $usuario->{$this->campoID}, time() + (60 * 240), '/');
            setcookie($this->campoEmail, $usuario->{$this->campoEmail}, time() + (60 * 240), '/');
            setcookie('status', 'logado', time() + (60 * 240), '/');

            $t = time() + (60 * 240);
            setcookie('timer', $t, time() + (60 * 240), '/');

            return true;
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /* Finaliza a sessão do usuário */
    function logout($redireciona = null)
    {

        include 'conn.php';

        setcookie($this->campoID . '_is', '', time() - (60 * 240), '/');
        setcookie($this->campoEmail, '', time() - (60 * 240), '/');
        setcookie('status', 'logado', time() - (60 * 240), '/');
        setcookie('timer', '', time() - (60 * 240), '/');

        session_destroy();

        // Se informado redirecionamento
        if ($redireciona !== null)
            header("Location: {$redireciona}");
    }
}
