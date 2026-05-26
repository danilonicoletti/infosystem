<?php

include __DIR__ . "/../../topo.php";

$consulta = $pdo->query("SELECT id_orcamento,os FROM orcamentos WHERE id_empresa = " . $company . " ORDER BY id_orcamento DESC LIMIT 0,1");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

if ($linha['id_orcamento'] == "") {
    $id_orcamento = 1;

    $os = 1;
} else {
    $id_orcamento = $linha['id_orcamento'] + 1;
    $explode_os = explode('/', $linha['os']);
    $os = (int)$explode_os[0] + 1;
}

$consultaEmissor = $pdo->query("SELECT * FROM usuarios WHERE ativo = 1 AND empresa = " . $company . " ORDER BY nome ASC");
while ($linhaEmissor = $consultaEmissor->fetch(PDO::FETCH_ASSOC)) {
    $loopEmissor .= '<option value="' . $linhaEmissor['id'] . '">' . $linhaEmissor['nome'] . '</option>';
}

$stmt = $pdo->prepare("DELETE FROM orcamentos_produtos WHERE temp = 1");
$stmt->execute();

$consultaAmbiente = $pdo->query("SELECT * FROM ambiente WHERE id_empresa = " . $company . " ORDER BY descricao ASC");
while ($linhaAmbiente = $consultaAmbiente->fetch(PDO::FETCH_ASSOC)) {

    $loopAmbiente .= '<option value="' . $linhaAmbiente['descricao'] . '">' . $linhaAmbiente['descricao'] . '</option>';
}
/*
$consultaAcabamento = $pdo->query("SELECT codigo,descricao,valor FROM acabamento ORDER BY descricao ASC");
while ($linhaAcabamento = $consultaAcabamento->fetch(PDO::FETCH_ASSOC)) {

    $acabamentos .= '<option value="' . mb_strtoupper($linhaAcabamento['descricao']) . '" >' . mb_strtoupper($linhaAcabamento['descricao']) . '</option>';
}
*/
// echo $id_orcamento;
?>
<style>
    .table-bordered td {
        vertical-align: middle;
    }

    .materiais th,
    .materiais td {
        padding: 0.25rem !important;
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
                        <a href="<?= $url ?>vendedor" class="text-muted">Orçamento</a>
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
            <a href="../orcamento/" class="btn btn-default font-weight-bold">Voltar</a>
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
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <div class="" id="orcamento_add" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="orcamento_add_form">
                                    <input type="hidden" name="type" value="orcamento_add" />
                                    <input type="hidden" name="complemento" value="" />
                                    <input type="hidden" name="id_orcamento" value="<?= $id_orcamento ?>" id="id_orcamento" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">

                                            <div class="checkbox-inline">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="sem">
                                                    <span></span>
                                                    Sem Cliente
                                                </label>

                                            </div>
                                            <span class="form-text text-muted">Selecione para Orçamentos Rápidos</span>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Orçamento:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="os" value="<?= $os ?>/<?= date('Y') ?>" placeholder="Orçamento" readonly tabindex="-1">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Data:</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y') ?>" disabled>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Prazo:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="prazo" placeholder="Prazo" value="20">
                                            <span class="form-text text-muted text-danger">Adicionar apenas Números</span>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Emissor:<span class="text-danger">*</span></label>

                                            <div class="input-group">
                                                <select class="form-control" name="emissor">
                                                    <option value="">...</option>
                                                    <?= $loopEmissor ?>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label>Vendedor:<span class="text-danger">*</span></label>

                                            <div class="input-group">
                                                <select class="form-control select2" id="vendedor" name="vendedor">
                                                    <option value="">...</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <a href="javascript:;" class="btn btn-light-success font-weight-bolder btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex;align-content: center;justify-content: center;border-top-right-radius: .5rem;align-items: center;border-bottom-right-radius: .5rem;"><i class="flaticon2-add"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-lg p-5">

                                                        <div class="form-group" style="margin-bottom: 1rem !important;">
                                                            <label>Nome do Vendedor</label>
                                                            <input type="text" id="nome_vendedor" class="form-control" placeholder="Fulano">
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-primary send-vendedor">Gravar</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4 comCliente">
                                            <label>Cliente:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="cliente" name="cliente">
                                                    <option value="">...</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <a href="javascript:;" class="btn btn-light-success font-weight-bolder btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex;align-content: center;justify-content: center;border-top-right-radius: .5rem;align-items: center;border-bottom-right-radius: .5rem;"><i class="flaticon2-add"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-lg p-5">

                                                        <div class="form-group" style="margin-bottom: 1rem !important;">
                                                            <label>Nome do Cliente</label>
                                                            <input type="text" id="nome_cliente" class="form-control" placeholder="Fulano">
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-primary send-cliente">Gravar</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 semCliente semClienteInput">
                                            <label>Cliente:</label>
                                            <input type="text" class="form-control" name="clientesem" placeholder="Cliente">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Obra:</label>
                                            <input type="text" class="form-control" name="obra" placeholder="Obra">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Endereço:</label>
                                            <input type="text" class="form-control" name="endereco" placeholder="Endereço">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4 semCliente">
                                            <label>E-mail Cliente:</label>
                                            <input type="text" class="form-control" name="email" placeholder="E-mail">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Tipo de Contato:</label>
                                            <input type="text" class="form-control" name="tipo_contato" placeholder="Tipo de Contato">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Contato:</label>
                                            <input type="text" class="form-control" name="contato" placeholder="Contato">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Telefone:</label>
                                            <input type="text" class="form-control celMask" name="telefone" placeholder="Telefone">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Instalação:</label>

                                            <div class="input-group">
                                                <select class="form-control" name="tempo">
                                                    <option value="N" selected>Noturna</option>
                                                    <option value="D">Diurna</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <!--begin::Button-->
                                    <div class="d-flex justify-content-end mb-3">
                                        <a href="#" class="btn btn-light-primary font-weight-bold mr-2 add-material" data-toggle="modal" data-target="#addMaterial">Adicionar Item</a>
                                        <a href="#" class="btn btn-light-success font-weight-bold mr-2 ColarMaterial" id="<?= $id_orcamento ?>">Colar Material</a>
                                    </div>
                                    <!--end::Button-->
                                    <div class="table-responsive-lg">

                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                                <tr class="table-active">
                                                    <th class="text-center" scope="col">Item</th>
                                                    <th scope="col">Descrição</th>
                                                    <!-- <th scope="col">Material</th> -->
                                                    <th scope="col">Quant.</th>
                                                    <th scope="col">Unid.</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Unitário.</th>
                                                    <th class="text-nowrap text-right" scope="col">Valor Total.</th>
                                                    <th class="text-right" style="width: 160px;">#&nbsp;&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody class="materiais">
                                                <tr>
                                                    <th class="text-center" colspan="7">Sem Materiais cadastrados</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <!-- <div class="form-group row">
                                        <div class="col-12 col-lg">
                                            <label>Acabamento:</label>
                                            <select class="form-control select2" id="kt_select2_1" name="acabamentoGeral[]" multiple="multiple">
                                                <? //$acabamentos 
                                                ?>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">
                                            <label>Obs Interna:</label>
                                            <textarea class="form-control" name="observacao" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Cond. Pagamento:</label>
                                            <input type="text" class="form-control" name="condicao" value="<?= $linha['condicao'] ?>" placeholder="Cond. Pagamento">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Desconto:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="desconto" placeholder="Desconto">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Frete:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="frete" placeholder="Frete">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Custos:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="custos" placeholder="Custos">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Acréscimo:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control qtdMask" name="acrescimo" placeholder="Acréscimo" value="">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Obs Frete:</label>
                                            <input type="text" class="form-control" name="obs_frete" value="" placeholder="Obs Frete">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Obs Custos:</label>
                                            <input type="text" class="form-control" name="obs_custos" value="" placeholder="Obs Custos">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Validade:</label>
                                            <input type="text" class="form-control" name="validade" placeholder="Orçamento com validade de X dias">
                                        </div>
                                    </div>
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between ">
                                        <div class="mr-2">

                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Gravar</button>
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
<!-- Modal Adicionar Item-->
<div class="modal fade" id="addMaterial" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="" id="materiais_add" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="materiais_add_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body p-5" style="height: 480px;">
                        <!-- <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 510px"> -->
                        <div class="row mb-0">
                            <div class="col-12">
                                <div class="" id="produtos" data-wizard-state="step-first" data-wizard-clickable="true">

                                    <!--begin::Wizard Body-->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-10 col-xxl-10">

                                            <input type="hidden" name="type" value="materiais_add" />
                                            <input type="hidden" name="id_material" value="" />
                                            <input type="hidden" name="id_orcamento" value="<?= $id_orcamento ?>" id="id_orcamento" />

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Ambiente:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <select class="form-control form-control-sm" name="ambiente">
                                                            <option value="">...</option>
                                                            <?= $loopAmbiente ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Material:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="material" placeholder="Material" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaProduto" data-toggle="modal" data-target="#listaMateriais"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control" name="descricao" rows="4"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 49.666667% !important;max-width: 49.666667% !important;"> Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9" style="flex: 0 0 50.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtdMask" placeholder="Quantidade" name="qtd">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <select class="form-control form-control-sm" name="unid">
                                                                <option value="">...</option>
                                                                <option value="m">m</option>
                                                                <option value="m2">m²</option>
                                                                <option value="Conjunto">Conjunto</option>
                                                                <option value="Peça">Peça</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="separator separator-dashed mt-0 mb-5"></div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">m²:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">

                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="m²" name="metro">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-expand icon-lg"></i>
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
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Material</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Material" name="valor" readonly tabindex="-1">
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
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Acabamento</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Acabamento" name="acabamento">
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
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Outros</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Outros" name="outros">
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
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Acréscimo:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="input-group">

                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="Acréscimo" name="acrescimo" value="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-percent icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Observação:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Observação" name="observacao">
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
                        <!-- </div> -->
                    </div>

                    <div class="modal-footer">
                        <!--begin::Actions-->
                        <button type="button" class="btn btn-success font-weight-bold action-hidden" data-wizard-type="action-submit">Gravar</button>
                        <!--end::Actions-->
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Fechar sem Incluir</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!--end::Container-->
<!-- Modal Editar Material-->
<div class="modal fade" id="EditMaterial" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="" id="materiais_edit" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="materiais_edit_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body p-5" style="height: 480px;">
                        <!-- <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 510px"> -->
                        <div class="row mb-0">
                            <div class="col-12">
                                <div class="" id="produtos" data-wizard-state="step-first" data-wizard-clickable="true">

                                    <!--begin::Wizard Body-->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-10 col-xxl-10">

                                            <input type="hidden" name="type" value="materiais_edit_edit" />
                                            <input type="hidden" name="idE" value="" />
                                            <input type="hidden" name="id_materialE" value="" />
                                            <input type="hidden" name="id_orcamento" value="<?= $id_orcamento ?>" id="id_orcamento" />

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Ambiente:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="ambienteE">
                                                                <option value="">...</option>
                                                                <?= $loopAmbiente ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Material:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="materialE" placeholder="Material" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaProduto" data-toggle="modal" data-target="#listaMateriais"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control copiarDescricao" name="descricaoE" rows="4"></textarea>
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon copiar" title="Copiar">
                                                            <i class="la la-clone"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 49.666667% !important;max-width: 49.666667% !important;"> Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9" style="flex: 0 0 50.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtdMask" placeholder="Quantidade" name="qtdE">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <select class="form-control form-control-sm" name="unidE">
                                                                <option value="">...</option>
                                                                <option value="m">m</option>
                                                                <option value="m2">m²</option>
                                                                <option value="Conjunto">Conjunto</option>
                                                                <option value="Peça">Peça</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="separator separator-dashed mt-0 mb-5"></div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">m²:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="m²" name="metroE">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-expand icon-lg"></i>
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
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Material</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Material" name="valorE" readonly tabindex="-1">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon atualizar" title="Atualizar">
                                                            <i class="la la-refresh"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Acabamento</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Acabamento" name="acabamentoE">
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
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Outros</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Outros" name="outrosE">
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
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Acréscimo:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="Acréscimo" name="acrescimoE">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-percent icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Observação:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Observação" name="observacaoE">
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
                        <!-- </div> -->
                    </div>

                    <div class="modal-footer">
                        <!--begin::Actions-->
                        <button type="button" class="btn btn-success font-weight-bold action-hidden" data-wizard-type="action-submit">Gravar</button>
                        <!--end::Actions-->
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Fechar sem Incluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Lista Materiais-->
<div class="modal fade" id="listaMateriais" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Materiais</h5>
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

<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/vendas/orcamento/add.js"></script>