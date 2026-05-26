<?php
include __DIR__ . "/../../topo.php";

$consulta = $pdo->query("SELECT codigo FROM fornecedor ORDER BY codigo DESC LIMIT 0,1");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$codigo = $linha['codigo'] + 1;

$consultaTipoFornecedor = $pdo->query("SELECT * FROM tipo_de_fornecedor WHERE id_empresa = " . $company . " ORDER BY descricao ASC");
while ($linhaTipoFornecedor = $consultaTipoFornecedor->fetch(PDO::FETCH_ASSOC)) {
    $loopTipoFornecedor .= '<option value="' . $linhaTipoFornecedor['codigo'] . '">' . $linhaTipoFornecedor['descricao'] . '</option>';
}

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
                        <a href="<?= $url ?>fornecedor" class="text-muted">Fornecedor</a>
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
            <a href="../fornecedor/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="fornecedor_add" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="fornecedor_add_form">
                                    <input name="type" type="hidden" value="fornecedor_add" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-3">
                                            <label>Pessoa:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="pessoa">
                                                <option value="">--</option>
                                                <option value="juridica">Jurídica</option>
                                                <option value="fisica">Física</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Tipo de Fornecedor:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="tipo_fornecedor">
                                                <option value="">--</option>
                                                <?= $loopTipoFornecedor ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label>Contato:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " name="contato" placeholder="Contato">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " name="codigo" placeholder="Código" value="<?= str_pad($codigo, 5, '0', STR_PAD_LEFT) ?>" readonly>
                                        </div>

                                        <div class="col-12 col-lg-5">
                                            <label class="alter-name-1">Nome Fantasia:</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control alter-name-1" name="nome" placeholder="Nome Fantasia">
                                        </div>
                                        <div class="col-12 col-lg-5">
                                            <label class="alter-name-2">Razão Social:</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control alter-name-2" name="nomes" placeholder="Razão Social">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Email:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="email" placeholder="Email">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label class="alter-doc-1">Documento:</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control alter-doc-1" name="documento" placeholder="Documento">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label class="alter-doc-2">Documento:</label>
                                            <input type="text" class="form-control alter-doc-2" name="documentos" placeholder="Documento">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Celular:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control celMask" name="celular" placeholder="Celular">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Telefone:</label>
                                            <input type="text" class="form-control telMask" name="telefone" placeholder="Telefone">
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>CEP:</label>
                                            <input type="text" class="form-control cepMask" name="cep" placeholder="CEP" required>
                                            <span class="form-text text-muted text-cep-validator text-danger"></span>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Endereço:</label>
                                            <input type="text" class="form-control endereco" name="endereco" placeholder="Endereço">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Numero:</label>
                                            <input type="text" class="form-control numero" name="numero" placeholder="Numero">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Complemento:</label>
                                            <input type="text" class="form-control" name="complemento" value="" placeholder="Complemento">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Bairro:</label>
                                            <input type="text" class="form-control bairro" name="bairro" placeholder="Bairro">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Cidade:</label>
                                            <input type="text" class="form-control cidade" name="cidade" placeholder="Cidade">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Estado:</label>
                                            <select class="form-control uf" name="estado">
                                                <option value="">...</option>
                                                <option value="AC">AC</option>
                                                <option value="AL">AL</option>
                                                <option value="AP">AP</option>
                                                <option value="AM">AM</option>
                                                <option value="BA">BA</option>
                                                <option value="CE">CE</option>
                                                <option value="DF">DF</option>
                                                <option value="ES">ES</option>
                                                <option value="GO">GO</option>
                                                <option value="MA">MA</option>
                                                <option value="MT">MT</option>
                                                <option value="MS">MS</option>
                                                <option value="MG">MG</option>
                                                <option value="PA">PA</option>
                                                <option value="PB">PB</option>
                                                <option value="PR">PR</option>
                                                <option value="PE">PE</option>
                                                <option value="PI">PI</option>
                                                <option value="RJ">RJ</option>
                                                <option value="RN">RN</option>
                                                <option value="RS">RS</option>
                                                <option value="RO">RO</option>
                                                <option value="RR">RR</option>
                                                <option value="SC">SC</option>
                                                <option value="SP">SP</option>
                                                <option value="SE">SE</option>
                                                <option value="TO">TO</option>
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

<script src="<?= $url ?>assets/js/empresas/fornecedor/add.js"></script>