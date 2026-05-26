<?php

require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';
require __DIR__ . '/../vendor/autoload.php';

use NFePHP\DA\NFe\Daevento;


$xml = file_get_contents(__DIR__ . '/corrigidos/35250851125803000103550010000000981022425128.xml');

$dadosEmitente = [
    'razaosocial' => "FUSION LASTRAS & MARMORES LTDA",
    'logradouro' => mb_strtoupper('Rua Joaquim Lapas Veigas'),
    'numero' => '532',
    'complemento' => '',
    'bairro' => mb_strtoupper('Jardim do Lago'),
    'CEP' => '05550010',
    'municipio' => mb_strtoupper('São Paulo'),
    'UF' => 'SP',
    'telefone' => '1137856600',
    'email' => 'comercial@fusionlastras.com.br'
];

$logo = __DIR__ . '/../assets/media/company/1.png';
try {
    $daevento = new Daevento($xml, $dadosEmitente);
    $daevento->debugMode(true);
    // $daevento->creditsIntegratorFooter('WEBNFe Sistemas - http://www.webenf.com.br');
    $daevento->printParameters('P', 'A4');
    $daevento->logoParameters($logo, 'L', false);
    $pdf = $daevento->render();
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (\Exception $e) {
    echo $e->getMessage();
}
