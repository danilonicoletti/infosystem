<?php

require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';
require __DIR__ . '/../vendor/autoload.php';

//declare(strict_types=1);

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

/* Gravando a Nfe no banco de Dados */

// $id = $_GET['id'];
$id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);

$ev = $pdo->query("SELECT * FROM evento");
$l = $ev->fetch(PDO::FETCH_ASSOC);

$nSeqEvento = $l['num'] + 1;

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['destinatario']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);

$consultaCFOP = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $linha['id_cfop']);
$linhaCFOP = $consultaCFOP->fetch(PDO::FETCH_ASSOC);

$config = [
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => 1, // 1 - Produção  2 - Homologação
    "razaosocial" => "FUSION LASTRAS & MARMORES LTDA",
    "cnpj" => "51125803000103", // PRECISA SER VÁLIDO
    "ie" => '121813312110', // PRECISA SER VÁLIDO
    "siglaUF" => "SP",
    "schemes" => "PL_009_V4",
    "versao" => '4.00',
];

$configJson = json_encode($config);
$certificadoDigital = file_get_contents('../vendor/certificado_novo.pfx');

$tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, '15101968'));
$tools->model('55');

$chave = $linha['chave_acesso'];
// $xCorrecao = filter_input(INPUT_POST, 'correcao', FILTER_DEFAULT);
$xCorrecao = filter_input(INPUT_POST, 'correcao', FILTER_DEFAULT);

$response = $tools->sefazCCe($chave, $xCorrecao, $nSeqEvento);

$stdCl = new Standardize($response);

$std = $stdCl->toStd();

if ($std->cStat != 128) {
    //houve alguma falha e o evento não foi processado
    //TRATAR
    echo 'error||' . $std->xMotivo;
} else {
    $cStat = $std->retEvento->infEvento->cStat;
    if ($cStat == '135' || $cStat == '136') {
        //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
        $xml = Complements::toAuthorize($tools->lastRequest, $response);

        file_put_contents('corrigidos/' . $chave . '.xml', $xml);

        echo 'success||' . $cStat->nProt;

        $stmt_ = $pdo->prepare("UPDATE evento SET num = '" . $nSeqEvento . "'");
        $stmt_->execute();

        //grave o XML protocolado 
    } else {
        //houve alguma falha no evento 
        //TRATAR
        echo 'error||' . $std->xMotivo;
    }
}
