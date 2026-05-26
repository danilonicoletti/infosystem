<?php

include __DIR__ . "/../../topo.php";

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM orcamentos WHERE id_orcamento = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$consultaTotal = $pdo->query("SELECT * FROM orcamentos WHERE os LIKE '" . $linha['os'] . "%'");
$total = $consultaTotal->rowCount();

$id_orcamento = $linha['id_orcamento'];

$consultaEmissor = $pdo->query("SELECT * FROM usuarios WHERE ativo = 1 AND empresa = " . $company . " ORDER BY nome ASC");
while ($linhaEmissor = $consultaEmissor->fetch(PDO::FETCH_ASSOC)) {

    if ($linhaEmissor['id'] == $linha['emissor']) {
        $selectedEmissor = 'selected';
    } else {
        $selectedEmissor = '';
    }

    $loopEmissor .= '<option value="' . $linhaEmissor['id'] . '" ' . $selectedEmissor . ' >' . $linhaEmissor['nome'] . '</option>';
}

$consultaVendedor = $pdo->query("SELECT * FROM vendedor WHERE id_vendedor = " . $linha['vendedor']);
$linhaVendedor = $consultaVendedor->fetch(PDO::FETCH_ASSOC);

$consultaCliente = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $linha['cliente']);
$linhaCliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);


// $consultaCondicao = $pdo->query("SELECT * FROM condicao_de_pagamento WHERE id_empresa = " . $company . " ORDER BY descricao ASC");
// while ($linhaCondicao = $consultaCondicao->fetch(PDO::FETCH_ASSOC)) {

//     if ($linhaCondicao['id_condicao'] == $linha['condicao']) {
//         $selectedCondicao = 'selected';
//     } else {
//         $selectedCondicao = '';
//     }
//     $loopCondicao .= '<option value="' . $linhaCondicao['id_condicao'] . '" ' . $selectedCondicao . '>' . $linhaCondicao['descricao'] . '</option>';
// }

$data = new DateTime($linha['data']);

$consultaProd = $pdo->query("SELECT id_o_produto, item, produto, coeficiente, moeda, t2.descricao as descProd, unid, qtd, metro, t1.valor, acabamento, outros, acrescimo, decrescimo, nome FROM orcamentos_produtos as t1 INNER JOIN materiais as t2 ON t1.produto = t2.id_material INNER JOIN tipos as t3 ON t1.tipo = t3.id_tipo WHERE id_orcamento = " . $id_orcamento . " ORDER BY id_o_produto ASC");
$total = $consultaProd->rowCount();
if ($total >= 1) {
    $item = 1;
    while ($linhaProd = $consultaProd->fetch(PDO::FETCH_ASSOC)) {

        // $arrayArea[] = $linhaProd['produto'] . "_" . $linhaProd['area'];

        $quantidade = $linhaProd['qtd'];
        $metro = $linhaProd['metro'];
        if ($linhaProd['moeda'] == '$') {
            $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'USD' AND data = '" . date('Y-m-d') . "'");
            $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

            $valorModea = $linhaProd['valor'] * $linhaMoeda['valor'];
            $valor = $valorModea * $linhaProd['coeficiente'];
        } else if ($linhaProd['moeda'] == '€') {
            $consultaMoeda = $pdo->query("SELECT valor FROM moedas WHERE moeda = 'EUR' AND data = '" . date('Y-m-d') . "'");
            $linhaMoeda = $consultaMoeda->fetch(PDO::FETCH_ASSOC);

            $valorModea = $linhaProd['valor'] * $linhaMoeda['valor'];
            $valor = $valorModea * $linhaProd['coeficiente'];
            $valor = $linhaProd['valor'] * $linhaProd['coeficiente'];
        } else {
            $valor = $linhaProd['valor'] * $linhaProd['coeficiente'];
        }

        $acabamento = $linhaProd['acabamento'];
        $outros = $linhaProd['outros'];

        if ($linhaProd['acrescimo'] != 0) {
            $mod_acr_dec = $linhaProd['acrescimo'] / 100;
        } elseif ($linhaProd['decrescimo'] != 0) {
            $mod_acr_dec = -$linhaProd['decrescimo'] / 100;
        } else {
            $mod_acr_dec = 0;
        }

        $material = ($metro * $quantidade) + ($metro * $quantidade * 0);

        $vl_unitario = (($metro * $valor) + $acabamento) + ($metro * $valor * 0) + ((($metro * $valor) + $acabamento) * $mod_acr_dec) + (($outros + ($outros * $mod_acr_dec)));

        $vl_total = $quantidade * $vl_unitario;

        if ($linhaProd['descProd'] == 'Sem Material') {
            $descProd = '';
        } else {
            $descProd = $linhaProd['descProd'];
        }
        if ($linhaProd['nome']  == 'Sem Tipo') {
            $nome = '';
        } else {
            $nome = $linhaProd['nome'];
        }

        $loopProd .= '<tr>
                        <td class="text-center">' .  $item . '</td>
                        <td>' . $descProd . '</td>
                        <td>' . $nome . '</td>
                        <td>' .  number_format($linhaProd['qtd'], 2, ",", "") . '</td>
                        <td>' . $linhaProd['unid'] . '</td>
                        <td class="text-nowrap">R$ ' . number_format($vl_unitario, 2, ",", ".") . '</td>
                        <td class="text-nowrap">R$ ' . number_format($vl_total, 2, ",", ".") . '</td>
                        <td>
                            <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon CopiarMaterial" title="Copiar">
                                <i class="la la-file-text-o"></i>
                            </a>
                            <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon DuplicarMaterial" title="Duplicar">
                                <i class="la la-clone"></i>
                            </a>
                            <a href="javascript:;" id="' . $linhaProd['id_o_produto'] . '" class="btn btn-sm btn-clean btn-icon EditMaterial" title="Editar">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon RemoverMaterial" title="Remover" id="' . $linhaProd['id_o_produto'] . '">
                                <i class="la la-trash"></i>
                            </a>
                        </td>
                    </tr>';

        $item++;
    }
} else {
    $loopProd = '<tr>
                    <th class="text-center" colspan="8">Sem Materiais cadastrados</th>
                </tr>';
}

?>
<style>
    .table-bordered td {
        vertical-align: middle;
    }

    .col-1 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 8.3333333333%;
        flex: 0 0 11.333333%;
        max-width: 11.333333%;
    }

    .col-11 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 91.6666666667%;
        flex: 0 0 87.666667%;
        max-width: 87.666667%;
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
                        <a href="<?= $url ?>orcamento" class="text-muted">Orçamento</a>
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
            <a href="../orcamento/pdf?id=<?= $id ?>" target="_blank" class="btn btn-primary font-weight-bold">Imprimir</a>
            <!--end::Button-->
            <!--begin::Button-->
            <a href="../orcamento/pdf-sem-valor?id=<?= $id ?>" target="_blank" class="btn btn-primary font-weight-bold ml-2">Imprimir sem Valor</a>
            <!--end::Button-->
            <!--begin::Button-->
            <a href="../orcamento/pdf-material?id=<?= $id ?>" target="_blank" class="btn btn-primary font-weight-bold ml-2">Imprimir com Material</a>
            <!--end::Button-->
            <!--begin::Button-->
            <a href="../orcamento/" class="btn btn-default font-weight-bold ml-2">Voltar</a>
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
                    <div class="" id="orcamento_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="orcamento_edit_form">
                                    <input type="hidden" name="type" value="orcamento_edit" />
                                    <input name="id" type="hidden" value="<?= $id ?>" />
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">

                                            <div class="checkbox-inline">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="sem" <?= $linha['sem'] == 1 ? 'checked' : '' ?>>
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
                                            <input type="text" class="form-control" name="os" value="<?= $linha['os'] ?>" placeholder="Orçamento" disabled>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Data:</label>
                                            <input type="text" class="form-control" value="<?= $data->format('d/m/Y') ?>" disabled>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Prazo:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="prazo" value="<?= $linha['prazo'] ?>" placeholder="Prazo">
                                            <span class="form-text text-muted text-danger">Adicionar apenas Números</span>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Emissor:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="emissor">
                                                <option value="">...</option>
                                                <?= $loopEmissor ?>
                                            </select>
                                        </div>

                                        <div class="col-12 col-lg-3">
                                            <label>Vendedor:<span class="text-danger">*</span></label>

                                            <input type="hidden" id="id_vendedor" value="<?= $linhaVendedor['id_vendedor'] ?>">
                                            <input type="hidden" id="nome_vendedor" value="<?= $linhaVendedor['nome'] ?>">

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

                                            <input type="hidden" id="id_cliente" value="<?= $linhaCliente['id_cliente'] ?>">
                                            <input type="hidden" id="nome_cliente" value="<?= $linhaCliente['nomes'] ?>">

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
                                            <input type="text" class="form-control" name="clientesem" value="<?= $linhaCliente['nome'] ?>" placeholder="Cliente">
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label>Obra:</label>
                                            <input type="text" class="form-control" name="obra" value="<?= $linha['obra'] ?>" placeholder="Obra">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Endereço:</label>
                                            <input type="text" class="form-control" name="endereco" value="<?= $linha['endereco'] ?>" placeholder="Endereço">
                                        </div>

                                    </div>
                                    <div class="form-group row semCliente">

                                        <div class="col-12 col-lg-2">
                                            <label>Telefone:</label>
                                            <input type="text" class="form-control celMask" name="telefone" value="<?= $linha['telefone'] ?>" placeholder="Telefone" readonly>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label>E-mail:</label>
                                            <input type="text" class="form-control" name="email" value="<?= $linha['email'] ?>" placeholder="E-mail" readonly>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Validade:</label>
                                            <input type="text" class="form-control" name="validade" value="<?= $linha['validade'] ?>" placeholder="Orçamento com validade de X dias">
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <!--begin::Button-->
                                    <div class="d-flex justify-content-end mb-3">
                                        <a href="#" class="btn btn-light-primary font-weight-bold mr-2 add-material" data-toggle="modal" data-target="#addMaterial">Adicionar Material</a>
                                        <a href="#" class="btn btn-light-success font-weight-bold mr-2 ColarMaterial" id="<?= $id ?>">Colar Material</a>
                                    </div>
                                    <!--end::Button-->
                                    <div class="table-responsive-lg">

                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                                <tr class="table-active">
                                                    <th class="text-center" scope="col">Item</th>
                                                    <th scope="col">Material</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Quant.</th>
                                                    <th scope="col">Unid.</th>
                                                    <th class="text-nowrap" scope="col">Valor Unitário.</th>
                                                    <th class="text-nowrap" scope="col">Valor Total.</th>
                                                    <th style="width: 170px;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody class="materiais">
                                                <?= $loopProd ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">
                                            <label>Observações:</label>
                                            <textarea class="form-control" name="observacao" rows="3"><?= $linha['observacao'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-3">
                                            <label>Cond. Pagamento:</label>
                                            <input type="text" class="form-control" name="condicao" value="<?= $linha['condicao'] ?>" placeholder="Cond. Pagamento">

                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Desconto:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control moneyMask" name="desconto" value="<?= number_format($linha['desconto'], 2, ",", ".") ?>" placeholder="Desconto">
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
                                                <input type="text" class="form-control moneyMask" name="frete" value="<?= number_format($linha['frete'], 2, ",", ".") ?>" placeholder="Frete">
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
                                                <input type="text" class="form-control moneyMask" name="custos" value="<?= number_format($linha['custos'], 2, ",", ".") ?>" placeholder="Custos">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-dollar-sign icon-lg"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between row">
                                        <div class="col-11 text-right">

                                            <div class="checkbox-list pt-4 float-right">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="salvar_novo">
                                                    <span></span>
                                                    Salvar Orçamento como <b class="ml-1">(<?= $linha['os'] ?>-<?= $total ?>)</b>
                                                </label>

                                            </div>
                                            <input type="hidden" name="os_novo" value="<?= $linha['os'] ?>-<?= $total ?>" />
                                        </div>
                                        <div class="col-1 text-right">

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
<!-- Modal Adicionar Material-->
<div class="modal fade" id="addMaterial" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="" id="materiais_add" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="materiais_add_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Material</h5>
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

                                            <input type="hidden" name="type" value="materiais_add_edit" />
                                            <input type="hidden" name="id_material" value="" />
                                            <input type="hidden" name="id_tipo" value="" />
                                            <input type="hidden" name="coeficiente" value="" />
                                            <input type="hidden" name="id_orcamento" value="<?= $id_orcamento ?>" id="id_orcamento" />

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Material:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="material" placeholder="Material" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href=" #" class="listaMaterial" data-toggle="modal" data-target="#listaMateriais"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Tipo:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="tipo" placeholder="Tipo" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href=" #" class="listaTipo" data-toggle="modal" data-target="#listaTipos"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Ambiente:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="area">
                                                                <option value="">...</option>
                                                                <option value="Cozinha">Cozinha</option>
                                                                <option value="Servico">Serviço</option>
                                                                <option value="Banheiro">Banheiro</option>
                                                                <option value="Churrasqueira">Churrasqueira</option>
                                                                <option value="Lavabo">Lavabo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control copiarDescricao" name="descricao" rows="3"></textarea>
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon copiar" title="Copiar" onclick="copiarTexto()">
                                                            <i class="la la-clone"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 49.666667% !important;max-width: 49.666667% !important;"> Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9" style="flex: 0 0 50.333333% !important;">
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
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm qtdMask" placeholder="Quantidade" name="qtd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed mt-0 mb-5"></div>
                                            <div class=" row">
                                                <div class="col-7">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Largura:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtd3Mask largura" placeholder="Largura" name="largura">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Altura:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm qtd3Mask altura" placeholder="Altura" name="altura">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Material</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Material" name="valor" readonly tabindex="999999999">
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
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">m²:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm qtd3Mask" placeholder="m²" name="metro" readonly>
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

                                            <div class=" row">
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

                                            <div class=" row">
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

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Acréscimo:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="input-group">

                                                                <input type="number" class="form-control form-control-sm" placeholder="Acréscimo" name="acrescimo" min="0" max="100">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-percent icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Decréscimo:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <div class="input-group">

                                                                <input type="number" class="form-control form-control-sm" placeholder="Decréscimo" name="decrescimo" min="0" max="100">
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
                                            <input type="hidden" name="id_tipoE" value="" />
                                            <input type="hidden" name="coeficienteE" value="" />
                                            <input type="hidden" name="id_orcamento" value="<?= $id_orcamento ?>" id="id_orcamento" />

                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Material:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="materialE" placeholder="Material" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaMaterial" data-toggle="modal" data-target="#listaMateriais"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-5">
                                                <label class="col-form-label col-xl-3 col-lg-3">Tipo:<span class="text-danger">*</span></label>
                                                <div class="col-xl-9 col-lg-9">
                                                    <div class="input-icon input-icon-right">
                                                        <input type="text" class="form-control form-control-sm" name="tipoE" placeholder="Tipos" autocomplete="INFOSYSTEM" readonly>
                                                        <span>
                                                            <a href="#" class="listaTipo" data-toggle="modal" data-target="#listaTipos"><i class="flaticon2-search-1 icon-md"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Ambiente:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control form-control-sm" name="areaE">
                                                                <option value="">...</option>
                                                                <option value="Cozinha">Cozinha</option>
                                                                <option value="Servico">Serviço</option>
                                                                <option value="Banheiro">Banheiro</option>
                                                                <option value="Churrasqueira">Churrasqueira</option>
                                                                <option value="Lavabo">Lavabo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Descrição:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control copiarDescricao" name="descricaoE" rows="3"></textarea>
                                                        </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon copiar" title="Copiar">
                                                            <i class="la la-clone"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 49.666667% !important;max-width: 49.666667% !important;"> Unidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9" style="flex: 0 0 50.333333% !important;">
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
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Quantidade:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <input type="text" class="form-control form-control-sm qtdMask" placeholder="Quantidade" name="qtdE">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed mt-0 mb-5"></div>
                                            <div class=" row">
                                                <div class="col-7">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Largura:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">
                                                            <input type="text" class="form-control form-control-sm qtd3Mask larguraE" placeholder="Largura" name="larguraE">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Altura:<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control form-control-sm qtd3Mask alturaE" placeholder="Altura" name="alturaE">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class=" row">
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">Vl. Material</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control form-control-sm moneyMask" placeholder="Vl. Material" name="valorE" readonly tabindex="999999999">
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
                                                <div class="col-xl-12">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-3 col-lg-3">m²:</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">

                                                                <input type="text" class="form-control form-control-sm qtd3Mask" placeholder="m²" name="metroE" readonly>
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

                                            <div class=" row">
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

                                            <div class=" row">
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

                                            <div class=" row">
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-6 col-lg-6">Acréscimo:</label>
                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="input-group">

                                                                <input type="number" class="form-control form-control-sm" placeholder="Acréscimo" name="acrescimoE" min="0" max="100">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-percent icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mb-5">
                                                        <label class="col-form-label col-xl-5 col-lg-5">Decréscimo:</label>
                                                        <div class="col-lg-7 col-xl-7">
                                                            <div class="input-group">

                                                                <input type="number" class="form-control form-control-sm" placeholder="Decréscimo" name="decrescimoE" min="0" max="100">
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
<!-- Modal Lista Produtos-->
<div class="modal fade" id="listaMateriais" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Materiais</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="400">
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
<!-- Modal Lista Tipo-->
<div class="modal fade" id="listaTipos" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Tipos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="400">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>Código:</label>
                            <input type="text" class="form-control datatable-input-tipos codigo_search" placeholder="Código" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Nome:</label>
                            <input type="text" class="form-control datatable-input-Tipos nome_search" placeholder="Nome" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_tipos">
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
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_tipos">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
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

<script src="<?= $url ?>assets/js/vendas/orcamento/edit.js"></script>