<?php

require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';
require __DIR__ . '/../vendor/autoload.php';


use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use NFePHP\DA\NFe\Danfe;

$id = filter_input(INPUT_POST, "id", FILTER_DEFAULT);

if (!$id) {
    die('error||ID inválido');
}

$config = [
    "atualizacao" => date('Y-m-d H:i:s'),
    "tpAmb"       => 1,
    "razaosocial" => "FUSION LASTRAS & MARMORES LTDA",
    "cnpj"        => "51125803000103",
    "ie"          => "121813312110",
    "siglaUF"     => "SP",
    "schemes"     => "PL_009_V4",
    "versao"      => "4.00"
];

$configJson = json_encode($config);

try {

    /*
    |--------------------------------------------------------------------------
    | CERTIFICADO
    |--------------------------------------------------------------------------
    */

    $certificadoDigital = file_get_contents('../vendor/certificado_novo.pfx');

    $tools = new Tools(
        $configJson,
        Certificate::readPfx($certificadoDigital, '15101968')
    );

    /*
    |--------------------------------------------------------------------------
    | LOTE
    |--------------------------------------------------------------------------
    */

    $c = $pdo->query("SELECT num FROM lote");
    $l = $c->fetch(PDO::FETCH_ASSOC);

    $idLote = str_pad($l['num'], 15, '0', STR_PAD_LEFT);

    /*
    |--------------------------------------------------------------------------
    | NF-E
    |--------------------------------------------------------------------------
    */

    $consulta = $pdo->query("
        SELECT * 
        FROM nfe 
        WHERE id_nfe = ".$id."
    ");

    $linha = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        die('error||NF-e não encontrada');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFICA STATUS
    |--------------------------------------------------------------------------
    */

    if ($linha['status'] == 2) {
        die('success||NF-e já autorizada');
    }

    /*
    |--------------------------------------------------------------------------
    | XML ASSINADO
    |--------------------------------------------------------------------------
    */

    $caminhoXml = __DIR__.'/assinadas/'.$linha['chave_acesso'].'.xml';

    if (!file_exists($caminhoXml)) {
        die('error||XML assinado não encontrado');
    }

    $xmlAssinado = file_get_contents($caminhoXml);

    /*
    |--------------------------------------------------------------------------
    | ENVIO SÍNCRONO
    |--------------------------------------------------------------------------
    */

    $resp = $tools->sefazEnviaLote(
        [$xmlAssinado],
        $idLote,
        1
    );

    /*
    |--------------------------------------------------------------------------
    | PADRONIZA RESPOSTA
    |--------------------------------------------------------------------------
    */

    $st  = new Standardize();
    $std = $st->toStd($resp);

    /*
    |--------------------------------------------------------------------------
    | LOTE PROCESSADO
    |--------------------------------------------------------------------------
    */

    if ($std->cStat == 104) {

        $prot = $std->protNFe->infProt;

        /*
        |--------------------------------------------------------------------------
        | NF-E AUTORIZADA
        |--------------------------------------------------------------------------
        */

        if ($prot->cStat == 100) {

            $xmlProtocolado = Complements::toAuthorize(
                $xmlAssinado,
                $resp
            );

            file_put_contents(
                __DIR__.'/protocoladas/'.$linha['chave_acesso'].'.xml',
                $xmlProtocolado
            );

            $data_saida = new DateTime();

            $stmt = $pdo->prepare("
                UPDATE nfe 
                SET protocolo = :protocolo,
                    status = 2,
                    data_saida = :data_saida
                WHERE id_nfe = :id
            ");

            $stmt->execute([
                ':protocolo' => $prot->nProt,
                ':data_saida' => $data_saida->format('Y-m-d\TH:i:s'),
                ':id' => $id
            ]);

            /*
            |--------------------------------------------------------------------------
            | ATUALIZA PRÓXIMO LOTE
            |--------------------------------------------------------------------------
            */

            $Lt = $l['num'] + 1;

            $stmt_ = $pdo->prepare("
                UPDATE lote 
                SET num = :num
            ");

            $stmt_->execute([
                ':num' => $Lt
            ]);

            echo 'success||NF-e autorizada';

        }

        /*
        |--------------------------------------------------------------------------
        | DUPLICIDADE
        |--------------------------------------------------------------------------
        */

        else if ($prot->cStat == 539) {

            $stmt = $pdo->prepare("
                UPDATE nfe 
                SET status = 3
                WHERE id_nfe = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            echo 'error||'.$prot->xMotivo;

        }

        /*
        |--------------------------------------------------------------------------
        | OUTRAS REJEIÇÕES
        |--------------------------------------------------------------------------
        */

        else {

            $stmt = $pdo->prepare("
                UPDATE nfe 
                SET chave_acesso = '',
                    status = 0
                WHERE id_nfe = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            @unlink(
                __DIR__.'/assinadas/'.$linha['chave_acesso'].'.xml'
            );

            echo 'error||'.$prot->xMotivo;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | ERRO NO PROCESSAMENTO DO LOTE
    |--------------------------------------------------------------------------
    */

    else {

        echo 'error||'.$std->xMotivo;
    }

} catch (\Exception $e) {

    echo 'error||'.$e->getMessage();
}