<?php

include __DIR__ . "/../../topo.php";

$consulta = $pdo->query("SELECT codigo FROM cfop ORDER BY codigo DESC LIMIT 0,1");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$codigo = $linha['codigo'] + 1;

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
                        <a href="<?= $url ?>cfop" class="text-muted">CFOP</a>
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
            <a href="../cfop/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="cfop_add" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="cfop_add_form">
                                    <input name="type" type="hidden" value="cfop_add" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" placeholder="Código" value="<?= str_pad($codigo, 5, '0', STR_PAD_LEFT) ?>" readonly>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>CFOP:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control cfop" name="cfop" placeholder="CFOP">
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Descrição:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="descricao" placeholder="Descrição">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>ICMS:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="icms">
                                                <option value="">...</option>
                                                <option value="00">00 - Tributada Integralmente</option>
                                                <option value="10">10 - Tributada e com cobrança do ICMS por substituição tributária</option>
                                                <option value="20">20 - Com redução de base de cálculo</option>
                                                <option value="30">30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária</option>
                                                <option value="40">40 - Isenta</option>
                                                <option value="51">41 - Não tributada</option>
                                                <option value="50">50 - Suspensão</option>
                                                <option value="51">51 - Diferimento</option>
                                                <option value="60">60 - ICMS cobrado anteriormente por substituição tributária</option>
                                                <option value="70">70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária</option>
                                                <option value="90">90 - Outros</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>IPI:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="ipi">
                                                <option value="">...</option>
                                                <option value="00">00 - Entrada com Recuperação de Crédito</option>
                                                <option value="01">01 - Entrada Tributável com Alíquota Zero</option>
                                                <option value="02">02 - Entrada Isenta</option>
                                                <option value="03">03 - Entrada Não-Tributada</option>
                                                <option value="04">04 - Entrada Imune</option>
                                                <option value="05">05 - Entrada com Suspensão</option>
                                                <option value="49">49 - Outras Entradas</option>
                                                <option value="50">50 - Saída Tributada</option>
                                                <option value="51">51 - Saída Tributável com Alíquota Zero</option>
                                                <option value="52">52 - Saída Isenta</option>
                                                <option value="53">53 - Saída Não-Tributada</option>
                                                <option value="54">54 - Saída Imune</option>
                                                <option value="55">55 - Saída com Suspensão</option>
                                                <option value="99">99 - Outras Saídas</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Cofins:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="cofins">
                                                <option value="">...</option>
                                                <option value="01">01 - Operação Tributável com Alíquota Básica</option>
                                                <option value="02">02 - Operação Tributável com Alíquota Diferenciada</option>
                                                <option value="03">03 - Operação Tributável com Alíquota por unidade de Medida de Produto</option>
                                                <option value="04">04 - Operação Tributável Monofásica - Revenda a Alíquota Zero</option>
                                                <option value="05">05 - Operação Tributável por Substituição Tributária</option>
                                                <option value="06">06 - Operação Tributável a Alíquota Zero</option>
                                                <option value="07">07 - Operação Isenta da Contribuição</option>
                                                <option value="49">49 - Outras Operações de Saída</option>
                                                <option value="71">71 - Operação de Aquisição com Isenção</option>
                                                <option value="72">72 - Operação de Aquisição com Suspensão</option>
                                                <option value="73">73 - Operação de Aquisição a Alíquota Zero</option>
                                                <option value="74">74 - Operação de Aquisição sem Incidência da Contribuição</option>
                                                <option value="75">75 - Operação de Aquisição por Substituição Tributária</option>
                                                <option value="98">98 - Outras Operações de Entrada</option>
                                                <option value="99">99 - Outras Operações</option>
                                            </select>
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
<!--end::Container-->
<!--begin::Footer-->
<?php include  __DIR__ . "/../../footer.php"; ?>
<!--end::Footer-->

<script src="<?= $url ?>assets/js/cadastros/cfop/add.js"></script>