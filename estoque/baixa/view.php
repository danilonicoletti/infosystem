<?
include('../../topo.php');

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM baixa_estoque WHERE id_baixa = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$consultaFornecedor = $pdo->query("SELECT nome FROM fornecedor WHERE id_fornecedor = " . $linha['id_fornecedor']);
$linhaFornecedor = $consultaFornecedor->fetch(PDO::FETCH_ASSOC);

$id_baixa = $linha['id_baixa'];

$consultaProd = $pdo->query("SELECT id_r_produto, t1.codigo, descricao, chapas, t1.unidade, t1.espessura, comprimento, altura, metro FROM baixa_produtos as t1 INNER JOIN produtos as t2 ON t1.id_produto = t2.id_produto WHERE id_baixa = " . $id_baixa . " ORDER BY t1.codigo ASC");
$total = $consultaProd->rowCount();

if ($total >= 1) {
    $i = 1;
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        $loopProd .= '<tr>
            <td class="text-center">' . $i . '</td>
            <td>' . $linhaProd['codigo'] . '</td>
            <td>' . $linhaProd['descricao'] . '</td>
            <td>Esp ' . number_format($linhaProd['espessura'], 2, ",", "") . ' Cm - ' . number_format($linhaProd['comprimento'], 2, ",", "") . ' x ' . number_format($linhaProd['altura'], 2, ",", "") . ' Metros</td>
            <td>' .  number_format($linhaProd['metro'], 3, ",", "") . '</td>
            <td>' . $linhaProd['chapas'] . '</td>
        </tr>';

        $i++;

        $totalChapas += $linhaProd['chapas'];
        $totalMetros += $linhaProd['metro'];
    }

    $loopProd .= '<tr style="background-color: #4a78c885;">
        <td class="text-center"></td>
        <td></td>
        <td><b>TOTAL</b></td>
        <td></td>
        <td>' .  number_format($totalMetros, 3, ",", "") . '</td>
        <td>' . $totalChapas . '</td>
    </tr>';
} else {
    $loopProd .= "<tr>
                 <th class='text-center' colspan='6'>Sem produtos cadastrados</th>
             </tr>";
}
?>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">InfoSystem</h5>
                <!--end::Page Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= $url ?>baixa" class="text-muted">Baixa </a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted">Ver</a>
                    </li>

                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Button-->
            <a href="../baixa/" class="btn btn-default font-weight-bold">Voltar</a>
            <!--end::Button-->

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--end::Subheader-->
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <div data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->

                                <div class="form-group row">
                                    <div class="col-12 col-lg-2">
                                        <label>Nº Saida:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="saida" placeholder="Nº Saida" value="<?= $linha['saida'] ?>">
                                    </div>

                                    <div class="col-12 col-lg-2">
                                        <label>Data:<span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-solid dateMask" placeholder="Selecione a Data" name="dt_emissao" value="<?= implode("/", array_reverse(explode("-", $linha['dt_emissao']))) ?>" id="dt_emissao" required tabindex="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="border: none;">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-8">
                                        <label>Fornecedor:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="fornecedor" value="<?= $linhaFornecedor['nome'] ?>" placeholder="Fornecedor" autocomplete="INFOSYSTEM" readonly>
                                    </div>

                                </div>

                                <div class="separator separator-dashed my-10"></div>

                                <div class="table-responsive-lg">

                                    <table class="table table-striped table-bordered ">
                                        <thead>
                                            <tr class="table-active">
                                                <th class="text-center" scope="col">Item</th>
                                                <th scope="col">Código</th>
                                                <th scope="col">Descrição</th>
                                                <th scope="col">Medidas</th>
                                                <th scope="col">Metro</th>
                                                <th scope="col">Chapas</th>
                                            </tr>
                                        </thead>
                                        <tbody class="produtos">
                                            <?= $loopProd ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Wizard Body-->
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->
</div>
<!--end::Entry-->
</div>
<!--end::Content-->
</div>
<!--begin::Content Wrapper-->
</div>
<!--end::Content-->

<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/estoque/baixa/view.js"></script>