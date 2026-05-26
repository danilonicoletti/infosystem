<?

require __DIR__ . '/../../inc/config.php';
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/./../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';
require __DIR__ . '/../../vendor/autoload.php';

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT os, t1.data, t2.nome as emissor, t3.nome as vendedor, t3.celular as v_celular, t3.email as v_email, cliente, tipo_contato, t1.contato as contato,obra, t1.endereco, prazo, condicao, desconto, frete, custos, t1.telefone, t1.email, acrescimo, t1.complemento FROM orcamentos as t1 INNER JOIN usuarios as t2 ON t1.emissor = t2.id INNER JOIN vendedor as t3 on t1.vendedor = t3.id_vendedor WHERE id_orcamento = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

if ($linha['contato'] != "") {
    if ($linha['tipo_contato'] == "") {
        $tipo_contato = "Contato:&nbsp;";
    } else {
        $tipo_contato = $linha['tipo_contato'] . ":&nbsp;";
    }
    $boxContato = $tipo_contato . $linha['contato'];
} else {
    $boxContato = "";
}

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

// $path = '../../assets/media/company/16874434251.png';
$path = '../../assets/media/company/logo-fusion-lastras-alta-transparente_branco.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

// (Optional) Setup the paper size and orientation
//$dompdf->setPaper('A4', 'landscape');

$data = new DateTime($linha['data']);

$consultaProd = $pdo->query("SELECT id_o_produto, item, material, ambiente, t1.descricao, unid, qtd, t1.valor, acabamento, outros, acrescimo FROM orcamentos_produtos as t1 INNER JOIN produtos as t2 ON t1.material = t2.id_produto WHERE id_orcamento = " . $id . " ORDER BY item ASC");
$totalLinhas = $consultaProd->rowCount();
if ($totalLinhas >= 1) {
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        $arrayArea[] = $linhaProd['material'] . "_" . $linhaProd['ambiente'];

        $quantidade = $linhaProd['qtd'];

        if ($linhaProd['descricao'] == '') {
            $descricao = '-- Sem Descrição --';
        } else {
            $descricao = nl2br($linhaProd['descricao']);
        }

        $loopProdArray[$linhaProd['material'] . "_" . $linhaProd['ambiente']] .= '<tr>
                        <td class="td-center">' . $linhaProd['item'] . '</td>
                        <td class="td-center">' . $descricao . '</td>
                        <td class="td-center">' .  number_format($linhaProd['qtd'], 2, ",", "") . '</td>
                        <td class="td-center">' . $linhaProd['unid'] . '</td>
                    </tr>';
    }
} else {
    $total = 0;
}
$a = 1;
if ($arrayArea !== null) {

    if (count($arrayArea) >= 1) {
        $headerProd = '<tr style="background-color:#D9D9D9;">
                        <td class="td-center">Item</td>
                        <td class="td-center" style="width:60%">Descrição</td>
                        <td class="td-center">Quant.</td>
                        <td class="td-center">Unid.</td>
                    </tr>';
        $arrayAreaUniq = array_unique($arrayArea);
        $a = 1;
        foreach ($arrayAreaUniq as $value) {

            $itemComposto = $a++;

            $produtoArea = explode("_", $value);

            $consultaProduto = $pdo->query("SELECT descricao FROM produtos WHERE id_produto = " . $produtoArea[0]);
            $linhaProduto = $consultaProduto->fetch(PDO::FETCH_ASSOC);

            if ($linhaProduto['descricao'] == 'Sem Material') {
                $descProduto = 'Sem Material';
            } else {
                $descProduto = $linhaProduto['descricao'];
            }

            $loopProd .= '<tr>
                            <td class="td-center">' . $itemComposto . '</td>
                            <td class="td-center"><b>' . $produtoArea[1] . '</b> - ' . $descProduto . '</td>
                            <td class="td-center"></td>
                            <td class="td-center"></td>
                         </tr>
                        ' . $loopProdArray[$value] . '<tr>
                            <td colspan="4" style="height:7px;"></td>
                        </tr>
                        ';

            $a++;
        }
    } else {
        $headerProd = '';
        $loopProd = '';
    }
} else {
    $loopProd = '';
}

if ($linha['obs_frete'] != '') {
    $obs_frete = '<br>(' . mb_strtoupper($linha['obs_frete']) . ')';
} else {
    $obs_frete = '';
}
if ($linha['frete'] != 0) {
    $tdFrete = '<tr>
                <td class="td-center"></td>
                <td colspan="4" class="td-right">FRETE PARA TRANSPORTE DE MATERIAL' . $obs_frete . '</td>
                <td class="td-right">&nbsp;&nbsp;R$ ' . number_format($linha['frete'], 2, ",", ".") . '</td>
            </tr>';
}
if ($linha['obs_custos'] != '') {
    $obs_custos = '<br>(' . mb_strtoupper($linha['obs_custos']) . ')';
} else {
    $obs_custos = '';
}
if ($linha['custos'] != 0) {
    if ($linha['tempo'] == 'N') {
        $tdCustos = '<tr>
                    <td class="td-center"></td>
                    <td colspan="4" class="td-right">CUSTOS PARA INSTALAÇÕES NOTURNAS/FINAIS DE SEMANA' . $obs_custos . '</td>
                    <td class="td-right">&nbsp;&nbsp;R$ ' . number_format($linha['custos'], 2, ",", ".") . '</td>
                </tr>';
    } else if ($linha['tempo'] == 'D') {
        $tdCustos = '<tr>
                    <td class="td-center"></td>
                    <td colspan="4" class="td-right">CUSTOS PARA INSTALAÇÕES DIURNAS/FINAIS DE SEMANA' . $obs_custos . '</td>
                    <td class="td-right">&nbsp;&nbsp;R$ ' . number_format($linha['custos'], 2, ",", ".") . '</td>
                </tr>';
    }
} else {
    if ($linha['tempo'] == 'N') {
        $tdCustos = '<tr>
                    <td class="td-center"></td>
                    <td colspan="4" class="td-right">CUSTOS PARA INSTALAÇÕES NOTURNAS/FINAIS DE SEMANA' . $obs_custos . '</td>
                    <td class="td-right">&nbsp;&nbsp;Não Incluso</td>
                </tr>';
    } else if ($linha['tempo'] == 'D') {
        $tdCustos = '<tr>
                    <td class="td-center"></td>
                    <td colspan="4" class="td-right">CUSTOS PARA INSTALAÇÕES DIURNAS/FINAIS DE SEMANA' . $obs_custos . '</td>
                    <td class="td-right">&nbsp;&nbsp;Não Incluso</td>
                </tr>';
    }
}
if ($linha['desconto'] != 0) {
    $tdDescontos = '<tr>
                        <td class="td-center"></td>
                        <td colspan="4" class="td-right">DESCONTO</td>
                        <td class="td-right">&nbsp;&nbsp;R$ ' . number_format($linha['desconto'], 2, ",", ".") . '</td>
                    </tr>';
} else {
    $tdDescontos = '';
}
if ($linha['v_celular'] == '') {
    $v_celular = '<br>';
} else {
    $v_celular = $linha['v_celular'];
}
if ($linha['v_email'] == '') {
    $v_email = '<br>';
} else {
    $v_email = $linha['v_email'];
}
if ($linha['condicao'] == "") {
    $condicao = '';
} else {
    $condicao = 'CONDIÇÕES DE PAGAMENTO: ' . $linha['condicao'] . '';
}
if ($linha['obra'] == "") {
    $obra = '';
} else {
    $obra = '<tr>
                <td class="td-left">Obra:</td>
                <td colspan="2" class="td-left">' . $linha['obra'] . '</td>
            </tr>';
}

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['cliente']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);

if ($linhaCliente['pessoa'] == 'fisica') {
    if ($linhaCliente['sem'] == '1') {
        $cliente =  $linhaCliente['nome'];
    } else {
        $cliente = $linhaCliente['nome'] . ' ' . $linhaCliente['nomes'];
    }
} else {
    $cliente = $linhaCliente['nomes'];
}
if ($linha['complemento'] != "") {
    $complemento = '<br><b>' . $linha['complemento'] . '</b>';
} else {
    $complemento = "";
}
$Html = '
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap");
    body {
        font-family: "Poppins", sans-serif;
        font-size:12px;
    }
    img{
        position: absolute;
        margin: 0 auto; 
        width: 136px;
        left: 26%;
        top:2px;
    }
    table{
        border-collapse: collapse;
    }
    td{
        border:1px solid #000000;
    }
    .td-left{
        padding:2px 0px 2px 5px;
    }
    .td-center{
        padding:2px 5px 2px 5px;
        text-align:center;
    }
    .td-right{
        padding:2px 5px 2px 0px;
        text-align:right;
    }
</style>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
    <tr>
        <td rowspan="3" style="width:70%; padding: 0px; margin:0px; background-color:#000000" align="center" >
            <img src="' . $base64 . '" style="width:276px;height:132px; position:relative; left:0px;" />
        </td>
        <td align="center" style="padding: 10px 0px; color:#de6565; font-weight:bold;">
            ORÇAMENTO:<br>
            ' . $linha['os'] . $complemento . '
        </td>
    </tr>
    <tr style="height:7px;">
        <td align="center" style="background-color:#D9D9D9; padding: 0px 0px;">Data: ' . $data->format('d/m/Y') . '</td>
    </tr>
    <tr>
        <td align="center" style="padding: 10px 0px;">
        ' . $linha['vendedor'] . '<br>
        ' . $v_celular . '<br>
        ' . $v_email . '
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%; margin-top:-1px;">
    ' . $obra . '
    <tr>
        <td style="width:15%;" class="td-left">Cliente:</td>
        <td class="td-left">' . $cliente . '</td>
        <td style="width:30%;" class="td-left">' . $boxContato . '</td>
    </tr>
    <tr>
        <td class="td-left">Endereço:</td>
        <td class="td-left">' . $linha['endereco'] . '</td>
        <td style="width:30%;" class="td-left">Telefone:&nbsp;' . $linha['telefone'] . '</td>
    </tr>
    <tr>
        <td class="td-left">E-mail:</td>
        <td class="td-left">' . $linha['email'] . '</td>
        <td style="background-color:#D9D9D9; width:30%;" class="td-left">Orçamento:&nbsp;' . $linha['emissor'] . '</td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%; margin-top:5px;">
    ' . $headerProd . '
    ' . $loopProd . '
    ' . $tdDescontos . '
    <tr >
        <td colspan="4" class="td-left" style="height:30px;">
        PRAZO DE ENTREGA: ' . $linha['prazo'] . ' dias úteis após projeto de corte aprovado<br>
        ' . $condicao . '
        </td>
    </tr>
    <tr >
        <td colspan="4" class="td-left" style="color:#de6565; font-size:10px;">
            <b>
            ORÇAMENTO SUJEITO A ALTERAÇÃO APÓS MEDIÇÃO IN LOCO . PODENDO GERAR CUSTOS EXTRAS.
            <br><br>
            EVENTUAIS ERROS DE SOMATÓRIA OU DIGITAÇÃO PODERÃO SER CORRIGIDOS ATÉ O MOMENTO DA ASSINATURA DO CONTRATO.
            <br><br>
            MATERIAIS FORNECIDOS PELO CLIENTE DEVERÃO SER ENTREGUES NA MARMORARIA.
            <br><br>
            HORÁRIO DE EXPEDIENTE: SEGUNDA A SEXTA DAS 08:00 AS 17:00 - QUALQUER TRABALHO EXECUTADO FORA DO HORÁRIO DE EXPEDIENTE DEVERÁ SER INFORMADO NO MOMENTO DA ELABORAÇÃO DO ORÇAMENTO. PODENDO HAVER CUSTO ADICIONAL.
            <br><br>
            PAGINAÇÃO DE PISOS E PAREDES SERÃO DEFINIDAS DE ACORDO COM O MELHOR APROVEITAMENTO DAS CHAPAS
            <br><br>
            EM  CASO DE NECESSIDADE DA OBRA PARA QUE SEJA NECESSÁRIO MEDIÇÃO E OU ENTREGUE/ INSTALAÇÃO DOS MATÉRIAS SEJA EXECUTADA EM ETAPAS (DIAS) DIFERENTES. SERÁ GERADO UM CUSTO ADICIONAL PARA MEDIÇÃO, ENTREGA E INSTALAÇÃO DOS ITENS.
            <br><br>
            TODA CUBA DE INOX SERÁ EMBUTIDA E COLADA POR BAIXO, CASO CONTRARIO APENAS COM ORIENTAÇÃO DO RESPONSÁVEL
            <br><br>
            A DISPONIBILIDADE DOS MATERIAIS ORÇADOS SERÁ VERIFICADA E CONFIRMADA NO ATO DO FECHAMENTO
            <br><br>
            OS ITENS NÃO INCLUÍDOS NO ORÇAMENTO SERÃO CONSIDERADOS EXCLUSO
            <br><br>
            TODAS PEÇAS ORÇADAS PODERÃO TER EMENDAS DEPENDENDO DO TAMANHO DAS CHAPAS
            <br><br>
            CUBAS, SERVENTIA, MATERIAL PARA COLOCAÇÃO DE RESPONSABILIDADE DO CLIENTE (CASO NÃO ESTEJAM INCLUSOS NO ORÇAMENTO).
            <br><br>
            EM CASO DE IÇAMENTO O CLIENTE SERÁ RESPONSÁVEL PELA CONTRATAÇÃO DO SERVIÇO.
            <br><br>
            No caso de fornecimento de argamassa, será considerado 13,33kg/m2 ( recomendação técnica do fabricante ) +25% referente a variação na regularização do contrapeso +2% de perdas inerentes ao processo perfazendo um total de 17kg/m2.A quantidade excedente será cobrada do cliente como adicional
            </b>
        </td>
    </tr>
</table>
';

// echo $Html;
// die();

$dompdf->loadHtml($Html);
// Render the HTML as PDF
$dompdf->render();

// $os = explode('/', $linha['os']);
// Output the generated PDF to Browser
$dompdf->stream($cliente . " - Orcamento " . limpaCarSpace($linha['os']) . ".pdf");
