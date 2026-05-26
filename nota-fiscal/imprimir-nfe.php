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

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$xmlAssinado = file_get_contents(__DIR__ . '/assinadas/' . $linha['chave_acesso'] . '.xml');
$xmlProtocolo = file_get_contents(__DIR__ . '/protocoladas/' . $linha['chave_acesso'] . '.xml');

try {
    $xml = Complements::toAuthorize($xmlAssinado, $xmlProtocolo);

    file_put_contents(__DIR__ . '/finalizadas/' . $linha['chave_acesso'] . '.xml', $xml);

    $xmlView = file_get_contents(__DIR__ . '/finalizadas/' . $linha['chave_acesso'] . '.xml');
    $logo = '';

    $danfe = new Danfe($xmlView);
    $danfe->exibirTextoFatura = false;
    $danfe->exibirPIS = false;
    $danfe->exibirIcmsInterestadual = false;
    $danfe->exibirValorTributos = false;
    $danfe->descProdInfoComplemento = false;
    $danfe->exibirNumeroItemPedido = false;
    $danfe->setOcultarUnidadeTributavel(true);
    $danfe->obsContShow(false);
    $danfe->printParameters(
        $orientacao = 'P',
        $papel = 'A4',
        $margSup = 2,
        $margEsq = 2
    );
    $danfe->logoParameters($logo, $logoAlign = 'C', $mode_bw = false);
    $danfe->setDefaultFont($font = 'times');
    $danfe->setDefaultDecimalPlaces(4);
    $danfe->debugMode(false);
    $danfe->creditsIntegratorFooter('InfoSystem - https://www.infoaquila.com.br');

    /*  $this->printParameters( $orientacao = '', $papel = 'A4', $margSup = 2, $margEsq = 2 );  || Caso queira mudar a configuracao padrao de impressao*/
    /*  $this->setOcultarUnidadeTributavel(true);  || Caso queira sempre ocultar a unidade tributável*/
    /*  $danfe->depecNumber('123456789');  || Informe o numero DPEC*/
    /*  $danfe->logoParameters($logo, 'C', false); || Configura a posicao da logo */

    $pdf = $danfe->render($logo);
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
