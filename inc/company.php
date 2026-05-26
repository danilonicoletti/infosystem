<?php

if (isset($_COOKIE['id_is'])) {

    //echo "SELECT * FROM usuarios WHERE id = " . $_COOKIE['id_is'

    $query = $pdo->query("SELECT * FROM usuarios WHERE id = " . $_COOKIE['id_is']);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $query_ = $pdo->query("SELECT * FROM empresa WHERE id = " . $row['empresa']);
    $row_ = $query_->fetch(PDO::FETCH_ASSOC);

    if ($row['avatar'] == '') {
        $imagemCard = 'default.png';
    } else {
        $imagemCard = $row['avatar'];
    }

    $primeiroNome = $row['nome'] . ' ' . $row['sobrenome'];
    //$segundoNome = $variavelC . "/" . $_COOKIE['id_is'];
    $segundoNome = $row['nome'];
    $primeiraLetra = $row['nome'];
    //$departamento = 'WebDesigner';

    $departamento = $row['departamento'];

    $company = $row['empresa'];
} else {

    echo "<script>location.href='" . $url . "expirado';</script>";
}
