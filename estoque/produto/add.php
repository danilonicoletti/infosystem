<?php

include __DIR__ . "/../../topo.php";

$consultaFornecedor = $pdo->query("SELECT * FROM fornecedor WHERE id_empresa = " . $company . " ORDER BY nome ASC");
while ($linhaFornecedor = $consultaFornecedor->fetch(PDO::FETCH_ASSOC)) {
    $loopFornecedor .= '<option value="' . $linhaFornecedor['id_fornecedor'] . '">' . $linhaFornecedor['nome'] . '</option>';
}

?>
<style>
    #kt_datatable_ncm td {
        padding: 0.5rem 0.5rem !important;
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
                        <a href="<?= $url ?>produto" class="text-muted">Produto</a>
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
            <a href="../produto/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="produto_add" data-wizard-state="step-first" data-wizard-clickable="true">
                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="produto_add_form">
                                    <input name="type" type="hidden" value="produto_add" />
                                    <div class="form-group row">
                                        <!-- <div class="col-12 col-lg-2">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" placeholder="Código" value="<?= str_pad($codigo, 5, '0', STR_PAD_LEFT) ?>" readonly>
                                        </div> -->
                                        <div class="col-12 col-lg-4">
                                            <label>Fornecedor:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="fornecedor">
                                                <option value="">...</option>
                                                <?= $loopFornecedor ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Descrição:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="descricao" placeholder="Descrição">
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex justify-content-center">
                                        <div class="col-12 col-lg">
                                            <label>Espécie:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="especie">
                                                <option value="">...</option>
                                                <option value="A">Ardosia</option>
                                                <option value="G">Granito</option>
                                                <option value="M">Mármore</option>
                                                <option value="F">Pedra Artificial</option>
                                                <option value="J">Jadstone</option>
                                                <option value="L">Limestone</option>
                                                <option value="O">Polo Blade</option>
                                                <option value="P">Porcelanato</option>
                                                <option value="R">Quartizito</option>
                                                <option value="Q">Quartizo</option>
                                                <option value="T">Travertino</option>
                                                <option value="S">Marmoglassi</option>
                                                <option value="X">Onix</option>
                                                <option value="Z">Outros</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Origem:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="origem">
                                                <option value="">...</option>
                                                <option value="N">Nacional</option>
                                                <option value="I">Importado</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Formato:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="formato">
                                                <option value="">...</option>
                                                <option value="B">Bloco</option>
                                                <option value="C">Chapa</option>
                                                <option value="L">Ladrilho</option>
                                                <option value="Z">Outros</option>

                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Estado:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="estado">
                                                <option value="">...</option>
                                                <option value="A">Apicoado</option>
                                                <option value="C">Acetinado</option>
                                                <option value="S">Bipolido</option>
                                                <option value="B">Bruto</option>
                                                <option value="E">Escovado</option>
                                                <option value="F">Flameado</option>
                                                <option value="O">Fosco</option>
                                                <option value="L">Levigado</option>
                                                <option value="Z">Outros</option>
                                                <option value="P">Polido</option>

                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Espessura:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="espessura">
                                                <option value="">...</option>
                                                <option value="1.0">1.0</option>
                                                <option value="1.2">1.2</option>
                                                <option value="1.5">1.5</option>
                                                <option value="1.8">1.8</option>
                                                <option value="0.6">0.6</option>
                                                <option value="2.0">2.0</option>
                                                <option value="3.0">3.0</option>

                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg">
                                            <label>Moeda:<span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm" name="moeda">
                                                <option value="">...</option>
                                                <option value="R$">Real</option>
                                                <option value="U$">Dólar</option>
                                                <option value="€">Euro</option>

                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Unidade:<span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm" name="unidade">
                                                <option value="">...</option>
                                                <option value="m">m</option>
                                                <option value="m2">m²</option>
                                                <option value="Conjunto">Conjunto</option>
                                                <option value="Peça">Peça</option>
                                                <option value="lts">Litros</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>NCM:<span class="text-danger">*</span></label>

                                            <div class="input-icon input-icon-right">
                                                <input type="hidden" name="id_ncm">
                                                <input type="text" class="form-control" name="ncm" placeholder="NCM" autocomplete="INFOSYSTEM" readonly>
                                                <span>
                                                    <a href="#" class="listaNCM" data-toggle="modal" data-target="#listaNCM"><i class="flaticon2-search-1 icon-md"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Valor:</label>
                                            <input type="text" class="form-control money2Mask" name="valor" value="0,00" placeholder="Valor">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg">
                                            <label>Observação:</label>
                                            <input type="text" class="form-control" name="observacao" value="" placeholder="Observação">
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

<div class="modal fade" id="listaNCM" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista NCM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="490">
                    <div class="row mb-8">
                        <div class="col-lg-4 mb-lg-0 mb-6">
                            <label>Código:</label>
                            <input type="text" class="form-control datatable-input-ncm" placeholder="Código" data-col-index="0" />
                        </div>
                        <div class="col-lg-5 mb-lg-0 mb-6">
                            <label>Descrição:</label>
                            <input type="text" class="form-control datatable-input-ncm" placeholder="Descrição" data-col-index="1" />
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6 pt-8 text-right">
                            <button class="btn btn-primary btn-primary--icon" id="kt_search_ncm">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>Pesquisar</span>
                                </span>
                            </button>
                        </div>
                        <div class="col-xl-12 p-0">
                            <div class="separator separator-dashed my-5"></div>
                        </div>
                    </div>

                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover table-checkable table-striped display compact" id="kt_datatable_ncm">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>NCM</th>
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

<script src="<?= $url ?>assets/js/estoque/produto/add.js"></script>