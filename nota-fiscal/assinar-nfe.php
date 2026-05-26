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

/* Gravando a Nfe no banco de Dados */

// $id = $_GET['id'];
$id = filter_input(INPUT_POST, "id", FILTER_DEFAULT);

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

$nfe = new Make();
$protocolo = new Complements();

$std = new stdClass();
$stdC = new stdClass();

$std->versao = '4.00'; //versão do layout (string)
$std->Id = ''; //se o Id de 44 dígitos não for passado será gerado automaticamente
$std->pk_nItem = null; //deixe essa variável sempre como NULL

$nfe->taginfNFe($std);

$std->cUF = 35;
$std->cNF = null;

// começa com 5 "Venda" e qtd 6 "Venda fora do estado" CFOP Descrição.....
$std->natOp = $linhaCFOP['descricao'];

$std->mod = 55;
$std->serie = 1;
$std->nNF = $linha['nota_fiscal']; // Numero da Nota Fiscal
$std->dhEmi = date('Y-m-d\TH:i:sP');
$std->dhSaiEnt = date('Y-m-d\TH:i:sP');
if ($linha['es'] == 'S') {
    $std->tpNF = 1; // 0 - entrada | 1 - saída
} else {
    $std->tpNF = 0; // 0 - entrada | 1 - saída
}

// 1 – operação interna | 2 – operação interestadual | 3 – operação com exterior
if ($linhaCFOP['cfop'][0] == 2 || $linhaCFOP['cfop'][0] == 6) {
    $std->idDest = 2;
} else {
    $std->idDest = 1;
}

$std->cMunFG = 3550308; // Código do Município
$std->tpImp = 1; // 1 - Retrato | 2 - Paisagem 
$std->tpEmis = 1; // Tipo de emissão da NFe ( 1 - Normal | 2 - Contingencia )
$std->cDV = null; // Digito verificador da chave de acesso 
$std->tpAmb = 1; // 1 - Produção  2 - Homologação
$std->finNFe = 1; // 1- NF-e normal | 2-NF-e complementar | 3 – NF-e de ajuste
$std->indFinal = 1; // 0 Consumidor Normal | 1 - Consumidor final 
$std->indPres = 0; // Se ouve presença no momento da compra
$std->procEmi = 0; //  emissão de NF-e com aplicativo do contribuinte
$std->verProc = '1.0';

$nfe->tagide($std);

$std->xNome = mb_strtoupper($row_['razao']);
$std->xFant = mb_strtoupper($row_['fantasia']);
$std->IE = $row_['documentos'];
$std->CRT = $row_['crt']; // Tipo de Regime tributário (Simples)
$std->CNPJ = $row_['documento']; //indicar apenas um CNPJ ou CPF

$nfe->tagemit($std);

$std->xLgr = mb_strtoupper($row_['endereco']);
$std->nro = $row_['numero'];
$std->xCpl = mb_strtoupper($row_['complemento']);
$std->xBairro = mb_strtoupper($row_['bairro']);
$std->cMun = limpaCar($row_['cod_municipio']);;
$std->xMun = mb_strtoupper($row_['cidade']);
$std->UF = mb_strtoupper($row_['estado']);
$std->CEP = limpaCar($row_['cep']);
$std->cPais = "1058";
$std->xPais = "BRASIL";
$std->fone = limpaCar($row_['telefone']);

$nfe->tagenderEmit($std);

if ($linhaCliente['pessoa'] == 'fisica') {
    $stdC->xNome = mb_strtoupper($linhaCliente['nome'] . " " . $linhaCliente['nomes']);
    if ($linhaCliente['documentos'] == 'Isento') {
        $stdC->indIEDest = "9";
    } else {
        $stdC->indIEDest = "2";
    }
    $stdC->CPF = limpaCar($linhaCliente['documento']);
} else {

    $stdC->xNome = mb_strtoupper($linhaCliente['nomes']);

    if ($linhaCliente['documentos'] == 'Isento') {
        $stdC->indIEDest = "9";
        $stdC->IE = "";
    } else {
        $stdC->indIEDest = "1";
        $stdC->IE = limpaCar($linhaCliente['documentos']);
    }

    $stdC->CNPJ = limpaCar($linhaCliente['documento']);
}

$stdC->email = "";

$nfe->tagdest($stdC);

$std->xLgr = mb_strtoupper($linhaCliente['endereco']);
$std->nro = $linhaCliente['numero'];
$std->xCpl = mb_strtoupper($linhaCliente['complemento']);
$std->xBairro = mb_strtoupper($linhaCliente['bairro']);
$std->cMun = $linhaCliente['cod_municipio'];
$std->xMun = mb_strtoupper($linhaCliente['municipio']);
$std->UF = mb_strtoupper($linhaCliente['uf']);
$std->CEP = limpaCar($linhaCliente['cep']);
$std->cPais = "1058";
$std->xPais = "BRASIL";
$std->fone = limpaCar($linhaCliente['telefone']);

$nfe->tagenderDest($std);

$i = 1;
$consultaProd = $pdo->query("SELECT id_produto, t1.codigo, t1.descricao as descricao, unid, t2.ncm as ncm, cstcson, qtd, valor, orig, p_icms, p_ipi, enq_ipi, vl_ipi, id_nfe FROM nfe_produtos AS t1 INNER JOIN ncm AS t2 ON t1.id_ncm = t2.id_ncm WHERE id_nfe = " . $id);
$total = $consultaProd->rowCount();

if ($total == 0) {

    $std->vTotTrib = 0;

    $nfe->tagimposto($std);

    $vl_total = 0;
    $total_ipi = 0;
    $total_icms = 0;
    $total_tribs = 0;

    $total_total = 0;
} else {
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        /* Produtos */

        $quantidade = $linhaProd['qtd'];
        $valor = $linhaProd['valor'];

        $vl_total = $valor * $quantidade;
        $vl_total_ipi = $vl_total + $linhaProd['vl_ipi'];

        if ($linhaProd['p_icms'] != 0) {
            $vl_total_icms = ($vl_total / 100) * $linhaProd['p_icms'];
            $valida_icms = true;
        } else {
            $vl_total_icms = 0;
            $valida_icms = false;
        }

        $std->item = $i; //item da NFe
        // $std->cProd = str_pad($linhaProd['id_produto'], 3, '0', STR_PAD_LEFT);
        $std->cProd = $linhaProd['codigo'];
        $std->cEAN = "SEM GTIN";
        $std->xProd = $linhaProd['descricao'];
        $std->NCM = $linhaProd['ncm'];
        // $std->cBenef = "2804300";
        $std->CFOP = $linhaCFOP['cfop'];
        // $std->CSON = $linhaProd['cstcson'];
        $std->uCom = $linhaProd['unid'];
        $std->qCom = $linhaProd['qtd'];
        $std->vUnCom = $linhaProd['valor'];
        $std->vProd = $vl_total;
        $std->cEANTrib = "SEM GTIN";
        $std->uTrib = $linhaProd['unid'];
        $std->qTrib = $linhaProd['qtd'];
        $std->vUnTrib = $linhaProd['valor'];
        $std->indTot = "1"; // 1 - Vai compor o valor total da Nota

        /* Fim Produtos */

        $nfe->tagprod($std);

        $std->vTotTrib = $total_tribs;

        $nfe->tagimposto($std);

        $std->orig = $linhaProd['orig']; // 0 - Nacional // 1- Importado // 2 - Adq. Mercado Interno
        if ($row_['crt'] == 1) {
            $std->CST = $linhaCFOP['icms'];
            $std->CSOSN = $linhaProd['cstcson'];
        } else {
            $std->CST = $linhaCFOP['icms'];
            // $std->CSOSN = $linhaProd['cstcson'];
        }
        $std->modBC = 0;

        if ($linhaProd['p_icms'] != 0) {
            $std->vBC = $vl_total; // Se o ICMS for zerado não tem BC
        } else {
            $std->vBC = 0; // Se o ICMS for zerado não tem BC
        }

        $std->pICMS = $linhaProd['p_icms'];
        $std->vICMS = $vl_total_icms;

        if ($linhaProd['cstcson'] == '101') {
            $std->pCredSN = $linhaProd['p_icms'];
            $std->vCredICMSSN = $vl_total_icms;
        }

        if ($row_['crt'] == 1) {
            $nfe->tagICMSSN($std);
        } else {
            $nfe->tagICMS($std);
        }

        if ($linhaProd['enq_ipi'] != "" && $linhaProd['enq_ipi'] != "0") {
            $std->cEnq = $linhaProd['enq_ipi'];
        } else {
            $std->cEnq = "999";
        }
        if ($linhaProd['vl_ipi'] != 0) {
            $std->vIPI = $linhaProd['vl_ipi'];

            if ($vl_total != 0) {
                $std->vBC = $vl_total;
            } else {
                $std->vBC = 0;
            }
        } else {
            $std->vIPI = 0;
            $std->vBC = 0;
        }

        if ($linhaProd['p_ipi'] != 0) {
            $std->pIPI =  $linhaProd['p_ipi'];
        } else {
            $std->pIPI = 0;
        }

        $std->CST = $linhaCFOP['ipi'];

        $nfe->tagIPI($std);

        $std->CST = $linhaCFOP['cofins'];
        $std->vBC = 0;
        $std->pPIS = 0;
        $std->vPIS = 0;
        $std->qBCProd = 0;
        $std->vAliqProd = 0;

        $nfe->tagPIS($std);

        $std->CST = $linhaCFOP['cofins'];
        $std->vBC = 0;
        $std->pCOFINS = 0;
        $std->vCOFINS = 0;
        $std->qBCProd = 0;
        $std->vAliqProd = 0;

        $nfe->tagCOFINS($std);

        $total_ipi += $linhaProd['vl_ipi'];
        $total_icms += $vl_total_icms;
        $total_total += $vl_total;

        $i++;
    }
}
$total_tribs = $total_ipi + $total_icms;
$tt = $total_total + $total_ipi;

if ($valida_icms) {
    $std->vBC = $vl_total;
    $std->vICMS = $total_icms;
} else {
    $std->vBC = 0;
    $std->vICMS = 0;
}

$std->vICMSDeson = 0;
$std->vBCST = 0;
$std->vST = 0;
$std->vProd = 0;
$std->vFrete = 0;
$std->vSeg = 0;
$std->vDesc = 0;
$std->vII = 0;
$std->vIPI = $total_ipi;
$std->vPIS = 0;
$std->vCOFINS = 0;
$std->vOutro = 0;
$std->vNF = $tt;
$std->vIPIDevol = 0;
$std->vTotTrib = $total_tribs;

$nfe->tagICMSTot($std);

$std->modFrete = $linha['frete']; // Tres modalidades de frete... 

$nfe->tagtransp($std);

$std->nFat = $linha['id_nfe'];
$std->vOrig = $tt;
$std->vDesc = null;
$std->vLiq = $tt;

$nfe->tagfat($std);

$std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

$nfe->tagpag($std);

$std->tPag = '15';
$std->vPag = $tt; //Obs: deve ser informado o valor pago pelo cliente
$std->CNPJ = '51125803000103';
$std->tBand = '01';
$std->cAut = '3333333';
$std->tpIntegra = 1; //incluso na NT 2015/002
$std->indPag = '0'; //0= Pagamento à Vista 1= Pagamento à Prazo

$nfe->tagdetPag($std);

$std->infAdFisco = '';
$std->infCpl = $linha['infcpl'];

$nfe->taginfAdic($std);

if (count($nfe->dom->errors) == 0) {
    $xml = $nfe->getXML();

    // file_put_contents('notas/valida.xml', $xml);
    // die();
    // unlink('assinadas/' . $chave . '.xml');

    try {
        $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, '15101968'));

        $xmlAssinado = $tools->signNFe($xml); // O conteúdo do XML assinado fica armazenado na variável $xmlAssinado

        file_put_contents('assinadas/' . $nfe->getChave() . '.xml', $xmlAssinado);

        $stmt = $pdo->prepare("UPDATE nfe SET chave_acesso = '" . $nfe->getChave() . "',status = 1 WHERE id_nfe = " . $id);
        $stmt->execute();

        echo "success";
    } catch (\Exception $e) {

        $number_error = rand();

        echo "error||Ocorreu um erro durante o processamento :" . $e->getMessage();

        $fp = fopen("../report/error_log_" . date('dmY') . ".txt", "a");
        $escreve = fwrite($fp, $number_error . "|chave_insert|UPDATE nfe SET chave_acesso = '" . $nfe->getChave() . "',status = 1 WHERE id_nfe = " . $id . "\n\n");
        fclose($fp);
    }
} else {
    foreach ($nfe->dom->errors as $value) {
        $errorsP .= '<small>' . $value . '</small><br><br>';
    }

    $number_error = rand();

    echo "error||" . $errorsP;
}

/*

$std->tPag = '90'; // Sem Pagamento
$std->vPag = 0.00;
$nfe->tagpag($std);

$nfe->tagide($std);

$ref = new stdClass();
$ref->refNFe = '3525075929858800015455002000066705183242683-4'; // chave da NF original
$nfe->tagrefNFe($ref);


$std->infAdFisco = 'Mercadoria sendo devolvida Ref. a nota ' . $linha['nota_fiscal'] . ' e a chave de acesso 35250759298588000154550020000667051832426834. ';


$std->finNFe = 4; // 1- NF-e normal | 2-NF-e complementar | 3 – NF-e de ajuste

*/
