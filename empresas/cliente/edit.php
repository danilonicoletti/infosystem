<?
include('../../topo.php');

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM clientes WHERE id_cliente = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

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
                        <a href="<?= $url ?>cliente" class="text-muted">Cliente</a>
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
            <a href="../cliente/" class="btn btn-default font-weight-bold">Voltar</a>
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
                    <div class="" id="cliente_edit" data-wizard-state="step-first" data-wizard-clickable="true">

                        <!--begin::Wizard Body-->
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!--begin::Form Wizard-->
                                <form class="form" id="cliente_edit_form">
                                    <input name="type" type="hidden" value="cliente_edit" />
                                    <input name="id" type="hidden" value="<?= $linha['id_cliente'] ?>" />

                                    <div class="form-group row">
                                        <div class="col-12 col-lg-3">
                                            <label>Pessoa:<span class="text-danger">*</span></label>
                                            <select class="form-control" name="pessoa">
                                                <option value="">--</option>
                                                <option value="juridica" <?= $linha['pessoa'] == 'juridica' ? 'selected' : '' ?>>Jurídica</option>
                                                <option value="fisica" <?= $linha['pessoa'] == 'fisica' ? 'selected' : '' ?>>Física</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>&nbsp;</label>
                                            <div class="checkbox-inline">
                                                <label class="checkbox mt-3">
                                                    <input type="checkbox" name="isento" <?= $linha['documentos'] == 'Isento' ? 'checked' : '' ?>>
                                                    <span></span>
                                                    Inscrição Estadual Isenta
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>Código:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" value="<?= $linha['codigo'] ?>" placeholder="Código" readonly>
                                        </div>

                                        <div class="col-12 col-lg">
                                            <label class="alter-name-1">Nome Fantasia:</label>
                                            <input type="text" class="form-control alter-name-1" value="<?= $linha['nome'] ?>" name="nome" placeholder="Nome Fantasia">
                                        </div>
                                        <div class="col-12 col-lg">
                                            <label class="alter-name-2">Razão Social:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control alter-name-2" value="<?= $linha['nomes'] ?>" name="nomes" placeholder="Razão Social">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Contato:</label>
                                            <input type="text" class="form-control" value="<?= $linha['contato'] ?>" name="contato" placeholder="Contato">
                                        </div>

                                    </div>
                                    <div class="form-group row">

                                        <div class="col-12 col-lg-2">
                                            <label class="alter-doc-1">Documento:</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control alter-doc-1" name="documento" value="<?= $linha['documento'] ?>" placeholder="Documento">
                                        </div>
                                        <div class="col-12 col-lg-2 box-doc-2">
                                            <label class="alter-doc-2">Documento:</label>
                                            <input type="text" class="form-control alter-doc-2" name="documentos" value="<?= $linha['documentos'] ?>" placeholder="Documento">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Celular:</label>
                                            <input type="text" class="form-control celMask" name="celular" value="<?= $linha['celular'] ?>" placeholder="Celular">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Telefone:</label>
                                            <input type="text" class="form-control telMask" name="telefone" value="<?= $linha['telefone'] ?>" placeholder="Telefone">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label>E-mail:</label>
                                            <input type="text" class="form-control" name="email" value="<?= $linha['email'] ?>" placeholder="E-mail">
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>CEP:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control cepMask" name="cep" value="<?= $linha['cep'] ?>" placeholder="CEP">
                                            <span class="form-text text-muted text-cep-validator text-danger"></span>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Endereço:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control endereco" name="endereco" value="<?= $linha['endereco'] ?>" placeholder="Endereço">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Numero:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control numero" name="numero" value="<?= $linha['numero'] ?>" placeholder="Numero">
                                            <input type="hidden" class="form-control ibge" name="ibge" value="<?= $linha['cod_municipio'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Complemento:</label>
                                            <input type="text" class="form-control" name="complemento" value="<?= $linha['complemento'] ?>" placeholder="Complemento">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Bairro:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control bairro" name="bairro" value="<?= $linha['bairro'] ?>" placeholder="Bairro">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Cidade:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control cidade" name="cidade" value="<?= $linha['municipio'] ?>" placeholder="Cidade">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Estado:<span class="text-danger">*</span></label>
                                            <select class="form-control uf" name="estado">
                                                <option value="">...</option>
                                                <option value="AC" <?= $linha['uf'] == 'AC' ? 'selected' : '' ?>>AC</option>
                                                <option value="AL" <?= $linha['uf'] == 'AL' ? 'selected' : '' ?>>AL</option>
                                                <option value="AP" <?= $linha['uf'] == 'AP' ? 'selected' : '' ?>>AP</option>
                                                <option value="AM" <?= $linha['uf'] == 'AM' ? 'selected' : '' ?>>AM</option>
                                                <option value="BA" <?= $linha['uf'] == 'BA' ? 'selected' : '' ?>>BA</option>
                                                <option value="CE" <?= $linha['uf'] == 'CE' ? 'selected' : '' ?>>CE</option>
                                                <option value="DF" <?= $linha['uf'] == 'DF' ? 'selected' : '' ?>>DF</option>
                                                <option value="ES" <?= $linha['uf'] == 'ES' ? 'selected' : '' ?>>ES</option>
                                                <option value="GO" <?= $linha['uf'] == 'GO' ? 'selected' : '' ?>>GO</option>
                                                <option value="MA" <?= $linha['uf'] == 'MA' ? 'selected' : '' ?>>MA</option>
                                                <option value="MT" <?= $linha['uf'] == 'MT' ? 'selected' : '' ?>>MT</option>
                                                <option value="MS" <?= $linha['uf'] == 'MS' ? 'selected' : '' ?>>MS</option>
                                                <option value="MG" <?= $linha['uf'] == 'MG' ? 'selected' : '' ?>>MG</option>
                                                <option value="PA" <?= $linha['uf'] == 'PA' ? 'selected' : '' ?>>PA</option>
                                                <option value="PB" <?= $linha['uf'] == 'PB' ? 'selected' : '' ?>>PB</option>
                                                <option value="PR" <?= $linha['uf'] == 'PR' ? 'selected' : '' ?>>PR</option>
                                                <option value="PE" <?= $linha['uf'] == 'PE' ? 'selected' : '' ?>>PE</option>
                                                <option value="PI" <?= $linha['uf'] == 'PI' ? 'selected' : '' ?>>PI</option>
                                                <option value="RJ" <?= $linha['uf'] == 'RJ' ? 'selected' : '' ?>>RJ</option>
                                                <option value="RN" <?= $linha['uf'] == 'RN' ? 'selected' : '' ?>>RN</option>
                                                <option value="RS" <?= $linha['uf'] == 'RS' ? 'selected' : '' ?>>RS</option>
                                                <option value="RO" <?= $linha['uf'] == 'RO' ? 'selected' : '' ?>>RO</option>
                                                <option value="RR" <?= $linha['uf'] == 'RR' ? 'selected' : '' ?>>RR</option>
                                                <option value="SC" <?= $linha['uf'] == 'SC' ? 'selected' : '' ?>>SC</option>
                                                <option value="SP" <?= $linha['uf'] == 'SP' ? 'selected' : '' ?>>SP</option>
                                                <option value="SE" <?= $linha['uf'] == 'SE' ? 'selected' : '' ?>>SE</option>
                                                <option value="TO" <?= $linha['uf'] == 'TO' ? 'selected' : '' ?>>TO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-2">
                                            <label>CEP:</label>
                                            <input type="text" class="form-control cepMask2" name="cep2" value="<?= $linha['cep2'] ?>" placeholder="CEP">
                                            <span class="form-text text-muted text-cep-validator2 text-danger"></span>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label>Endereço:</label>
                                            <input type="text" class="form-control endereco2" name="endereco2" value="<?= $linha['endereco2'] ?>" placeholder="Endereço">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Numero:</label>
                                            <input type="text" class="form-control numero2" name="numero2" value="<?= $linha['numero2'] ?>" placeholder="Numero">
                                            <input type="hidden" class="form-control ibge2" name="ibge2" value="<?= $linha['cod_municipio2'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-lg-4">
                                            <label>Complemento:</label>
                                            <input type="text" class="form-control" name="complemento2" value="<?= $linha['complemento2'] ?>" placeholder="Complemento">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Bairro:</label>
                                            <input type="text" class="form-control bairro2" name="bairro2" value="<?= $linha['bairro2'] ?>" placeholder="Bairro">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label>Cidade:</label>
                                            <input type="text" class="form-control cidade2" name="cidade2" value="<?= $linha['municipio2'] ?>" placeholder="Cidade">
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label>Estado:</label>
                                            <select class="form-control uf2" name="estado2">
                                                <option value="">...</option>
                                                <option value="AC" <?= $linha['uf2'] == 'AC' ? 'selected' : '' ?>>AC</option>
                                                <option value="AL" <?= $linha['uf2'] == 'AL' ? 'selected' : '' ?>>AL</option>
                                                <option value="AP" <?= $linha['uf2'] == 'AP' ? 'selected' : '' ?>>AP</option>
                                                <option value="AM" <?= $linha['uf2'] == 'AM' ? 'selected' : '' ?>>AM</option>
                                                <option value="BA" <?= $linha['uf2'] == 'BA' ? 'selected' : '' ?>>BA</option>
                                                <option value="CE" <?= $linha['uf2'] == 'CE' ? 'selected' : '' ?>>CE</option>
                                                <option value="DF" <?= $linha['uf2'] == 'DF' ? 'selected' : '' ?>>DF</option>
                                                <option value="ES" <?= $linha['uf2'] == 'ES' ? 'selected' : '' ?>>ES</option>
                                                <option value="GO" <?= $linha['uf2'] == 'GO' ? 'selected' : '' ?>>GO</option>
                                                <option value="MA" <?= $linha['uf2'] == 'MA' ? 'selected' : '' ?>>MA</option>
                                                <option value="MT" <?= $linha['uf2'] == 'MT' ? 'selected' : '' ?>>MT</option>
                                                <option value="MS" <?= $linha['uf2'] == 'MS' ? 'selected' : '' ?>>MS</option>
                                                <option value="MG" <?= $linha['uf2'] == 'MG' ? 'selected' : '' ?>>MG</option>
                                                <option value="PA" <?= $linha['uf2'] == 'PA' ? 'selected' : '' ?>>PA</option>
                                                <option value="PB" <?= $linha['uf2'] == 'PB' ? 'selected' : '' ?>>PB</option>
                                                <option value="PR" <?= $linha['uf2'] == 'PR' ? 'selected' : '' ?>>PR</option>
                                                <option value="PE" <?= $linha['uf2'] == 'PE' ? 'selected' : '' ?>>PE</option>
                                                <option value="PI" <?= $linha['uf2'] == 'PI' ? 'selected' : '' ?>>PI</option>
                                                <option value="RJ" <?= $linha['uf2'] == 'RJ' ? 'selected' : '' ?>>RJ</option>
                                                <option value="RN" <?= $linha['uf2'] == 'RN' ? 'selected' : '' ?>>RN</option>
                                                <option value="RS" <?= $linha['uf2'] == 'RS' ? 'selected' : '' ?>>RS</option>
                                                <option value="RO" <?= $linha['uf2'] == 'RO' ? 'selected' : '' ?>>RO</option>
                                                <option value="RR" <?= $linha['uf2'] == 'RR' ? 'selected' : '' ?>>RR</option>
                                                <option value="SC" <?= $linha['uf2'] == 'SC' ? 'selected' : '' ?>>SC</option>
                                                <option value="SP" <?= $linha['uf2'] == 'SP' ? 'selected' : '' ?>>SP</option>
                                                <option value="SE" <?= $linha['uf2'] == 'SE' ? 'selected' : '' ?>>SE</option>
                                                <option value="TO" <?= $linha['uf2'] == 'TO' ? 'selected' : '' ?>>TO</option>
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

<script src="<?= $url ?>assets/js/empresas/cliente/edit.js"></script>