<?php

include __DIR__ . "/../../topo.php";

$consulta = $pdo->query("SELECT id_pedido, codigo FROM pedidos WHERE id_empresa = " . $company . " ORDER BY id_pedido DESC LIMIT 0,1");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

if ($linha['id_pedido'] == "") {
    $id_pedido = 1;

    $os = 1;
} else {
    $id_pedido = $linha['id_pedido'] + 1;

    $os = $linha['codigo'] + 1;
}

$consultaPrazo = $pdo->query("SELECT * FROM prazo_de_pagamento WHERE id_empresa = " . $company . " ORDER BY id_pagamento ASC");
while ($linhaPrazo = $consultaPrazo->fetch(PDO::FETCH_ASSOC)) {

    $loopPrazo .= '<option value="' . $linhaPrazo['id_pagamento'] . '" >' . $linhaPrazo['descricao'] . '</option>';
}

$consultaMoedaD = $pdo->query("SELECT * FROM moedas WHERE id_empresa = " . $company . " AND data = '" . date('Y-m-d') . "' AND moeda = 'USD'");
$totalMoedaD = $consultaMoedaD->rowCount();
if ($totalMoedaD >= 1) {
    $linhaMoedaD = $consultaMoedaD->fetch(PDO::FETCH_ASSOC);
    $dolar = number_format($linhaMoedaD['valor'], 3, ",", ".");
} else {
    $dolar = '0,00';
}

$consultaMoedaE = $pdo->query("SELECT * FROM moedas WHERE id_empresa = " . $company . " AND data = '" . date('Y-m-d') . "' AND moeda = 'EUR'");
$totalMoedaE = $consultaMoedaE->rowCount();
if ($totalMoedaE >= 1) {
    $linhaMoedaE = $consultaMoedaE->fetch(PDO::FETCH_ASSOC);
    $euro = number_format($linhaMoedaE['valor'], 3, ",", ".");
} else {
    $euro = '0,00';
}

$moedas .= ' <div class="ribbon-target" style="bottom: 15px;">
    <span class="ribbon-inner bg-success"></span>Dólar: ' . $dolar . '<br>Euro: ' . $euro . '
</div>';

$stmt = $pdo->prepare("DELETE FROM pedidos_produtos WHERE temp = 1");
$stmt->execute();

$stmt_ = $pdo->prepare("DELETE FROM pedidos_pagamentos WHERE temp = 1");
$stmt_->execute();

unset($_SESSION['produtos']);

?>
<style>
    span.select2-selection.select2-selection--single {
        border-top-right-radius: 0.42rem !important;
        border-bottom-right-radius: 0.42rem !important;
    }

    .table-bordered td {
        vertical-align: middle;
    }

    #kt_datatable_estoque_filter,
    #kt_datatable_estoque th:nth-last-child(1),
    #kt_datatable_estoque td:nth-last-child(1):not(.dataTables_empty),
    #kt_datatable_estoque th:nth-last-child(2),
    #kt_datatable_estoque td:nth-last-child(2):not(.dataTables_empty),
    #kt_datatable_estoque th:nth-last-child(3),
    #kt_datatable_estoque td:nth-last-child(3):not(.dataTables_empty) {
        display: none;
    }

    div#listaMateriais {
        overflow-y: hidden !important;
    }

    div#addProduto {
        overflow-y: hidden !important;
    }
</style>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6" id="kt_subheader">
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
                        <a href="<?= $url ?>vendas/pedido" class="text-muted">Pedido</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted">Adicionar</a>
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
            <a href="../pedido/" class="btn btn-default font-weight-bold">Voltar</a>
            <!--end::Button-->
            <!--begin::Daterange-->
            <a href="#" class="btn btn-light-primary btn-sm font-weight-bold ml-2" data-toggle="tooltip" data-placement="left">
                <span class="svg-icon svg-icon-md">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M12,22 C7.02943725,22 3,17.9705627 3,13 C3,8.02943725 7.02943725,4 12,4 C16.9705627,4 21,8.02943725 21,13 C21,17.9705627 16.9705627,22 12,22 Z" fill="#000000" opacity="0.3" />
                            <path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000" />
                        </g>
                    </svg><!--end::Svg Icon--></span>
                <span class="font-weight-bold" id="kt_dashboard_daterangepicker_date"><?= date('d') ?> de <?= date('M') ?></span>
            </a>
            <!--end::Daterange-->
        </div>

        <!--end::Toolbar-->
    </div>
</div>
<!--end::Subheader-->
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid pt-0" id="kt_content">
    <div class="subheader py-2 py-lg-4  subheader-transparent " id="kt_subheader" style="background: #ebedf6;">
        <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap p-0">
            <!--begin::Details-->
            <div class="d-flex align-items-center flex-wrap mr-2">
            </div>
            <!--end::Details-->
        </div>
    </div>
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <div class="" id="pedido_add" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="pedido_add_form">
                                    <input type="hidden" name="type" value="pedido_add" />
                                    <input type="hidden" name="emissor" value="<?= $_COOKIE['id_is'] ?>" />
                                    <input type="hidden" name="id_pedido" id="id_pedido" value="<?= $id_pedido ?>">

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Pedido:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" value="<?= $os ?>" placeholder="Codigo">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Data Emissão:</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y') ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="col-12 col-lg">
                                            <label>Cliente:<span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="cliente" name="cliente">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Vendedor:<span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="vendedor" name="vendedor">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Transportadora:<span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="transportadora" name="transportadora">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="d-flex justify-content-end mb-3">
                                        <a href="#" class="btn btn-light-primary font-weight-bold" data-toggle="modal" data-target="#addProduto">Adicionar Produto</a>
                                    </div>
                                    <div class="table-responsive-lg">

                                        <table class="table table-striped table-bordered ">
                                            <thead style="background: #9acfea !important;">
                                                <tr>
                                                    <th class="text-center" scope="col">Item</th>
                                                    <th class="text-center">Chapas</th>
                                                    <th style="width:300px; text-align:center;">Descrição</th>
                                                    <th scope="col">Medidas</th>
                                                    <th class="text-nowrap text-right" scope="col">M²</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Unitário</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Total</th>
                                                    <th scope="col" style="width:90px; text-align:center;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody class="materiais">
                                                <tr>
                                                    <th class="text-center" colspan="8">Sem Produtos cadastrados</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="form-group row">

                                        <div class="col-12 col-lg">
                                            <label>Valor ICMS ST:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="vl_icms" value="" placeholder="Valor ICMS ST">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg">
                                            <label>Valor IPI:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="vl_ipi" value="" placeholder="Valor IPI">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Outros Valores:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="vl_outros" value="" placeholder="Outros Valores">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Valor Desconto:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="vl_desconto" value="" placeholder="Valor Desconto">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Valor Produtos:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="vl_produto" placeholder="Valor Produto" readonly tabindex="-1">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="row mb-5">
                                        <div class="col-6">
                                            <div class="card card-custom card-border  ribbon ribbon-clip ribbon-right">

                                                <?= $moedas ?>

                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-12 col-lg-12">
                                                            <label>Comprador:</label>
                                                            <input type="text" class="form-control" name="comprador" placeholder="Comprador">
                                                        </div>

                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label>Frete</label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="frete" value="0">
                                                                <span></span>
                                                                Nós Entregamos
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" name="frete" value="1">
                                                                <span></span>
                                                                Cliente Retira
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card card-custom card-border">

                                                <div class="card-body">
                                                    <div class="d-flex justify-content-end mb-3">
                                                        <a href="#" class="btn btn-light-success font-weight-bold add-pagamento">Adicionar Pagamento</a>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-12">
                                                            <div class="row mb-2">
                                                                <div class="col-4">
                                                                    <label>Forma de Pagamento:</label>
                                                                    <select class="form-control" name="forma">
                                                                        <option value="">...</option>
                                                                        <option value="Boleto">Boleto</option>
                                                                        <option value="Cartão de Credito">Cartão de Credito</option>
                                                                        <option value="Cartão de Debito">Cartão de Debito</option>
                                                                        <option value="Carteira">Carteira</option>
                                                                        <option value="Cheque">Cheque</option>
                                                                        <option value="Credito Cliente">Credito Cliente</option>
                                                                        <option value="Dinheiro">Dinheiro</option>
                                                                        <option value="Permuta">Permuta</option>
                                                                        <option value="Super Link">Super Link</option>
                                                                        <option value="Transferência">Transferência</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label>Prazo de Pagamento:</label>
                                                                    <select class="form-control" name="prazo">
                                                                        <option value="">...</option>
                                                                        <?= $loopPrazo ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label>Valor:</label>
                                                                    <input type="text" class="form-control moneyMask" name="valor" value="" placeholder="Valor">
                                                                </div>
                                                            </div>

                                                            <div class="separator separator-dashed my-10"></div>
                                                        </div>

                                                        <div class="col-12">
                                                            <table class="table table-bordered table-striped table-sm rounded table-hover table-extra mb-0" id="kt_datatable_pagamento">
                                                                <thead style="background: #9acfea !important;">
                                                                    <tr>
                                                                        <th scope="col">Forma</th>
                                                                        <th scope="col">Prazo</th>
                                                                        <th scope="col">Valor</th>
                                                                        <th scope="col">Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th scope="col"></th>
                                                                        <th scope="col"></th>
                                                                        <th scope="col"></th>
                                                                        <th scope="col"></th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <!-- <div class="d-flex align-items-center mb-0 bg-light-warning rounded p-5">
                                                <span class="svg-icon svg-icon-warning mr-5">
                                                    <span class="svg-icon svg-icon-lg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <defs />
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <rect fill="#000000" opacity="0.3" x="11.5" y="2" width="2" height="4" rx="1" />
                                                                <rect fill="#000000" opacity="0.3" x="11.5" y="16" width="2" height="5" rx="1" />
                                                                <path d="M15.493,8.044 C15.2143319,7.68933156 14.8501689,7.40750104 14.4005,7.1985 C13.9508311,6.98949895 13.5170021,6.885 13.099,6.885 C12.8836656,6.885 12.6651678,6.90399981 12.4435,6.942 C12.2218322,6.98000019 12.0223342,7.05283279 11.845,7.1605 C11.6676658,7.2681672 11.5188339,7.40749914 11.3985,7.5785 C11.2781661,7.74950085 11.218,7.96799867 11.218,8.234 C11.218,8.46200114 11.2654995,8.65199924 11.3605,8.804 C11.4555005,8.95600076 11.5948324,9.08899943 11.7785,9.203 C11.9621676,9.31700057 12.1806654,9.42149952 12.434,9.5165 C12.6873346,9.61150047 12.9723317,9.70966616 13.289,9.811 C13.7450023,9.96300076 14.2199975,10.1308324 14.714,10.3145 C15.2080025,10.4981676 15.6576646,10.7419985 16.063,11.046 C16.4683354,11.3500015 16.8039987,11.7268311 17.07,12.1765 C17.3360013,12.6261689 17.469,13.1866633 17.469,13.858 C17.469,14.6306705 17.3265014,15.2988305 17.0415,15.8625 C16.7564986,16.4261695 16.3733357,16.8916648 15.892,17.259 C15.4106643,17.6263352 14.8596698,17.8986658 14.239,18.076 C13.6183302,18.2533342 12.97867,18.342 12.32,18.342 C11.3573285,18.342 10.4263378,18.1741683 9.527,17.8385 C8.62766217,17.5028317 7.88033631,17.0246698 7.285,16.404 L9.413,14.238 C9.74233498,14.6433354 10.176164,14.9821653 10.7145,15.2545 C11.252836,15.5268347 11.7879973,15.663 12.32,15.663 C12.5606679,15.663 12.7949989,15.6376669 13.023,15.587 C13.2510011,15.5363331 13.4504991,15.4540006 13.6215,15.34 C13.7925009,15.2259994 13.9286662,15.0740009 14.03,14.884 C14.1313338,14.693999 14.182,14.4660013 14.182,14.2 C14.182,13.9466654 14.1186673,13.7313342 13.992,13.554 C13.8653327,13.3766658 13.6848345,13.2151674 13.4505,13.0695 C13.2161655,12.9238326 12.9248351,12.7908339 12.5765,12.6705 C12.2281649,12.5501661 11.8323355,12.420334 11.389,12.281 C10.9583312,12.141666 10.5371687,11.9770009 10.1255,11.787 C9.71383127,11.596999 9.34650161,11.3531682 9.0235,11.0555 C8.70049838,10.7578318 8.44083431,10.3968355 8.2445,9.9725 C8.04816568,9.54816454 7.95,9.03200304 7.95,8.424 C7.95,7.67666293 8.10199848,7.03700266 8.406,6.505 C8.71000152,5.97299734 9.10899753,5.53600171 9.603,5.194 C10.0970025,4.85199829 10.6543302,4.60183412 11.275,4.4435 C11.8956698,4.28516587 12.5226635,4.206 13.156,4.206 C13.9160038,4.206 14.6918294,4.34533194 15.4835,4.624 C16.2751706,4.90266806 16.9686637,5.31433061 17.564,5.859 L15.493,8.044 Z" fill="#000000" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </span>
                                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                                    <a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Posição financeira até este Pedido</a>
                                                    <span class="text-muted font-weight-bold">Salto Anterior: </span>
                                                </div>
                                                <span class="font-weight-bolder text-warning py-1 font-size-lg">R$ 4.000,00</span>
                                            </div> -->
                                        </div>
                                        <div class="col-6">
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <!-- <div class="mr-5 text-right" style="line-height: 1.25;color: #c10c0c;">
                                                    <span>Saldo a Pagar</span><br>
                                                    <span style="font-weight: 800;font-size: 22px;">R$ 100,00</span>
                                                </div> -->
                                                <span class="txt_total"></span>
                                                <input type="hidden" name="vl_pedido" value="">
                                                <div>
                                                    <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Gravar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--end::Form Wizard-->
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
<!--begin::Content Wrapper-->
</div>
<!--end::Content-->
<div id="theModal" class="modal fade text-center">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<!-- Modal Add Produto-->
<div class="modal fade" id="addProduto" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Produtos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="500">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>Código:</label>
                            <input type="text" class="form-control datatable-input-materiais codigo_search" placeholder="Código" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Descrição:</label>
                            <input type="text" class="form-control datatable-input-materiais descricao_search" placeholder="Descrição" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_materiais">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>Pesquisar</span>
                                </span>
                            </button>
                            <a href="#" class="d-block zerar"><small>Zerar Pesquisa</small></a>
                        </div>
                        <div class="col-xl-12 p-0">
                            <div class="separator separator-dashed my-5"></div>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_materiais">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>#</th>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Fechar sem Incluir</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Lista Materiais-->
<div class="modal fade" id="listaMateriais" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista em Estoque</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="500" data-wheel-propagation="true">
                    <!-- <input type="text" id="id_r_produto" /> -->
                    <!--begin: Datatable-->
                    <form class="mb-15">
                        <div class="row">
                            <div class="col-lg-4 mb-lg-0 mb-6">
                                <label>Código:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Ex: TNCP200010000001" data-col-index="1" value="" />
                            </div>
                            <div class="col-lg-2 mb-lg-0 mb-6">
                                <label>Espessura:</label>
                                <input type="text" class="form-control datatable-input qtd1Mask" placeholder="Ex: 1,8" data-col-index="2" value="" />
                            </div>
                            <div class="col-lg-2 mb-lg-0 mb-6">
                                <label>Comprimento:</label>
                                <input type="text" class="form-control datatable-input qtdMask" placeholder="Ex: 2,00" data-col-index="3" value="" />
                            </div>
                            <div class="col-lg-2 mb-lg-0 mb-6">
                                <label>Altura:</label>
                                <input type="text" class="form-control datatable-input qtdMask" placeholder="Ex: 2,00" data-col-index="4" value="" />
                            </div>
                            <div class="col-lg-2 mb-lg-0 mb-6">
                                <label>M²:</label>
                                <input type="text" class="form-control datatable-input qtd3Mask" placeholder="Ex: 2,000" data-col-index="5" value="" />
                            </div>
                        </div>
                        <div class="row mb-6">

                            <div class="col-lg mb-lg-0 mb-6 pt-6 text-right">
                                <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Pesquisar</span>
                                    </span>
                                </button>&#160;&#160;
                                <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Limpar</span>
                                    </span>
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_estoque">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>
                                    <label class="checkbox">
                                        <input type="checkbox" class="selectAll">
                                        <span></span>
                                    </label>
                                </th>
                                <th>Código</th>
                                <th>Medidas</th>
                                <th>Comprimento</th>
                                <th>Altura</th>
                                <th>Metro</th>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable-->
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-light-primary font-weight-bold add-produto mr-3">Inserir Produtos</a>
                <button type="button" class="btn btn-light-primary font-weight-bold close-produtos" data-dismiss="modal">Fechar sem Incluir</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="modal_ativo">
<div class="modais"></div>

<div class="modal fade" id="editProduto" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="" id="produtos_edit" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="produtos_edit_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="descricaoProduto">Editar <b>()</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div data-scroll="true" data-height="500">
                            <div class="row mb-0">
                                <div class="col-12">

                                    <!--begin::Wizard Body-->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-10 col-xxl-10">

                                            <input type="hidden" name="type" value="produtos_edit" />
                                            <input type="hidden" name="id_produto" value="" />

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Produto:</label>
                                                <div class="col-xl-9 col-lg-9">

                                                    <input type="text" class="form-control form-control-sm" name="produto" value="" readonly tabindex="-1">

                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-7">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Espessura:</label>
                                                        <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">

                                                            <input type="text" class="form-control form-control-sm" name="espessura" value="" readonly tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-5">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Chapas:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="number" class="form-control form-control-sm" name="chapas" value="" readonly tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Comprimento:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm qtdMask" name="comprimento" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Altura:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm qtdMask" name="altura" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-0">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Metro ²:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm qtd3Mask" name="metro" value="">
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon atualizar" title="Atualizar" style="bottom: 5px;">
                                                            <i class="la la-refresh"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="separator separator-dashed my-5"></div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Moeda:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="moeda">
                                                                <option value="R$" selected>Real</option>
                                                                <option value="U$">Dólar</option>
                                                                <option value="€">Euro</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-0">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Preço:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Preço" name="custo">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed my-5"></div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Valor em R$:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm" name="valor_real" value="" readonly tabindex="-1">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Total:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm" name="subtotal" value="" readonly tabindex="-1">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Form Wizard-->
                                        </div>
                                    </div>
                                    <!--end::Wizard Body-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary font-weight-bolder" data-wizard-type="action-submit">Gravar</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Fechar sem Incluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--end::Container-->

<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/vendas/pedido/add.js"></script>