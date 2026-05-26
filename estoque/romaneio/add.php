<?php

include __DIR__ . "/../../topo.php";

$consulta = $pdo->query("SELECT id_romaneio, entrada FROM romaneio WHERE id_empresa = " . $company . " ORDER BY id_romaneio DESC LIMIT 0,1");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

if ($linha['id_romaneio'] == "") {
    $id_romaneio = 1;
    $entrada = 1334;
} else {
    $id_romaneio = $linha['id_romaneio'] + 1;
    $entrada = $linha['entrada'] + 1;
}

$stmt = $pdo->prepare("DELETE FROM romaneio_produtos WHERE temp = 1 AND id_romaneio = " . $id_romaneio);
$stmt->execute();

?>
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
                        <a href="<?= $url ?>romaneio" class="text-muted">Romaneio</a>
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
            <a href="../romaneio/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="romaneio_add" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="romaneio_add_form">
                                    <input name="type" type="hidden" value="romaneio_add" />
                                    <input type="hidden" name="id_romaneio" value="<?= $id_romaneio ?>" id="id_romaneio" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Nº Entrada:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="entrada" placeholder="Nº Entrada" value="<?= $entrada ?>">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Nota Fiscal:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nota_fiscal" placeholder="Nota Fiscal">
                                        </div>

                                        <div class="col-12 col-lg-2">
                                            <label>Data:<span class="text-danger">*</span></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control form-control-solid dateMask" placeholder="Selecione a Data" name="dt_emissao" value="<?= date('d/m/Y') ?>" id="dt_emissao" required tabindex="4" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: none;">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-5">
                                            <label>Fornecedor:<span class="text-danger">*</span></label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control form-control-sm" name="fornecedor" placeholder="Fornecedor" autocomplete="INFOSYSTEM" readonly>
                                                <span>
                                                    <a href=" #" class="listaFornecedor" data-toggle="modal" data-target="#listaFornecedor"><i class="flaticon2-search-1 icon-md"></i></a>
                                                </span>
                                            </div>
                                            <input type="hidden" name="id_fornecedor">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Comissão:</label>
                                            <input type="text" class="form-control money2Mask" name="comissao" value="" placeholder="Comissão">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Frete:</label>
                                            <input type="text" class="form-control money2Mask" name="frete" value="" placeholder="Frete">
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Observação:</label>
                                            <input type="text" class="form-control" name="observacao" placeholder="Observação">
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
                                            <thead style="background: #9acfea;">
                                                <tr>
                                                    <th class="text-center" scope="col">Item</th>
                                                    <th scope="col">Descrição</th>
                                                    <th scope="col">Medidas</th>
                                                    <th scope="col">Metro</th>
                                                    <th scope="col">Chapas</th>
                                                    <th class="text-nowrap" scope="col">Custo Pedra.</th>
                                                    <th style="width: 90px;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody class="produtos">
                                                <tr>
                                                    <th class="text-center" colspan="7">Sem produtos cadastrados</th>
                                                </tr>
                                            </tbody>
                                        </table>
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

<!--begin::Footer-->
<!--end::Content-->
<!-- Modal Lista Fornecedor-->
<div class="modal fade" id="listaFornecedor" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Fornecedores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="490">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>Código:</label>
                            <input type="text" class="form-control datatable-input-fornecedores codigo_search" placeholder="Código" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Nome:</label>
                            <input type="text" class="form-control datatable-input-fornecedores descricao_search" placeholder="Nome" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_fornecedores">
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
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_fornecedores">
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
<!-- Modal Adicionar Produto-->
<div class="modal fade" id="addProduto" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="" id="produtos_add" data-wizard-state="step-first" data-wizard-clickable="true">
                <form class="form" id="produtos_add_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body p-5">
                        <div data-scroll="true" data-height="500">
                            <div class="row mb-0">
                                <div class="col-12">
                                    <div class="" id="produtos" data-wizard-state="step-first" data-wizard-clickable="true">

                                        <!--begin::Wizard Body-->
                                        <div class="row justify-content-center">
                                            <div class="col-xl-10 col-xxl-10">

                                                <input type="hidden" name="type" value="produtos_add" />
                                                <input type="hidden" name="id_produto" value="" />
                                                <input type="hidden" name="id_romaneio" value="<?= $id_romaneio ?>" id="id_romaneio" />

                                                <div class="form-group row mb-5">
                                                    <label class="col-form-label col-xl-3 col-lg-3">Produto:<span class="text-danger">*</span></label>
                                                    <div class="col-xl-9 col-lg-9">
                                                        <div class="input-icon input-icon-right">
                                                            <input type="text" class="form-control form-control-sm" name="produto" placeholder="Produto" autocomplete="INFOSYSTEM" readonly>
                                                            <span>
                                                                <a href=" #" class="listaProduto" data-toggle="modal" data-target="#listaProdutos">
                                                                    <i class="flaticon2-search-1 icon-md"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Espessura:<span class="text-danger">*</span></label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" class="form-control form-control-sm" placeholder="Espessura" name="espessura" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-7">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-5 col-lg-5" style="flex: 0 0 42.666667% !important;max-width: 42.666667% !important;">Chapas:<span class="text-danger">*</span></label>
                                                            <div class="col-lg-7 col-xl-7" style="flex: 0 0 57.333333% !important;">
                                                                <input type="number" class="form-control form-control-sm" placeholder="Chapas" name="chapas">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-5">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Unidade:</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select class="form-control form-control-sm" name="unidade">
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

                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Comprimento:<span class="text-danger">*</span></label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="Comprimento" name="comprimento">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Altura:<span class="text-danger">*</span></label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" class="form-control form-control-sm qtdMask" placeholder="Altura" name="altura">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Metro ²:</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" class="form-control form-control-sm qtd3Mask" placeholder="Metro ²" name="metro" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Moeda:</label>
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
                                                        <div class="form-group row mb-5">
                                                            <label class="col-form-label col-xl-3 col-lg-3">Custo Pedra:<span class="text-danger">*</span></label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control form-control-sm money2Mask" placeholder="Custo Pedra" name="custo">
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
<div class="modal fade" id="listaProdutos" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Produtos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="490">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>Código:</label>
                            <input type="text" class="form-control datatable-input-produtos codigo_search" placeholder="Código" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Descrição:</label>
                            <input type="text" class="form-control datatable-input-produtos descricao_search" placeholder="Descrição" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_produtos">
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
                    <table class="table table-sm table-bordered table-hover table-checkable table-striped display" id="kt_datatable_produtos">
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

<!--end::Container-->
<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/estoque/romaneio/add.js"></script>