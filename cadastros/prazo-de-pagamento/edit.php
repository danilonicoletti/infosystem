<?
include('../../topo.php');

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM prazo_de_pagamento WHERE id_pagamento = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

if ($linha['parcela_1'] == 0) {
    $parcela_1 = "";
} else {
    $parcela_1 = $linha['parcela_1'];
}
if ($linha['parcela_2'] == 0) {
    $parcela_2 = "";
} else {
    $parcela_2 = $linha['parcela_2'];
}
if ($linha['parcela_3'] == 0) {
    $parcela_3 = "";
} else {
    $parcela_3 = $linha['parcela_3'];
}
if ($linha['parcela_4'] == 0) {
    $parcela_4 = "";
} else {
    $parcela_4 = $linha['parcela_4'];
}
if ($linha['parcela_5'] == 0) {
    $parcela_5 = "";
} else {
    $parcela_5 = $linha['parcela_5'];
}
if ($linha['parcela_6'] == 0) {
    $parcela_6 = "";
} else {
    $parcela_6 = $linha['parcela_6'];
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
                        <a href="<?= $url ?>prazo-de-pagamento" class="text-muted">Prazo de Pagamento</a>
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
            <a href="../prazo-de-pagamento/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="prazo_de_pagamento_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="prazo_de_pagamento_edit_form">
                                    <input name="id" type="hidden" value="<?= $id ?>" />
                                    <input name="type" type="hidden" value="prazo_de_pagamento_edit" />
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" value="<?= $linha['codigo'] ?>" placeholder="Código" readonly>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Descrição:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="descricao" value="<?= $linha['descricao'] ?>" placeholder="Descrição">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg">
                                            <label>Parcela 1:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_1" placeholder="Parcela 1" value="<?= $parcela_1 ?>">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Parcela 2:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_2" placeholder="Parcela 2" value="<?= $parcela_2 ?>">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Parcela 3:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_3" placeholder="Parcela 3" value="<?= $parcela_3 ?>">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Parcela 4:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_4" placeholder="Parcela 4" value="<?= $parcela_4 ?>">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Parcela 5:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_5" placeholder="Parcela 5" value="<?= $parcela_5 ?>">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label>Parcela 6:</label>
                                            <input type="number" min="1" max="999" class="form-control" name="parcela_6" placeholder="Parcela 6" value="<?= $parcela_6 ?>">
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

<script src="<?= $url ?>assets/js/cadastros/prazo-de-pagamento/edit.js"></script>