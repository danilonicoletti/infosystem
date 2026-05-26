<?php

include __DIR__ . "/../../topo.php";

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT descricao FROM produtos WHERE id_produto = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

unset($_SESSION['complex']);

?>

<style>
    .dataTables_wrapper .dataTable th,
    .dataTables_wrapper .dataTable td {
        padding: 0.5rem 0.5rem !important;
    }

    .table tbody td:nth-child(5):not(.dataTables_empty),
    .table thead th:nth-child(5):not(.dataTables_empty),
    .table tbody td:nth-child(6):not(.dataTables_empty),
    .table thead th:nth-child(6):not(.dataTables_empty) {
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
                        <a href="" class="text-muted">Estoque</a>
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
                <div class="card-header">
                    <h3 class="card-title">
                        <?= $linha['descricao'] ?>
                    </h3>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <input type="hidden" id="id_produto" value="<?= $id ?>">
                    <table class="table table-bordered table-hover table-checkable table-striped" id="kt_datatable" style="margin-top: 13px !important">
                        <thead style="background: #9acfea;">
                            <tr>
                                <th>Código</th>
                                <th>Data Emissão&nbsp;&nbsp;&nbsp;</th>
                                <th>Medidas</th>
                                <th>M²</th>
                                <th>Medidas</th>
                                <th>Medidas</th>
                                <th>Quantidade</th>
                                <th>Fornecedor</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="row totais">

                    </div>
                    <!--end: Datatable-->
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
<!--end::Container-->
<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<!--begin::Page Scripts(used by this page)-->
<script src="<?= $url ?>assets/js/estoque/produto/estoque.js"></script>