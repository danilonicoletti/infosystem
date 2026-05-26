<?php

// Verificar se o arquivo existe e o módulo está acessível
$cnfPath = '/home/polomarm/openssl_legacy.cnf';
$modulePath = '/usr/lib64/ossl-modules';

// Confirmar que o módulo legacy existe
var_dump(file_exists($modulePath . '/legacy.so'));

// Definir as variáveis de ambiente ANTES de qualquer operação OpenSSL
putenv("OPENSSL_CONF={$cnfPath}");
putenv("OPENSSL_MODULES={$modulePath}");


echo getenv('OPENSSL_CONF') . '<br>';
echo getenv('OPENSSL_MODULES') . '<br>';

$pfx = file_get_contents('vendor/24102487.pfx');

var_dump(
    openssl_pkcs12_read($pfx, $certs, '15101968')
);

while ($e = openssl_error_string()) {
    echo $e . '<br>';
}

