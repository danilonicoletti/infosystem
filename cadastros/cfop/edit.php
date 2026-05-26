<?
include('../../topo.php');

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM cfop WHERE id_cfop = " . $id);
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
                        <a href="<?= $url ?>cfop" class="text-muted">CFOP</a>
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
            <a href="../cfop/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="cfop_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="cfop_edit_form">
                                    <input name="id" type="hidden" value="<?= $id ?>" />
                                    <input name="type" type="hidden" value="cfop_edit" />
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" value="<?= $linha['codigo'] ?>" placeholder="Código" readonly>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>CFOP:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cfop" value="<?= $linha['cfop'] ?>" placeholder="CFOP">
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Descrição:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="descricao" value="<?= $linha['descricao'] ?>" placeholder="Descrição">
                                        </div>


                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>ICMS:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="icms">
                                                <option value="">...</option>
                                                <option value="00" <?= $linha['icms'] == '00' ? 'selected' : '' ?>>00 - Tributada Integralmente</option>
                                                <option value="10" <?= $linha['icms'] == '10' ? 'selected' : '' ?>>10 - Tributada e com cobrança do ICMS por substituição tributária</option>
                                                <option value="20" <?= $linha['icms'] == '20' ? 'selected' : '' ?>>20 - Com redução de base de cálculo</option>
                                                <option value="30" <?= $linha['icms'] == '30' ? 'selected' : '' ?>>30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária</option>
                                                <option value="40" <?= $linha['icms'] == '40' ? 'selected' : '' ?>>40 - Isenta</option>
                                                <option value="41" <?= $linha['icms'] == '41' ? 'selected' : '' ?>>41 - Não tributada</option>
                                                <option value="50" <?= $linha['icms'] == '50' ? 'selected' : '' ?>>50 - Suspensão</option>
                                                <option value="51" <?= $linha['icms'] == '51' ? 'selected' : '' ?>>51 - Diferimento</option>
                                                <option value="60" <?= $linha['icms'] == '60' ? 'selected' : '' ?>>60 - ICMS cobrado anteriormente por substituição tributária</option>
                                                <option value="70" <?= $linha['icms'] == '70' ? 'selected' : '' ?>>70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária</option>
                                                <option value="90" <?= $linha['icms'] == '90' ? 'selected' : '' ?>>90 - Outros</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>IPI:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="ipi">
                                                <option value="">...</option>
                                                <option value="00" <?= $linha['ipi'] == '00' ? 'selected' : '' ?>>00 - Entrada com Recuperação de Crédito</option>
                                                <option value="01" <?= $linha['ipi'] == '01' ? 'selected' : '' ?>>01 - Entrada Tributável com Alíquota Zero</option>
                                                <option value="02" <?= $linha['ipi'] == '02' ? 'selected' : '' ?>>02 - Entrada Isenta</option>
                                                <option value="03" <?= $linha['ipi'] == '03' ? 'selected' : '' ?>>03 - Entrada Não-Tributada</option>
                                                <option value="04" <?= $linha['ipi'] == '04' ? 'selected' : '' ?>>04 - Entrada Imune</option>
                                                <option value="05" <?= $linha['ipi'] == '05' ? 'selected' : '' ?>>05 - Entrada com Suspensão</option>
                                                <option value="49" <?= $linha['ipi'] == '49' ? 'selected' : '' ?>>49 - Outras Entradas</option>
                                                <option value="50" <?= $linha['ipi'] == '50' ? 'selected' : '' ?>>50 - Saída Tributada</option>
                                                <option value="51" <?= $linha['ipi'] == '51' ? 'selected' : '' ?>>51 - Saída Tributável com Alíquota Zero</option>
                                                <option value="52" <?= $linha['ipi'] == '52' ? 'selected' : '' ?>>52 - Saída Isenta</option>
                                                <option value="53" <?= $linha['ipi'] == '53' ? 'selected' : '' ?>>53 - Saída Não-Tributada</option>
                                                <option value="54" <?= $linha['ipi'] == '54' ? 'selected' : '' ?>>54 - Saída Imune</option>
                                                <option value="55" <?= $linha['ipi'] == '55' ? 'selected' : '' ?>>55 - Saída com Suspensão</option>
                                                <option value="99" <?= $linha['ipi'] == '99' ? 'selected' : '' ?>>99 - Outras Saídas</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>Cofins:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="cofins">
                                                <option value="">...</option>
                                                <option value="01" <?= $linha['cofins'] == '01' ? 'selected' : '' ?>>01 - Operação Tributável com Alíquota Básica</option>
                                                <option value="02" <?= $linha['cofins'] == '02' ? 'selected' : '' ?>>02 - Operação Tributável com Alíquota Diferenciada</option>
                                                <option value="03" <?= $linha['cofins'] == '03' ? 'selected' : '' ?>>03 - Operação Tributável com Alíquota por unidade de Medida de Produto</option>
                                                <option value="04" <?= $linha['cofins'] == '04' ? 'selected' : '' ?>>04 - Operação Tributável Monofásica - Revenda a Alíquota Zero</option>
                                                <option value="05" <?= $linha['cofins'] == '05' ? 'selected' : '' ?>>05 - Operação Tributável por Substituição Tributária</option>
                                                <option value="06" <?= $linha['cofins'] == '06' ? 'selected' : '' ?>>06 - Operação Tributável a Alíquota Zero</option>
                                                <option value="07" <?= $linha['cofins'] == '07' ? 'selected' : '' ?>>07 - Operação Isenta da Contribuição</option>
                                                <option value="49" <?= $linha['cofins'] == '49' ? 'selected' : '' ?>>49 - Outras Operações de Saída</option>
                                                <option value="71" <?= $linha['cofins'] == '71' ? 'selected' : '' ?>>71 - Operação de Aquisição com Isenção</option>
                                                <option value="72" <?= $linha['cofins'] == '72' ? 'selected' : '' ?>>72 - Operação de Aquisição com Suspensão</option>
                                                <option value="73" <?= $linha['cofins'] == '73' ? 'selected' : '' ?>>73 - Operação de Aquisição a Alíquota Zero</option>
                                                <option value="74" <?= $linha['cofins'] == '74' ? 'selected' : '' ?>>74 - Operação de Aquisição sem Incidência da Contribuição</option>
                                                <option value="75" <?= $linha['cofins'] == '75' ? 'selected' : '' ?>>75 - Operação de Aquisição por Substituição Tributária</option>
                                                <option value="98" <?= $linha['cofins'] == '98' ? 'selected' : '' ?>>98 - Outras Operações de Entrada</option>
                                                <option value="99" <?= $linha['cofins'] == '99' ? 'selected' : '' ?>>99 - Outras Operações</option>
                                            </select>
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

<script src="<?= $url ?>assets/js/cadastros/cfop/edit.js"></script>