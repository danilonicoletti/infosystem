<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg p-0">
    <!--begin::Header-->
    <div class="d-flex align-items-center p-8 rounded-top">
        <!--begin::Symbol-->
        <div class="symbol symbol-md bg-light-primary mr-3 flex-shrink-0">
            <img src="<?= $url ?>assets/media/company/<?= $imagemCard ?>" alt="" />
        </div>
        <!--end::Symbol-->
        <!--begin::Text-->
        <div class="text-dark m-0 flex-grow-1 mr-3 font-size-h5"><?= $primeiroNome ?></div>
        <!-- <span class="label label-light-success label-lg font-weight-bold label-inline">3 messages</span> -->
        <!--end::Text-->
    </div>
    <div class="separator separator-solid"></div>
    <!--end::Header-->
    <!--begin::Nav-->
    <div class="navi navi-spacer-x-0 pt-5">
        <!--begin::Item-->
        <a href="javascript:;" class="navi-item px-8">
            <div class="navi-link">
                <div class="navi-icon mr-2">
                    <i class="flaticon2-calendar-3 text-success"></i>
                </div>
                <div class="navi-text">
                    <div class="font-weight-bold">Meu perfil</div>
                    <div class="text-muted">Configurações de conta e muito mais
                        <span class="label label-light-danger label-inline font-weight-bold">Atualização</span>
                    </div>
                </div>
            </div>
        </a>
        <!--end::Item-->
        <!--begin::Item-->
        <a href="javascript:;" class="navi-item px-8">
            <div class="navi-link">
                <div class="navi-icon mr-2">
                    <i class="flaticon2-mail text-warning"></i>
                </div>
                <div class="navi-text">
                    <div class="font-weight-bold">Minhas mensagens</div>
                    <div class="text-muted">Caixa de entrada e tarefas</div>
                </div>
            </div>
        </a>
        <!--end::Item-->
        <!--begin::Item-->
        <a href="javascript:;" class="navi-item px-8">
            <div class="navi-link">
                <div class="navi-icon mr-2">
                    <i class="flaticon2-rocket-1 text-danger"></i>
                </div>
                <div class="navi-text">
                    <div class="font-weight-bold">Minhas atividades</div>
                    <div class="text-muted">Registros e notificações</div>
                </div>
            </div>
        </a>
        <!--end::Item-->
        <!--begin::Item-->
        <a href="javascript:;" class="navi-item px-8">
            <div class="navi-link">
                <div class="navi-icon mr-2">
                    <i class="flaticon2-hourglass text-primary"></i>
                </div>
                <div class="navi-text">
                    <div class="font-weight-bold">Minhas tarefas</div>
                    <div class="text-muted">Últimas tarefas e projetos</div>
                </div>
            </div>
        </a>
        <!--end::Item-->
        <!--begin::Footer-->
        <div class="navi-separator mt-3"></div>
        <div class="navi-footer px-8 py-5">
            <a href="<?= $url ?>login/sair" class="btn btn-light-primary font-weight-bold">Sair</a>

        </div>
        <!--end::Footer-->
    </div>
    <!--end::Nav-->
</div>