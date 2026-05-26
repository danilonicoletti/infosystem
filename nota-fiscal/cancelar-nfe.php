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
$certificadoDigital = file_get_contents('../vendor/certificado_novo.pfx');

$id = filter_input(INPUT_POST, "id", FILTER_DEFAULT);

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

try {
    $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, '15101968'));
    $tools->model('55');

    $chave = $linha['chave_acesso'];
    $xJust = 'Erro de digitação nos dados dos produtos';
    $nProt = $linha['protocolo'];
    $response = $tools->sefazCancela($chave, $xJust, $nProt);

    //você pode padronizar os dados de retorno através da classe abaixo
    //de forma a facilitar a extração dos dados do XML
    //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
    //      quando houver a necessidade de protocolos
    $stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML
    $std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML
    // $arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML
    // $json = $stdCl->toJson();

    //verifique se o evento foi processado
    if ($std->cStat != 128) {
        //houve alguma falha e o evento não foi processado
        //TRATAR

        echo 'error||' . $std->xMotivo;
    } else {
        $cStat = $std->retEvento->infEvento->cStat;
        if ($cStat == '101' || $cStat == '135' || $cStat == '155') {
            //SUCESSO PROTOCOLAR A SOLICITAÇÃO ANTES DE GUARDAR
            $xml = Complements::toAuthorize($tools->lastRequest, $response);
            //grave o XML protocolado 

            file_put_contents('canceladas/' . $linha['chave_acesso'] . '.xml', $xml);

            echo 'success||' . $std->xMotivo;

            $stmt = $pdo->prepare("UPDATE nfe SET status = '3' WHERE id_nfe = " . $id);
            $stmt->execute();
        } else {
            //houve alguma falha no evento 
            //TRATAR

            echo 'error||' . $std->xMotivo;
        }
    }
} catch (\Exception $e) {
    echo 'error||' . $e->getMessage();
}
