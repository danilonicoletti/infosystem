'use strict';

// Class definition
var KTOrcamentoEdit = (function () {
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
                        var form_data = $('#orcamento_edit_form').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        //Custom data
                        data.append('key', 'value');

                        $.ajax({
                            url: HOST_URL + 'vendas/orcamento/sql.php',
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
                                        text: 'Orçamento atualizado com sucesso!',
                                        icon: 'success',
                                        timer: 1000,
                                        onOpen: function () {
                                            Swal.showLoading();
                                        },
                                    }).then(function (result) {
                                        window.location.href = HOST_URL + 'vendas/orcamento';
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
                                    html: 'Orçamento não foi atualizado!',
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
                    fields: {
                        os: {
                            validators: {
                                notEmpty: {
                                    message: 'OS é Necessário',
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
                        vendedor: {
                            validators: {
                                notEmpty: {
                                    message: 'Vendedor é Necessário',
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
                        obra: {
                            validators: {
                                notEmpty: {
                                    message: 'Obra é Necessário',
                                },

                            },
                        },
                        prazo: {
                            validators: {
                                notEmpty: {
                                    message: 'Prazo é Necessário',
                                },

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
            _wizardEl = KTUtil.getById('orcamento_edit');
            _formEl = KTUtil.getById('orcamento_edit_form');

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
    //KTOrcamentoEdit.init();

    $('.menu-item-submenu:nth-child(6)').addClass('menu-item-open menu-item-here');


    $(document).on('change', '#nfe', function (e) {
        e.preventDefault();
        var id = $(this).val();

        Swal.fire({
            title: 'Deseja importar a nota, para carta de correção?',
            html: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, importar!',
            cancelButtonText: 'Não, cancelar!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: HOST_URL + 'nota-fiscal/sql.php',
                    type: 'POST',
                    data: {
                        type: 'carta_correcao',
                        id: id,
                    },
                    success: function (data, status) {
                        var resposta = data.split('||');

                        $('input[name=dt_emissao]').val(resposta[0]);
                        $('input[name=emissor]').val(resposta[1]);
                        $('input[name=cliente]').val(resposta[2]);
                        $('input[name=frete]').val(resposta[3]);
                        $('input[name=cfop]').val(resposta[4]);
                        $('input[name=id]').val(resposta[5]);

                        $('.corrigir').show();

                    },
                });
            }
        });

    });

    $(document).on('click', '.corrigir', function (e) {
        e.preventDefault();

        var id = $('input[name=id]').val();
        var correcao = $('input[name=correcao]').val();

        if (correcao.length <= 5) {
            Swal.fire({
                title: 'Não é possível enviar!',
                text: 'A informação precisar ter mais que 5 caracteres!',
                icon: 'error',
                timer: 1000,
                onOpen: function () {
                    Swal.showLoading();
                },
            });
        } else {
            if (correcao == "" || id == "") {
                Swal.fire({
                    title: 'Erro ao Corrigir!',
                    text: 'A tela sera atualiza, tente novamente!',
                    icon: 'error',
                    timer: 1000,
                    onOpen: function () {
                        Swal.showLoading();
                    },
                }).then(function (result) {
                    // window.location.reload();
                });
            } else {
                $.ajax({
                    url: '../nota-fiscal/carta-correcao-nfe.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        id: id,
                        correcao: correcao
                    },
                    success: function (data) {
                        var resposta = data.split('||');
                        if (resposta[0] == 'success') {
                            Swal.fire({
                                title: 'Corrigida!',
                                text: 'Nota Fiscal corrigida. Nº Protocolo ' + resposta[1],
                                icon: 'success',
                                timer: 1000,
                                onOpen: function () {
                                    Swal.showLoading();
                                },
                            }).then(function (result) {
                                // window.location.reload();
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
                            }).then(function (result) {
                                // window.location.reload();
                            });
                        }

                    },
                });
            }
        }

    });

});
