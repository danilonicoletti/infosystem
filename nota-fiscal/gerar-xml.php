<?php

require __DIR__ . '/../../inc/config.php';
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/../../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';
require __DIR__ . '/../../vendor/autoload.php';

//declare(strict_types=1);

use NFePHP\NFe\Make;

/* Gravando a Nfe no banco de Dados */

$id = anti_injection($_GET['id']);

$consultaOrc = $pdo->query("SELECT * FROM nfe WHERE id_orcamento = " . $id);
$linhaOrc = $consultaOrc->fetch(PDO::FETCH_ASSOC);

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['cliente']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);
/*
$config = [
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => 2, // 1 - Produção  2 - Homologação
    "razaosocial" => "RAZAO SOCIAL DO EMISSOR",
    "cnpj" => "99999999999999", // PRECISA SER VÁLIDO
    "ie" => '999999999999', // PRECISA SER VÁLIDO
    "siglaUF" => "SP",
    "schemes" => "PL_009_V4",
    "versao" => '4.00',
    "tokenIBPT" => "AAAAAAA",
    "CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
    "CSCid" => "000002"
];

$json = json_encode($config);
*/

$nfe = new Make();

$std = new stdClass();
$std->versao = '4.00'; //versão do layout (string)
$std->Id = ''; //se o Id de 44 dígitos não for passado será gerado automaticamente
$std->pk_nItem = null; //deixe essa variável sempre como NULL

$nfe->taginfNFe($std);

$std->cUF = 35;
$std->cNF = null;
$std->natOp = 'VENDAS DE MERCADORIAS';

$std->mod = 55;
$std->serie = 1;
$std->nNF = $nota_fiscal; // Numero da Nota Fiscal
$std->dhEmi = date('Y-m-d\TH:i:sP');
$std->dhSaiEnt = "";
$std->tpNF = 1; // 0 - entrada | 1 - saída
$std->idDest = 1;  // 1 – operação interna | 2 – operação interestadual | 3 – operação com exterior
$std->cMunFG = 3550308; // Código do Município
$std->tpImp = 1; // 1 - Retrato | 2 - Paisagem 
$std->tpEmis = 1; // Tipo de emissão da NFe ( 1 - Normal | 2 - Contingencia )
$std->cDV = null; // Digito verificador da chave de acesso 
$std->tpAmb = 2; // 1 - Produção  2 - Homologação
$std->finNFe = 1; // 1- NF-e normal | 2-NF-e complementar | 3 – NF-e de ajuste
$std->indFinal = 0; // 0 Consumidor Normal | 1 - Consumidor final 
$std->indPres = 0; // Se ouve presença no momento da compra
$std->procEmi = 0; //  emissão de NF-e com aplicativo do contribuinte
$std->verProc = '1.0';

$nfe->tagide($std);

$std->xNome = "FUSION LASTRAS & MÁRMORES";
$std->xFant = "FUSION LASTRAS & MÁRMORES LTDA";
$std->IE = "121813312110";
$std->CRT = "1"; // Tipo de Regime tributário (Simples)
$std->CNPJ = "51125803000103"; //indicar apenas um CNPJ ou CPF

$nfe->tagemit($std);

$std->xLgr = "RUA JOAQUIM LAPAS VEIGA";
$std->nro = "532";
$std->xCpl = "";
$std->xBairro = "JARDIM GILDA MARIA";
$std->cMun = "3550308";
$std->xMun = "SAO PAULO";
$std->UF = "SP";
$std->CEP = "05550010";
$std->cPais = "1058";
$std->xPais = "BRASIL";
$std->fone = "1137814288";

$nfe->tagenderEmit($std);

$std->xNome = $linhaCliente['nome'];
$std->indIEDest = "1";
$std->IE = "148079034114";
$std->CNPJ = "03593880000579"; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
$std->email = $linhaCliente['email'];

$nfe->tagdest($std);

$std->xLgr = "RUA JOAQUIM LAPAS VEIGA";
$std->nro = "650";
$std->xCpl = "";
$std->xBairro = "JARDIM GILDA MARIA";
$std->cMun = "3550308";
$std->xMun = "SAO PAULO";
$std->UF = "SP";
$std->CEP = "05550010";
$std->cPais = "1058";
$std->xPais = "BRASIL";
$std->fone = "1137814288";

$nfe->tagenderDest($std);

$std->item = 1; //item da NFe
$std->cProd = "001";
// $std->cEAN = "7898202570260";
$std->xProd = "TESTE";
$std->NCM = "68101900";
// $std->cBenef = "2804300";
$std->CFOP = "5102";
$std->uCom = "UN";
$std->qCom = "1";
$std->vUnCom = "10.00";
$std->vProd = "10.00";
// $std->cEANTrib = "7898202570260";
$std->uTrib = "UN";
$std->qTrib = "1";
$std->vUnTrib = "10.0000000000";
$std->indTot = "1";
// $std->xPed = "0000009594";
// $std->nItemPed = "1";

$nfe->tagprod($std);

$std->vTotTrib = 10.00;

$nfe->tagimposto($std);

$std->orig = 0;
$std->CSOSN = '102';
$std->pCredSN = 2.00;
$std->vCredICMSSN = 20.00;

$nfe->tagICMSSN($std);

$std->vBC = 0;
$std->vICMS = 0;
$std->vICMSDeson = 0;
$std->vBCST = 0;
$std->vST = 0;
$std->vProd = 0;
$std->vFrete = 0;
$std->vSeg = 0;
$std->vDesc = 0;
$std->vII = 0;
$std->vIPI = 0;
$std->vPIS = 0;
$std->vCOFINS = 0;
$std->vOutro = 0;
$std->vNF = 0;
$std->vIPIDevol = 0;
$std->vTotTrib = 0;

$nfe->tagICMSTot($std);

$std->modFrete = 9;

$nfe->tagtransp($std);

//$std->qVol = 2;
//$std->esp = 'caixa';
//$std->marca = 'OLX';
//$std->nVol = '11111';
//$std->pesoL = 10.50;
//$std->pesoB = 11.00;

//$nfe->tagvol($std);

//$std->nFat = '1233';
//$std->vOrig = 1254.22;
//$std->vDesc = null;
//$std->vLiq = 1254.22;

$nfe->tagfat($std);

//$std->nDup = '1233-1';
//$std->dVenc = '2017-08-22';
//$std->vDup = 1254.22;

//$nfe->tagdup($std);

$std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

$nfe->tagpag($std);

$std->tPag = '01';
$std->vPag = 200.00; //Obs: deve ser informado o valor pago pelo cliente
$std->CNPJ = '12345678901234';
$std->tBand = '01';
$std->cAut = '3333333';
$std->tpIntegra = 1; //incluso na NT 2015/002
$std->indPag = '0'; //0= Pagamento à Vista 1= Pagamento à Prazo

$nfe->tagdetPag($std);

$std->infAdFisco = '';
$std->infCpl = '';

$nfe->taginfAdic($std);

$std->CNPJ = '99999999999999'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
$std->xContato = 'Fulano de Tal'; //Nome da pessoa a ser contatada
$std->email = 'fulano@soft.com.br'; //E-mail da pessoa jurídica a ser contatada
$std->fone = '1155551122'; //Telefone da pessoa jurídica/física a ser contatada
$std->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
$std->idCSRT = '01'; //Identificador do CSRT

$nfe->taginfRespTec($std);

//$erros = $nfe->getErrors();
//var_dump($nfe->getErrors());

//$xml = $nfe->monta();
$xml = $nfe->getXML();

echo $xml;

file_put_contents('fixtures/nota.xml', $xml);
