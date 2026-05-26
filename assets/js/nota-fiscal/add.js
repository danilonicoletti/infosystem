'use strict';

// Class definition
var KTNotaAdd = (function () {
    // Base elements
    var _wizardEl;
    var _formEl;
    var _wizardObj;
    var _validations = [];

    // Private functions
    var _initWizard = function () {
        // Initialize form wizard
        _wizardObj = new KTWizard(_wizardEl, {
            startStep: 1, // initial active step number
            clickableSteps: false, // allow step clicking
        });

        // Change event
        _wizardObj.on('changed', function (wizard) {
            KTUtil.scrollTop();
        });

        // Submit event
        _wizardObj.on('submit', function (wizard) {
            var validator = _validations[0]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        var data = new FormData();

                        //Form data
                        var form_data = $('#nota_add_form').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        $.ajax({
                            url: HOST_URL + 'nota-fiscal/sql.php',
                            type: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status) {
                                //console.log(data);
                                var resposta = data.split('||');
                                if (resposta[0] == 'success') {
                                    Swal.fire({
                                        title: 'Inserida!',
                                        text: 'Nota Fiscal Inserida com sucesso!',
                                        icon: 'success',
                                        timer: 1000,
                                        onOpen: function () {
                                            Swal.showLoading();
                                        },
                                    }).then(function (result) {
                                        window.location.href = HOST_URL + 'nota-fiscal';
                                    });
                                } else {
                                    Swal.fire({
                                        html: resposta[1],
                                        icon: 'error',
                                        buttonsStyling: false,
                                        confirmButtonText: 'OK, entendi!',
                                        customClass: {
                                            confirmButton: 'btn font-weight-bold btn-primary',
                                        },
                                    });
                                }
                            },
                            error: function (xhr, desc, err) {
                                Swal.fire({
                                    html: 'Nota Fiscal não foi inserida!',
                                    icon: 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: 'OK, entendi!',
                                    customClass: {
                                        confirmButton: 'btn font-weight-bold btn-primary',
                                    },
                                });
                            },
                        });
                    } else {
                    }
                });
            }
        });
    };

    var _initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // Step 1
        _validations.push(
            FormValidation.formValidation(_formEl, {

                fields: {
                    dt_emissao: {
                        validators: {
                            notEmpty: {
                                message: 'Data é Necessário',
                            },
                        },
                    },
                    emissor: {
                        validators: {
                            notEmpty: {
                                message: 'Emissor é Necessário',
                            },
                        },
                    },
                    cliente: {
                        validators: {
                            notEmpty: {
                                message: 'Cliente é Necessário',
                            },
                        },
                    },
                    frete: {
                        validators: {
                            notEmpty: {
                                message: 'Frete é Necessário',
                            },
                        },
                    },
                    cfop: {
                        validators: {
                            notEmpty: {
                                message: 'CFOP é Necessário',
                            },
                        },
                    }
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    }),
                },
            })
        );
    };

    return {
        // public functions
        init: function () {
            _wizardEl = KTUtil.getById('nota_add');
            _formEl = KTUtil.getById('nota_add_form');

            _initWizard();
            _initValidation();
        },
    };
})();

var KTBootstrapDatepicker = (function () {
    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>',
        };
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        };
    }

    // Private functions
    var demos = function () {
        // enable clear button
        $('#dt_emissao').datepicker({

            rtl: KTUtil.isRTL(),
            todayBtn: 'linked',
            clearBtn: true,
            todayHighlight: true,
            templates: arrows,
            format: "dd/mm/yyyy",
            language: "pt-BR",
        });
    };

    return {
        // public functions
        init: function () {
            demos();
        },
    };
})();

var KTprodutosAdd = (function () {
    // Base elements
    var _wizardEl;
    var _formEl;
    var _wizardObj;
    var _validations = [];

    // Private functions
    var _initWizard = function () {
        // Initialize form wizard
        _wizardObj = new KTWizard(_wizardEl, {
            startStep: 1, // initial active step number
            clickableSteps: false, // allow step clicking
        });

        // Change event
        _wizardObj.on('changed', function (wizard) {
            KTUtil.scrollTop();
        });

        // Submit event
        _wizardObj.on('submit', function (wizard) {
            $('.action-hidden').hide();

            var validator = _validations[0]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        var data = new FormData();

                        //Form data
                        var form_data = $('#produtos_add_form').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        //Custom data
                        data.append('key', 'value');

                        $.ajax({
                            url: '../nota-fiscal/sql.php',
                            type: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status) {
                                //console.log(data);
                                var resposta = data.split('||');
                                if (resposta[0] == 'success') {
                                    Swal.fire({
                                        title: 'Inserido!',
                                        text: 'Produto inserido na nfe com sucesso!',
                                        icon: 'success',
                                        timer: 1000,
                                        onOpen: function () {
                                            Swal.showLoading();
                                        },
                                    }).then(function (result) {
                                        $('.action-hidden').show();

                                        $('#addProduto').modal('toggle');

                                        $('.ncm_search').val('');
                                        $('.ncm_search').val('');

                                        $('input[name=codigo]').val('');
                                        $('input[name=id_ncm]').val('');
                                        $('input[name=ncm]').val('');
                                        $('input[name=descricao]').val('');
                                        $('select[name=cstcson]').val('');
                                        $('select[name=orig]').val('');
                                        $('input[name=qtd]').val('');
                                        $('select[name=unid]').val('');
                                        $('input[name=valor]').val('');
                                        $('input[name=p_icms]').val('');
                                        $('input[name=p_ipi]').val('');
                                        $('input[name=enq_ipi]').val('');
                                        $('input[name=vl_ipi]').val('');

                                        $('.display-st').hide();

                                        $('.txt_st_icms').html('');
                                        $('.txt_st_pis_confins').html('');
                                        $('.txt_st_ipi').html('');

                                        var id_nfe = $('#id_nfe').val();

                                        $.ajax({
                                            url: '../nota-fiscal/sql.php',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                type: 'produtos_view_edit',
                                                id_nfe: id_nfe,
                                            },
                                            success: function (data) {
                                                var resposta = data.split("||");
                                                $('.produtos').html(resposta[0]);
                                                $('.produtos-total').html(resposta[1]);
                                            },
                                        });

                                    });
                                } else {
                                    $('.action-hidden').show();

                                    Swal.fire({
                                        html: resposta[1],
                                        icon: 'error',
                                        buttonsStyling: false,
                                        confirmButtonText: 'OK, entendi!',
                                        customClass: {
                                            confirmButton: 'btn font-weight-bold btn-primary',
                                        },
                                    });
                                }
                            },
                            error: function (xhr, desc, err) {
                                $('.action-hidden').show();

                                Swal.fire({
                                    html: 'O Produto não foi adicionado!',
                                    icon: 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: 'OK, entendi!',
                                    customClass: {
                                        confirmButton: 'btn font-weight-bold btn-primary',
                                    },
                                });
                            },
                        });
                    } else {
                        $('.action-hidden').show();
                    }
                });
            }
        });
    };

    var _initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // Step 1

        _validations.push(
            FormValidation.formValidation(_formEl, {
                fields: {

                    codigo: {
                        validators: {
                            notEmpty: {
                                message: 'Código é Necessário',
                            },
                        },
                    },
                    ncm: {
                        validators: {
                            notEmpty: {
                                message: 'NCM é Necessário',
                            },
                        },
                    },
                    descricao: {
                        validators: {
                            notEmpty: {
                                message: 'Descrição é Necessário',
                            },
                        },
                    },
                    cstcson: {
                        validators: {
                            notEmpty: {
                                message: 'CST/CSON é Necessário',
                            },
                        },
                    },
                    qtd: {
                        validators: {
                            notEmpty: {
                                message: 'Quantidade é Necessário',
                            },
                        },
                    },
                    unid: {
                        validators: {
                            notEmpty: {
                                message: 'Unidade é Necessário',
                            },
                        },
                    },
                    orig: {
                        validators: {
                            notEmpty: {
                                message: 'Origem é Necessário',
                            },
                        },
                    },
                    valor: {
                        validators: {
                            notEmpty: {
                                message: 'Valor é Necessário',
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    }),
                },
            })
        );
    };

    return {
        // public functions
        init: function () {
            _wizardEl = KTUtil.getById('produtos_add');
            _formEl = KTUtil.getById('produtos_add_form');

            _initWizard();
            _initValidation();
        },
    };
})();

var KTprodutosEdit = (function () {
    // Base elements
    var _wizardEl;
    var _formEl;
    var _wizardObj;
    var _validations = [];

    // Private functions
    var _initWizard = function () {
        // Initialize form wizard
        _wizardObj = new KTWizard(_wizardEl, {
            startStep: 1, // initial active step number
            clickableSteps: false, // allow step clicking
        });

        // Change event
        _wizardObj.on('changed', function (wizard) {
            KTUtil.scrollTop();
        });

        // Submit event
        _wizardObj.on('submit', function (wizard) {
            $('.action-hidden').hide();

            var validator = _validations[0]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        var data = new FormData();

                        //Form data
                        var form_data = $('#produtos_edit_form').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        //Custom data
                        data.append('key', 'value');

                        $.ajax({
                            url: '../nota-fiscal/sql.php',
                            type: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status) {
                                //console.log(data);
                                var resposta = data.split('||');
                                if (resposta[0] == 'success') {
                                    Swal.fire({
                                        title: 'Atualizado!',
                                        text: 'Produto atualizado na nfe com sucesso!',
                                        icon: 'success',
                                        timer: 1000,
                                        onOpen: function () {
                                            Swal.showLoading();
                                        },
                                    }).then(function (result) {
                                        $('.action-hidden').show();

                                        $('#editProduto').modal('toggle');

                                        $('.ncm_search').val('');
                                        $('.ncm_search').val('');

                                        $('input[name=codigo]').val('');
                                        $('input[name=id_ncm]').val('');
                                        $('input[name=ncm]').val('');
                                        $('input[name=descricao]').val('');
                                        $('select[name=cstcson]').val('');
                                        $('select[name=orig]').val('');
                                        $('input[name=qtd]').val('');
                                        $('select[name=unid]').val('');
                                        $('input[name=valor]').val('');
                                        $('input[name=p_icms]').val('');
                                        $('input[name=p_ipi]').val('');
                                        $('input[name=enq_ipi]').val('');
                                        $('input[name=vl_ipi]').val('');

                                        $('.display-st').hide();

                                        $('.txt_st_icms').html('');
                                        $('.txt_st_pis_confins').html('');
                                        $('.txt_st_ipi').html('');

                                        var id_nfe = $('#id_nfe').val();

                                        $.ajax({
                                            url: '../nota-fiscal/sql.php',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                type: 'produtos_view_edit',
                                                id_nfe: id_nfe,
                                            },
                                            success: function (data) {
                                                var resposta = data.split("||");
                                                $('.produtos').html(resposta[0]);
                                                $('.produtos-total').html(resposta[1]);
                                            },
                                        });

                                        $('#EditProduto').modal('hide');

                                    });
                                } else {
                                    $('.action-hidden').show();

                                    Swal.fire({
                                        html: resposta[1],
                                        icon: 'error',
                                        buttonsStyling: false,
                                        confirmButtonText: 'OK, entendi!',
                                        customClass: {
                                            confirmButton: 'btn font-weight-bold btn-primary',
                                        },
                                    });
                                }
                            },
                            error: function (xhr, desc, err) {
                                $('.action-hidden').show();

                                Swal.fire({
                                    html: 'O Produto não foi atualizado!',
                                    icon: 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: 'OK, entendi!',
                                    customClass: {
                                        confirmButton: 'btn font-weight-bold btn-primary',
                                    },
                                });
                            },
                        });
                    } else {
                        $('.action-hidden').show();
                    }
                });
            }
        });
    };

    var _initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // Step 1

        _validations.push(
            FormValidation.formValidation(_formEl, {
                fields: {

                    codigo: {
                        validators: {
                            notEmpty: {
                                message: 'Código é Necessário',
                            },
                        },
                    },
                    ncm: {
                        validators: {
                            notEmpty: {
                                message: 'NCM é Necessário',
                            },
                        },
                    },
                    descricao: {
                        validators: {
                            notEmpty: {
                                message: 'Descrição é Necessário',
                            },
                        },
                    },
                    cstcson: {
                        validators: {
                            notEmpty: {
                                message: 'CST/CSON é Necessário',
                            },
                        },
                    },
                    qtd: {
                        validators: {
                            notEmpty: {
                                message: 'Quantidade é Necessário',
                            },
                        },
                    },
                    unid: {
                        validators: {
                            notEmpty: {
                                message: 'Unidade é Necessário',
                            },
                        },
                    },
                    orig: {
                        validators: {
                            notEmpty: {
                                message: 'Origem é Necessário',
                            },
                        },
                    },
                    valor: {
                        validators: {
                            notEmpty: {
                                message: 'Valor é Necessário',
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    }),
                },
            })
        );
    };

    return {
        // public functions
        init: function () {
            _wizardEl = KTUtil.getById('produtos_edit');
            _formEl = KTUtil.getById('produtos_edit_form');

            _initWizard();
            _initValidation();
        },
    };
})();

var KTDatatableCFOPServer = (function () {
    var initTable2 = function () {
        var table = $('#kt_datatable_cfops').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'Todos'],
            ],
            language: {
                url: '../assets/js/Portuguese-Brasil.json',
            },
            //info: false,
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: HOST_URL + 'nota-fiscal/cfops.php',
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: 0,
                    width: '70px',
                },
                {
                    targets: 2,
                    width: '70px',
                },
                {
                    className: 'text-center',
                    targets: -1,
                    title: 'Ações',
                    orderable: false,
                    width: '35px',
                    render: function (data, type, row) {
                        return (
                            '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-cfops" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
								<i class="la la-check-circle"></i>\
							</a>'
                        );
                    },
                },
            ],
        });
    };
    return {
        //main function to initiate the module
        init: function () {
            initTable2();
        },
    };
})();

var KTDatatableNCMServer = (function () {
    var initTable2 = function () {
        var table = $('#kt_datatable_ncms').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'Todos'],
            ],
            language: {
                url: '../assets/js/Portuguese-Brasil.json',
            },
            //info: false,
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: HOST_URL + 'nota-fiscal/ncms.php',
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: 0,
                    width: '70px',
                },
                {
                    targets: 2,
                    width: '70px',
                },
                {
                    className: 'text-center',
                    targets: -1,
                    title: 'Ações',
                    orderable: false,
                    width: '35px',
                    render: function (data, type, row) {
                        return (
                            '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-ncms" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
								<i class="la la-check-circle"></i>\
							</a>'
                        );
                    },
                },
            ],
        });
    };
    return {
        //main function to initiate the module
        init: function () {
            initTable2();
        },
    };
})();

var KTSelect2 = function () {
    // Private functions
    var demos = function () {
        // basic
        $('#cliente').select2({
            placeholder: "Selecione o Cliente",
            ajax: {
                url: HOST_URL + 'nota-fiscal/cliente.php',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.itemName,
                                id: item.id
                            }

                        })
                    };
                }
            }
        });

    }

    // Public functions
    return {
        init: function () {
            demos();
        }
    };
}();

function moedaParaNumero(valor) {
    return isNaN(valor) == false ? parseFloat(valor) : parseFloat(valor.replaceAll('.', '').replace(',', '.'));
}

function numeroParaMoeda(n, c, d, t, s, i, j) {
    (c = isNaN((c = Math.abs(c))) ? 2 : c), (d = d == undefined ? ',' : d), (t = t == undefined ? '.' : t), (s = n < 0 ? '-' : ''), (i = parseInt((n = Math.abs(+n || 0).toFixed(c))) + ''), (j = (j = i.length) > 3 ? j % 3 : 0);
    return (
        s +
        (j ? i.substr(0, j) + t : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) +
        (c
            ? d +
            Math.abs(n - i)
                .toFixed(c)
                .slice(2)
            : '')
    );
}

jQuery(document).ready(function () {
    KTNotaAdd.init();
    KTBootstrapDatepicker.init();
    KTSelect2.init();
    KTprodutosAdd.init();
    KTprodutosEdit.init();
    KTDatatableCFOPServer.init();
    KTDatatableNCMServer.init();

    $('.menu-item-submenu:nth-child(6)').addClass('menu-item-open menu-item-here');

    $(document).on('click', '#kt_search_cfops', function (e) {
        e.preventDefault();

        var params = {};

        $('.datatable-input-cfops').each(function () {
            var i = $(this).data('col-index');
            params[i] = $(this).val();
        });
        //console.log(params);
        $.each(params, function (i, val) {
            $('#kt_datatable_cfops')
                .DataTable()
                .column(i)
                .search(val ? val : '', false, false);
        });
        $('#kt_datatable_cfops').DataTable().table().draw();
    });

    $(document).on('click', '#kt_search_ncms', function (e) {
        e.preventDefault();

        var params = {};

        $('.datatable-input-ncms').each(function () {
            var i = $(this).data('col-index');
            params[i] = $(this).val();
        });
        //console.log(params);
        $.each(params, function (i, val) {
            $('#kt_datatable_ncms')
                .DataTable()
                .column(i)
                .search(val ? val : '', false, false);
        });
        $('#kt_datatable_ncms').DataTable().table().draw();
    });

    $(document).on('focusout', 'input[name=valor]', function (e) {

        if ($('input[name=qtd]').val() == "") {
            var qtd = 1;
            $('input[name=qtd]').val(1);
        } else {
            var qtd = moedaParaNumero($('input[name=qtd]').val());
        }

        var valor = $(this).val();

        if (valor != "") {
            var total = moedaParaNumero(valor) * qtd;
            $('.display-total').html('Valor Total: R$ ' + numeroParaMoeda(total));

            $('input[name=valor]').val(valor);
            $('input[name=total]').val(total);
        }
    });

    $(document).on('focusout', 'input[name=p_ipi]', function (e) {

        var porcent = $(this).val().slice(0, $(this).val().length - 1);

        if ($('input[name=qtd]').val() == "") {
            var qtd = 1;
            $('input[name=qtd]').val(1);
        } else {
            var qtd = moedaParaNumero($('input[name=qtd]').val());
        }

        var valor = $('input[name=valor]').val();

        if (valor != "") {
            var valorTotal = moedaParaNumero(valor) * qtd;

        }

        if (porcent != 0) {
            var ipi = (valorTotal / 100) * moedaParaNumero(porcent);

            var total = valorTotal + ipi;

            $('.display-total').html('Valor Total: R$ ' + numeroParaMoeda(total));

            $('input[name=vl_ipi]').val(numeroParaMoeda(ipi));
            $('input[name=total]').val(total);

        }

    });

    $(document).on('click', '.incluir-cfops', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'cfops_load',
                id: id,
            },
            success: function (data) {
                var resposta = data.split('|');

                $('input[name=id_cfop]').val(resposta[0]);
                $('input[name=cfop]').val(resposta[1]);

            },
        });
    });

    $(document).on('click', '.incluir-ncms', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'ncms_load',
                id: id,
            },
            success: function (data) {
                var resposta = data.split('|');

                $('input[name=id_ncm]').val(resposta[0]);
                $('input[name=ncm]').val(resposta[1]);
            },
        });
    });

    $(document).on('click', '.remover-produto', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Deseja remover?',
            html: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, remover!',
            cancelButtonText: 'Não, cancelar!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: HOST_URL + 'nota-fiscal/sql.php',
                    type: 'POST',
                    data: {
                        type: 'produtos_remove',
                        id: id,
                    },
                    success: function (data, status) {
                        //console.log(data);
                        var resposta = data.split('||');
                        if (resposta[0] == 'success') {
                            Swal.fire({
                                title: 'Removido!',
                                text: 'Produto removido com sucesso!',
                                icon: 'success',
                                timer: 1000,
                                onOpen: function () {
                                    Swal.showLoading();
                                },
                            }).then(function (result) {
                                var id_nfe = $('#id_nfe').val();

                                $.ajax({
                                    url: '../nota-fiscal/sql.php',
                                    type: 'POST',
                                    dataType: 'html',
                                    data: {
                                        type: 'produtos_view_edit',
                                        id_nfe: id_nfe,
                                    },
                                    success: function (data) {
                                        var resposta = data.split("||");
                                        $('.produtos').html(resposta[0]);
                                        $('.produtos-total').html(resposta[1]);
                                    },
                                });
                            });
                        } else {
                            Swal.fire({
                                html: resposta[1],
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'OK, entendi!',
                                customClass: {
                                    confirmButton: 'btn font-weight-bold btn-primary',
                                },
                            });
                        }
                    },
                });
            }
        });
    });

    $(document).on('click', '.EditProduto', function () {
        var id_produto = $(this).attr('id');
        $('#EditProduto').modal('show');

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'cfops_load_edit',
                id: id_produto,
            },
            success: function (data) {
                var resposta = data.split('|');

                $('.display-st').show();
                $('input[name=id_cfop]').val(resposta[0]);
                $('input[name=cfop]').val(resposta[1]);
                $('input[name=st_icms]').val(resposta[2]);
                $('.txt_st_icms').html(resposta[2]);
                $('input[name=st_ipi]').val(resposta[3]);
                $('.txt_st_ipi').html(resposta[3]);
                $('input[name=st_pis_confins]').val(resposta[4]);
                $('.txt_st_pis_confins').html(resposta[4]);
            },
        });

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'ncms_load_edit',
                id: id_produto,
            },
            success: function (data) {
                var resposta = data.split('|');

                $('input[name=id_ncm]').val(resposta[0]);
                $('input[name=ncm]').val(resposta[1]);
            },
        });

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'produtos_load_edit',
                id: id_produto,
            },
            success: function (data) {
                var resposta = data.split('|');

                $('input[name=id_produtoE]').val(resposta[0]);
                $('input[name=descricao]').val(resposta[1]);
                $('select[name=cstcson]').val(resposta[2]);
                $('input[name=qtd]').val(resposta[3]);
                $('select[name=unid]').val(resposta[4]);
                $('input[name=valor]').val(resposta[5]);
                $('input[name=p_icms]').val(resposta[6]);
                $('input[name=vl_ipi]').val(resposta[7]);
                $('input[name=p_ipi]').val(resposta[8]);
                $('input[name=enq_ipi]').val(resposta[9]);
                $('input[name=codigo]').val(resposta[10]);
                $('select[name=orig]').val(resposta[11]);

            },
        });
    });

    $(document).on('hide.bs.modal', '#EditProduto', function () {

        $('.display-st').hide();
        $('input[name=id_cfop]').val('');
        $('input[name=codigo]').val('');
        $('input[name=cfop]').val('');
        $('input[name=id_ncm]').val('');
        $('input[name=ncm]').val('');
        $('input[name=st_icms]').val('');
        $('.txt_st_icms').html('');
        $('input[name=st_ipi]').val('');
        $('.txt_st_ipi').html('');
        $('input[name=st_pis_confins]').val('');
        $('.txt_st_pis_confins').html('');
        $('input[name=descricao]').val('');
        $('select[name=cstcson]').val('');
        $('select[name=orig]').val('');
        $('input[name=qtd]').val('');
        $('select[name=unid]').val('');
        $('input[name=valor]').val('');
        $('input[name=p_icms]').val('');
        $('input[name=vl_ipi]').val('');
        $('input[name=p_ipi]').val('');
        $('input[name=enq_ipi]').val('');

    });

});
