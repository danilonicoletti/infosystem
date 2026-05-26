<?php

include __DIR__ . "/../topo.php";

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$id_nfe = $linha['id_nfe'];

$consultaEmissor = $pdo->query("SELECT * FROM usuarios WHERE id = " . $linha['emissor']);
$linhaEmissor = $consultaEmissor->fetch(PDO::FETCH_ASSOC);

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['destinatario']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);

if ($linhaCliente['pessoa'] == 'fisica') {
    $cliente = $linhaCliente['nome'] . " " . $linhaCliente['nomes'];
} else {
    $cliente = $linhaCliente['nomes'];
}

$consultaCFOP = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $linha['id_cfop']);
$linhaCFOP = $consultaCFOP->fetch(PDO::FETCH_ASSOC);

$data = new DateTime($linha['data_emissao']);

// $i = 1;
$consultaProd = $pdo->query("SELECT id_produto, t1.codigo, t1.descricao as descricao, qtd, unid, valor, vl_ipi, p_icms, ncm FROM nfe_produtos as t1  INNER JOIN ncm as t3 ON t1.id_ncm = t3.id_ncm WHERE id_nfe = " . $id_nfe . " ORDER BY id_produto ASC");
$total = $consultaProd->rowCount();
if ($total >= 1) {
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        $quantidade = $linhaProd['qtd'];
        $valor = $linhaProd['valor'];

        $vl_total = $valor * $quantidade;
        $vl_total_ipi = $vl_total + $linhaProd['vl_ipi'];

        if ($linhaProd['p_icms'] != 0) {
            $vl_total_icms = ($vl_total / 100) * $linhaProd['p_icms'];
        }

        $loopProd .= '<tr>
                        <td class="text-center">' . $linhaProd['codigo'] . '</td>
                        <td>' . $linhaProd['descricao'] . '</td>
                        <td>' .  number_format($quantidade, 3, ",", "") . '</td>
                        <td>' . $linhaProd['unid'] . '</td>
                        <td>' . $linhaProd['ncm'] . '</td>
                        <td class="text-nowrap">R$ ' . number_format($valor, 2, ",", ".") . '</td>
                        <td class="text-nowrap">R$ ' . number_format($vl_total_ipi, 2, ",", ".") . '</td>
                        <td>
                            <a href="javascript:;" id="' . $linhaProd['id_produto'] . '" class="btn btn-sm btn-clean btn-icon EditProduto" title="Editar">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon remover-produto" title="Remover" id="' . $linhaProd['id_produto'] . '">
                                <i class="la la-trash"></i>
                            </a>
                        </td>
                    </tr>';

        $i++;

        $total_ipi += $linhaProd['vl_ipi'];
        $total_icms += $vl_total_icms;
        $total_produtos += $vl_total_ipi;
        $total_total += $vl_total + $total_ipi;
    }
} else {

    $vl_total = 0;
    $total_ipi = 0;
    $total_icms = 0;
    $total_produtos = 0;
    $total_total = 0;

    $loopProd = '<tr>
                <th class="text-center" colspan="9">Sem produtos cadastrados</th>
            </tr>';
}

if($linha['status'] != '0'){
    $disabled = 'disabled';
    $readonly = 'readonly';
}else{
    $disabled = '';
    $readonly = '';
}

?>
<style>
    .table-bordered td {
        vertical-align: middle;
    }

    .modal-footer h3 {
        letter-spacing: -1px;
        font-weight: 600;
    }

    .display-st {
        display: none;
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
                        <a href="<?= $url ?>nota-fiscal" class="text-muted">Notas Fiscais</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted">Editar</a>
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
            <a href="../nota-fiscal/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="nota_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="nota_edit_form">
                                     <input type="hidden" name="type" value="nota_edit" /> 
                                     <input type="hidden" name="id_nota"  value="<?= $id ?>" /> 
                                     <input type="hidden" name="id_cfop" value="<?= $linha['id_cfop']?>" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">
                                            <h2><b>Nota Fiscal Nº <?= $linha['nota_fiscal'] ?></b></h2>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="col-12 col-lg-2">
                                            <label>Data:<span class="text-danger">*</span></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control form-control-solid dateMask" placeholder="Selecione a Data" name="dt_emissao" value="<?= $data->format('d/m/Y') ?>" id="dt_emissao" required tabindex="4" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: none;">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Emissor:</label>
                                            <input type="text" class="form-control" name="emissor" value="<?= $linhaEmissor['nome'] ?>" placeholder="Emissor" disabled>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label>Cliente:</label>
                                            <input type="text" class="form-control" name="cliente" value="<?= $cliente ?>" placeholder="Cliente" disabled>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Frete:<span class="text-danger">*</span></label>

                                            <div class="input-group">
                                                <select class="form-control" id="frete" name="frete" disabled>
                                                    <option value="">...</option>
                                                    <option value="0" <?= $linha['frete'] == '0' ? 'selected' : '' ?>>Emitente</option>
                                                    <option value="1" <?= $linha['frete'] == '1' ? 'selected' : '' ?>>Destinatário</option>
                                                    <option value="2" <?= $linha['frete'] == '2' ? 'selected' : '' ?>>Terceiros</option>
                                                    <option value="9" <?= $linha['frete'] == '9' ? 'selected' : '' ?>>Sem Transporte</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>CFOP:<span class="text-danger">*</span></label>


                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control" name="cfop" value="<?= $linhaCFOP['descricao'] ?>"  placeholder="CFOP" autocomplete="INFOSYSTEM" <?= $disabled ?>>
                                                <span>
                                                    <a href="#" class="listaCFOP" data-toggle="modal" data-target="#listaCFOP"><i class="flaticon2-search-1 icon-md"></i></a>
                                                </span>
                                            </div>


                                        </div>
                                        
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>
                                    <!--begin::Button-->
                                    <div class="d-flex justify-content-end mb-3">
                                        <a href="#" class="btn btn-light-primary font-weight-bold mr-2 add-produto" data-toggle="modal" data-target="#addProduto">Adicionar Produto</a>
                                    </div>
                                    <!--end::Button-->
                                    <div class="table-responsive-lg">

                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                                <tr class="table-active">
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th scope="col">Descrição</th>
                                                    <th scope="col">Quant.</th>
                                                    <th scope="col">Unid.</th>
                                                    <th scope="col">NCM</th>
                                                    <th class="text-nowrap" scope="col">Valor Unitário.</th>
                                                    <th class="text-nowrap" scope="col">Valor Total.</th>
                                                    <th style="width: 90px;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody class="produtos">
                                                <?= $loopProd ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex align-items-center flex-wrap produtos-total">

                                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1 ml-15">
                                            <span class="mr-4">
                                                <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                            </span>
                                            <div class="d-flex flex-column text-dark-75">
                                                <span class="font-weight-bolder font-size-sm">Base de Calculo ICMS</span>
                                                <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span><?= number_format($total_produtos, 2, ",", ".") ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                            <span class="mr-4">
                                                <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                            </span>
                                            <div class="d-flex flex-column text-dark-75">
                                                <span class="font-weight-bolder font-size-sm">Total ICMS</span>
                                                <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span><?= number_format($total_icms, 2, ",", ".") ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                            <span class="mr-4">
                                                <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                            </span>
                                            <div class="d-flex flex-column text-dark-75">
                                                <span class="font-weight-bolder font-size-sm">Total IPI</span>
                                                <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span><?= number_format($total_ipi, 2, ",", ".") ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                            <span class="mr-4">
                                                <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                            </span>
                                            <div class="d-flex flex-column text-dark-75">
                                                <span class="font-weight-bolder font-size-sm">Total dos Produtos</span>
                                                <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span><?= number_format($total_produtos, 2, ",", ".") ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                            <span class="mr-4">
                                                <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                            </span>
                                            <div class="d-flex flex-column text-dark-75">
                                                <span class="font-weight-bolder font-size-sm">Total da Nota</span>
                                                <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">R$ </span><?= number_format($total_total, 2, ",", ".") ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-7"></div>

                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                            <label>Informações Adicionais:<span class="text-danger">*</span></label>

                                            <input type="text" name="infcpl" class="form-control form-control-sm" value="<?= $linha['infcpl'] ?> " <?= $readonly ?> >

                                        </div>
                                    </div>

                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between mt-5">
                                        <div class="mr-2">

                                        </div>
                                         <div>
                                        <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Gravar</button>
                                        <? if (file_exists(__DIR__ . '/assinadas/' . $linha['chave_acesso'] . '.xml')) { ?>
                                           
                                                <a href="mostrar-nfe?id=<?= $id ?>" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" title="Imprimir Nota" target="_blank">Imprimir NFe</a>
                                            
                                        <? } ?>
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
<!-- Modal Adicionar Produto-->
<div class="modal fade" id="addProduto" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="" id="produtos_add" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="produtos_add_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body p-5" style="height: 510px;">
                        <!-- <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 510px"> -->
                        <div class="row mb-0">
                            <div class="col-12">
                                <div class="" id="produtos" data-wizard-state="step-first" data-wizard-clickable="true">

                                    <!--begin::Wizard Body-->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-10 col-xxl-10">

                                            <input type="hidden" name="type" value="produtos_add_edit" />
                                            <input type="hidden" name="id_ncm" value="" />
                                            <input type="hidden" name="id_nfe" value="<?= $id_nfe ?>" id="id_nfe" />

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Código:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Código" name="codigo" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">NCM:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="ncm" placeholder="NCM" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaNCM" data-toggle="modal" data-target="#listaNCM"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Descrição" name="descricao">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">CST/CSON:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="cstcson">
                                                                <option value="">...</option>
                                                                <option value="101">101 - Tributada pelo Simples Nacional com permissão de crédito</option>
                                                                <option value="102">102 - Tributada pelo Simples Nacional sem permissão de crédito</option>
                                                                <option value="103">103 - Isenção do ICMS no Simples Nacional para faixa de receita bruta</option>
                                                                <option value="201">201 - Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="202">202 - Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="203">203 - Isenção do ICMS no Simples Nacional para faixa de receita bruta e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="300">300 - Imune</option>
                                                                <option value="400">400 - Não tributada pelo Simples Nacional</option>
                                                                <option value="500">500 - ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação</option>
                                                                <option value="900">900 - Outros</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-7">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtd4Mask" placeholder="Quantidade" name="qtd">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-5">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="unid">
                                                                <option value="">...</option>
                                                                <option value="m">m</option>
                                                                <option value="m2">m²</option>
                                                                <option value="CJ">Conjunto</option>
                                                                <option value="PC">Peça</option>
                                                                <option value="L">Litros</option>
                                                                <option value="CH">Chapa</option>
                                                                <option value="UN">Unidade</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Origem:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="orig">
                                                                <option value="">...</option>
                                                                <option value="0">Nacional</option>
                                                                <option value="1">Importado</option>
                                                                <option value="2">Adq. Mercado Interno</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Valor:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">

                                                                <input type="text" class="form-control form-control-sm money4Mask" placeholder="Valor" name="valor">
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

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">% ICMS:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <input type="text" class="form-control form-control-sm percentMask" placeholder="% ICMS" name="p_icms">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">% IPI:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm percentMask" placeholder="% IPI" name="p_ipi">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Enq IPI:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Enq IPI" name="enq_ipi">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Vl IPI:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm money4Mask" placeholder="Vl IPI" name="vl_ipi">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" row display-st">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST ICMS: </span><span class="txt_st_icms"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST PIS/COFINS: </span><span class="txt_st_pis_confins"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST IPI: </span><span class="txt_st_ipi"></span>
                                                        </label>

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
                        <h3 class="display-total mr-40"> </h3>
                        <input type="hidden" name="total">
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

<!-- Modal Editar Produtos-->
<div class="modal fade" id="EditProduto" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="" id="produtos_edit" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="produtos_edit_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body p-5" style="height: 510px;">
                        <!-- <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 510px"> -->
                        <div class="row mb-0">
                            <div class="col-12">
                                <div class="" id="produtos" data-wizard-state="step-first" data-wizard-clickable="true">

                                    <!--begin::Wizard Body-->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-10 col-xxl-10">

                                            <input type="hidden" name="type" value="produtos_edit" />
                                            <input type="hidden" name="id_produtoE" value="" />
                                            <input type="hidden" name="id_nfe" value="<?= $id_nfe ?>" id="id_nfe" />

                                            <input type="hidden" name="id_ncm" value="" />

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Código:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Código" name="codigo" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">NCM:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="ncm" placeholder="NCM" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaNCM" data-toggle="modal" data-target="#listaNCM"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Descrição" name="descricao">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">CST/CSON:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="cstcson">
                                                                <option value="">...</option>
                                                                <option value="101">101 - Tributada pelo Simples Nacional com permissão de crédito</option>
                                                                <option value="102">102 - Tributada pelo Simples Nacional sem permissão de crédito</option>
                                                                <option value="103">103 - Isenção do ICMS no Simples Nacional para faixa de receita bruta</option>
                                                                <option value="201">201 - Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="202">202 - Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="203">203 - Isenção do ICMS no Simples Nacional para faixa de receita bruta e com cobrança do ICMS pro substituição tributária</option>
                                                                <option value="300">300 - Imune</option>
                                                                <option value="400">400 - Não tributada pelo Simples Nacional</option>
                                                                <option value="500">500 - ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação</option>
                                                                <option value="900">900 - Outros</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-7">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtd4Mask" placeholder="Quantidade" name="qtd">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-5">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="unid">
                                                                <option value="">...</option>
                                                                <option value="m">m</option>
                                                                <option value="m2">m²</option>
                                                                <option value="CJ">Conjunto</option>
                                                                <option value="PC">Peça</option>
                                                                <option value="L">Litros</option>
                                                                <option value="CH">Chapa</option>
                                                                <option value="UN">Unidade</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Origem:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="orig">
                                                                <option value="">...</option>
                                                                <option value="0">Nacional</option>
                                                                <option value="1">Importado</option>
                                                                <option value="2">Adq. Mercado Interno</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Valor:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">

                                                                <input type="text" class="form-control form-control-sm money4Mask" placeholder="Valor" name="valor">
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

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">% ICMS:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <input type="text" class="form-control form-control-sm percentMask" placeholder="% ICMS" name="p_icms">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">% IPI:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm percentMask" placeholder="% IPI" name="p_ipi">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Enq IPI:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Enq IPI" name="enq_ipi">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Vl IPI:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm money4Mask" placeholder="Vl IPI" name="vl_ipi">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" row display-st">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST ICMS: </span><span class="txt_st_icms"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST PIS/COFINS: </span><span class="txt_st_pis_confins"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-form-label col-xl-12 col-lg-12">
                                                            <span class=" font-weight-bold">ST IPI: </span><span class="txt_st_ipi"></span>
                                                        </label>

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

<!-- Modal Lista NCM-->
<div class="modal fade" id="listaNCM" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de NCMs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="400">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>NCM:</label>
                            <input type="text" class="form-control datatable-input-ncms ncm_search" placeholder="NCM" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Descrição:</label>
                            <input type="text" class="form-control datatable-input-ncms descricao_search" placeholder="Descrição" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_ncms">
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
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_ncms">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>NCM</th>
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

<!-- Modal Lista CFOP-->
<div class="modal fade" id="listaCFOP" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de CFOPs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="400">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>CFOP:</label>
                            <input type="text" class="form-control datatable-input-cfops cfop_search" placeholder="CFOP" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Descrição:</label>
                            <input type="text" class="form-control datatable-input-cfops descricao_search" placeholder="Descrição" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_cfops">
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
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_cfops">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>CFOP</th>
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

<!--end::Content-->
<div id="theModal" class="modal fade text-center">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<!--begin::Footer-->
<?php include  __DIR__ . "/../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/nota-fiscal/edit.js"></script>