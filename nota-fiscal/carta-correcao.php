<?php

include __DIR__ . "/../topo.php";


$consulta = $pdo->query("SELECT * FROM nfe WHERE status = 2 ORDER BY id_nfe ASC");
while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

    $nfes .= '<option value="' . $linha['id_nfe'] . '">Nota Fiscal Nº ' . $linha['nota_fiscal'] . '</option>';
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
                        <a href="" class="text-muted">Carta de Correção</a>
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
                    <div class="" id="_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="_edit_form">
                                    <!-- <input type="hidden" name="type" value="orcamento_edit" /> -->
                                    <input name="id" type="hidden" value="" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">
                                            <label>Escolha uma Nota Fiscal:<span class="text-danger">*</span></label>

                                            <div class="input-group">
                                                <select class="form-control" id="nfe" name="nfe">
                                                    <option value="">...</option>
                                                    <?= $nfes ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="col-12 col-lg-2">
                                            <label>Data:<span class="text-danger">*</span></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control form-control-solid dateMask" placeholder="Selecione a Data" name="dt_emissao" value="" id="dt_emissao" required tabindex="4" disabled />
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: none;">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-2">
                                            <label>Emissor:</label>
                                            <input type="text" class="form-control" name="emissor" value="" placeholder="Emissor" disabled>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label>Cliente:</label>
                                            <input type="text" class="form-control" name="cliente" value="" placeholder="Cliente" disabled>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Frete:<span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="frete" value="" placeholder="Frete" disabled>

                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>CFOP:</label>

                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control" name="cfop" value="" placeholder="CFOP" disabled>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-7"></div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-12">
                                            <label>Informações complementares:</label>

                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control" name="correcao" value="" placeholder="Informações complementares">

                                            </div>
                                        </div>
                                    </div>

                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between ">
                                        <div class="mr-2">

                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 corrigir" style="display: none;">Gerar Carta de Correção</button>
                                            <!-- <a href="imprimir-nfe?id=<?= $id ?>" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" title="Imprimir Nota" target="_blank">Imprimir NFe</a> -->
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

<!--begin::Footer-->
<?php include  __DIR__ . "/../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/nota-fiscal/carta-correcao.js"></script>