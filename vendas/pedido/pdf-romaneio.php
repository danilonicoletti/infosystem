<?php

require __DIR__ . '/../../inc/config.php';
require __DIR__ . '/../../login/conn.php';
include __DIR__ . '/./../inc/url.php';
include __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../inc/company.php';
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_pedido = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM pedidos WHERE id_pedido = " . $id_pedido);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['cliente']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);

$consultaProd = $pdo->query("SELECT id_p_produto, SUM(chapas) as chapas, descricao, medidas, SUM(metro) as metro, SUM(valor) as valor, SUM(subtotal) as subtotal FROM pedidos_produtos WHERE id_pedido = " . $id_pedido . " AND temp = 0 GROUP BY descricao,medidas ORDER BY item ASC");
$total = $consultaProd->rowCount();
$item = 1;
$somaItem = 55;

if ($total >= 1) {
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        $produtos .= '<tr>
                    <td style="text-align:center;">' . $linhaProd['chapas'] . '</td>
                    <td>' . $linhaProd['codigo'] . '</td>
                    <td>' . $linhaProd['descricao'] . '</td>
                    <td style="text-align:center;">' . $linhaProd['medidas'] . '</td>
                    <td style="text-align:center;">' . number_format($linhaProd['metro'], 3, ",", "") . '</td>
                </tr>';

        $somaItem++;
        $totalChapas += $linhaProd['chapas'];
        $totalMetros += $linhaProd['metro'];
    }
}

$height = 500 - $somaItem;

// Função para obter o conteúdo HTML
$html = '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Romaneio de Entrega</title>
        <style>
            @page { margin: 0; }
            body {
                font-family: Helvetica Neue, sans-serif;
                font-size:10px;
                margin:0;
                padding: 10px;
            }
            .header, .info, .table {
                width: 100%;
                border-collapse: collapse;
            }
            .header th, .header td {
                text-align: center;
                padding: 3px;
                border: 0.5px solid #000;
            }
            .info td {
                vertical-align: middle;
                padding: 1px;
            }
            .info th {
                text-align: left;
                padding: 1px;
            }
            .info table {
                width: 100%;
            }
   
            .table th, .table td {
                border-left: 0.5px solid #000;
                border-right: 0.5px solid #000;
                padding: 5px;
            }
            .table th {
                background-color: #f2f2f2;
                border-top: 0.5px solid #000;
                border-bottom: 0.5px solid #000;
            }
            .table tbody {
                display: table-row-group;
            }
            .table tr:last-child td {
                border-bottom: 0.5px solid #000;
            }
            .footer {
                border-top: 0.5px solid #000;
                border-left: 0.5px solid #000;
                border-right: 0.5px solid #000;
                padding: 3px;
                text-align: left;
            }
            .footer td {
                border: none;
                padding: 3px;
            }
            .observations{
                width: 100%;
                border-collapse: collapse;
            }
            .observations th {
                border-top: none !important;
            }
            .observations th, .observations td {
                text-align: center;
                padding: 3px;
                border: 0.5px solid #000;
                height:15px;
            }
            .blocos th, .blocos td {
                text-align: center;
                padding: 5px;
                border: none !importan;
            }
            .spacer {
                height: ' . $height . 'px;
            }
           
        </style>
    </head>
    <body>
        <table class="header" border="1">
            <tr>
                <td colspan="2" style="height:60px;"><strong>POLO MÁRMORES</strong></td>
            </tr>
            <tr>
                <td><strong>ROMANEIO DE ENTREGA DO PEDIDO ' . $linha['codigo'] . '</strong></td>
                <td style="width:100px"><strong>Data: ' . implode("/", array_reverse(explode("-", $linha['dt_emissao']))) . '</strong></td>
            </tr>
        </table>
        <br>
        <table class="info">
            <tr>
                <td style="width:50%;">
                    <table>
                        <tr><td><strong>Cliente:</strong> ' . $linhaCliente['nome'] . '</td></tr>
                        <tr><td><strong>Endereço:</strong> ' . $linhaCliente['endereco'] . ',' . $linhaCliente['numero'] . '</td></tr>
                        <tr><td><strong>Município:</strong> ' . $linhaCliente['municipio'] . '</td></tr>
                        <tr><td><strong>Bairro:</strong> ' . $linhaCliente['bairro'] . '</td></tr>
                        <tr><td><strong>CNPJ:</strong></td> ' . $linhaCliente['documento'] . '</tr>
                        <tr><td><strong>Comprador:</strong> ' . $linha['comprador'] . '</td></tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr><td><strong>Fone:</strong> ' . $linhaCliente['telefone'] . '</td></tr>
                        <tr><td><strong>Celular:</strong> ' . $linhaCliente['celular'] . '</td></tr>
                        <tr><td><strong>Estado:</strong ' . $linhaCliente['uf'] . '></tr>
                        <tr><td><strong>CEP:</strong> ' . $linhaCliente['cep'] . '</td></tr>
                        <tr><td><strong>I. Estadual:</strong> ' . $linhaCliente['documentos'] . '</td></tr>
                        <tr><td><strong>E-mail:</strong></td ' . $linhaCliente['email'] . '></tr>
                    </table>
                </td>
            </tr>
            
        </table>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align:center;">Qtd</th>
                    <th style="text-align:left; width:130px;">Código / Nº Etiqueta</th>
                    <th style="text-align:left;">Descrição</th>
                    <th style="text-align:center; width:130px;">Medidas</th>
                    <th style="text-align:center;">M2</th>
                </tr>
            </thead>
            <tbody>
                ' . $produtos . '
                <tr>
                    <td class="spacer">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
            <tfoot>
                <!-- Adicione mais linhas conforme necessário -->
                    <tr class="footer">
                        <td style="text-align:center;"><strong>' . $totalChapas . '</strong></td>
                        <td><strong>Total de Chapas</strong></td>
                        <td></td>
                        <td style="text-align:right;"><strong>Total de M2</strong></td>
                        <td style="text-align:center;"><strong>' . number_format($totalMetros, 3, ",", "") . '</strong></td>
                    </tr>
            </tfoot>
        
        </table>
        <table class="observations">
            <thead>
                <tr>
                    <th>Observações</th>
                </tr>
            </thead>
            <tbody>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                    <td style="text-align:center;">
                        
                        <strong>
                        *** Atenção: Inspecione detalhadamente as mercadorias recebidas<br>
                        Não aceitaremos reclamações nem devoluções após o ato da entrega ou retirada das mercadorias
                        </strong>
                        <br><br><br>
                        <table stye=" width:100%;" class="blocos">
                            <tr stye="">
                                <td stye="width:25%;  text-align:center;">
                                    __________/__________/___________<br>
                                    Finalização da Entrega
                                </td>
                                <td stye="width:25%;border:none; text-align:center;">
                                    _______________________________<br>
                                    ' . $linhaCliente['nome'] . '
                                </td>
                                <td stye="width:25%;border:none; text-align:center;">
                                    _______________________________<br>
                                    &nbsp;
                                </td>
                                <td stye="width:25%;border:none; text-align:center;">
                                    _______________________________<br>
                                    Entregador
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>';;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isPhpEnabled', true);

// Cria uma instância do DOMPDF
$dompdf = new Dompdf();

// Carrega o conteúdo HTML
$dompdf->loadHtml($html);

// (Opcional) Configure o tamanho e a orientação do papel
$dompdf->setPaper('A4', 'portrait');

// Renderiza o HTML como PDF
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream("romaneio.pdf", ["Attachment" => false]);
