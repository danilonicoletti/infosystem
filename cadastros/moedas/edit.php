<?
include('../../topo.php');

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM moedas WHERE id = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);
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
                        <a href="<?= $url ?>moedas" class="text-muted">Moeda</a>
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
            <a href="../moedas/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="moeda_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="moeda_edit_form">
                                    <input name="id" type="hidden" value="<?= $id ?>" />
                                    <input name="type" type="hidden" value="moeda_edit" />
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Data:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control dateMask" name="data" placeholder="Data" value="<?= implode("/", array_reverse(explode("-", $linha['data']))) ?>">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Moeda</label>
                                            <select class="form-control" name="moeda">
                                                <option>...</option>
                                                <option value="USD" <?= $linha['moeda'] == 'USD' ? 'selected' : '' ?>>Dolar</option>
                                                <option value="EUR" <?= $linha['moeda'] == 'EUR' ? 'selected' : '' ?>>Euro</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Valor:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control moneyMask" name="valor" placeholder="Valor" value="<?= number_format($linha['valor'], 2, ',', '.') ?>">
                                        </div>

                                    </div>
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between ">
                                        <div class="mr-2">

                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Gravar</button>

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
<!--end::Container-->
<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/cadastros/moedas/edit.js"></script>