<?php

require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';
require __DIR__ . '/../vendor/autoload.php';

//declare(strict_types=1);

use NFePHP\NFe\Make;
use NFePHP\NFe\Complements;
use NFePHP\DA\NFe\Danfe;

// $id = anti_injection($_GET['id']);
$id = 11;

$config = [
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => 1,
    "razaosocial" => "FUSION LASTRAS & MARMORES LTDA",
    "cnpj" => "51125803000103",
    "ie" => '121813312110',
    "siglaUF" => "SP",
    "schemes" => "PL_009_V4",
    "versao" => '4.00',
];

$configJson = json_encode($config);
$certificadoDigital = file_get_contents('../vendor/2000349254.pfx');

$c = $pdo->query("SELECT num FROM lote ");
$l = $c->fetch(PDO::FETCH_ASSOC);

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, '15101968'));

$xmlAssinado = file_get_contents(__DIR__ . '/assinadas/' . $linha['chave_acesso'] . '.xml');

$error = 0;

if ($linha['recibo'] != "") {
    $recibo = $linha['recibo'];
} else {
    try {
        $idLote = str_pad($l['num'], 15, '0', STR_PAD_LEFT);
        $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);

        print_r($resp);
    } catch (\Exception $e) {
        print_r($e->getMessage());
    }
}


$Lt = $l['num'] + 1;
$stmt_ = $pdo->prepare("UPDATE lote SET num = " . $Lt);
$stmt_->execute();
