'use strict';

// Class definition
var KTEmpresaAdd = (function () {
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
            var validator = _validations[0];

            if (validator) {

                if ($("select[name=pessoa]").val() == 'fisica') {

                    _validations[0].disableValidator('nome_responsavel');
                    _validations[0].disableValidator('sobrenome_responsavel');
                } else {
                    _validations[0].enableValidator('nome_responsavel');
                    _validations[0].enableValidator('sobrenome_responsavel');
                }

                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        alert(HOST_URL + 'create-company/sql.php');

                        var data = new FormData();

                        //Form data
                        var form_data = $('#empresa_add_form').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        //Custom data
                        data.append('key', 'value');
                        data.append("logotipo", document.getElementById('logotipo').files[0]);

                        $.ajax({
                            url: HOST_URL + 'create-company/sql.php',
                            type: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status) {
                                console.log(data);

                                var resposta = data.split('||');
                                if (resposta[0] == 'success') {
                                    Swal.fire({
                                        title: 'Seja bem vindo!',
                                        text: 'Empresa criada com sucesso',
                                        icon: 'success',
                                        timer: 1000,
                                        onOpen: function () {
                                            Swal.showLoading();
                                        },
                                    }).then(function (result) {
                                        window.location.href = HOST_URL;
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
                                    title: 'Opsss!',
                                    text: 'Sua empresa não foi criada, entre em contato com fornecedor do sistema para mais esclarecimentos!',
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
        _validations.push(
            FormValidation.formValidation(_formEl, {
                fields: {
                    pessoa: {
                        validators: {
                            notEmpty: {
                                message: 'Pessoa é Necessário',
                            },

                        },
                    },

                    fantasia: {
                        validators: {
                            notEmpty: {
                                message: 'Nome Fantasia é Necessário',
                            },

                        },
                    },
                    social: {
                        validators: {
                            notEmpty: {
                                message: 'Razão Social é Necessário',
                            },

                        },
                    },
                    documento: {
                        validators: {
                            notEmpty: {
                                message: 'Documento é Necessário',
                            },
                            id: {
                                enabled: true,
                                country: 'BR',
                                message: 'Preencha um CPF válido',
                            },
                            vat: {
                                enabled: false,
                                country: 'BR',
                                message: 'Preencha um CNPJ válido',
                            },
                        },
                    },
                    telefone: {
                        validators: {
                            notEmpty: {
                                message: 'Telefone é Necessário',
                            },

                        },
                    },
                    crt: {
                        validators: {
                            notEmpty: {
                                message: 'Regime Tributário é Necessário',
                            },

                        },
                    },
                    nome_responsavel: {
                        validators: {
                            notEmpty: {
                                message: 'Nome do Responsável é Necessário',
                            },

                        },
                    },
                    sobrenome_responsavel: {
                        validators: {
                            notEmpty: {
                                message: 'Sobrenome do Responsável é Necessário',
                            },

                        },
                    },
                    logotipo: {
                        validators: {
                            notEmpty: {
                                message: 'Logotipo é Necessário',
                            },

                        },
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email é Necessário'
                            },
                            emailAddress: {
                                message: 'Email inserido não é um endereço de email válido'
                            },
                            remote: {
                                message: 'Email invalido, ou já existe!',
                                method: 'POST',
                                url: '../create-company/email.remote.php',
                            },
                        }
                    },
                    senha: {
                        validators: {
                            notEmpty: {
                                message: 'A senha é obrigatória e não pode estar vazia'
                            }
                        }
                    },
                    confirmar: {
                        validators: {
                            notEmpty: {
                                message: 'Confirmar senha é obrigatória e não pode estar vazio'
                            },
                            identical: {
                                compare: function () {
                                    return _formEl.querySelector('[name="senha"]').value;
                                },
                                message: 'A senha e sua confirmação devem ser as mesmas',
                            }
                        }
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

    document.querySelector('[name="documento"]').addEventListener('keyup', function (e) {
        switch (e.target.value.length) {
            // User is trying to put a VAT number
            case 18:
                _validations[0]
                    // Disable the id validator
                    .disableValidator('documento', 'id')
                    // Enable the vat one
                    .enableValidator('documento', 'vat')
                    // Revalidate field
                    .revalidateField('documento');
                break;

            // User is trying to put an ID number
            case 14:
            default:
                _validations[0].enableValidator('documento', 'id').disableValidator('documento', 'vat').revalidateField('documento');
                break;
        }
    });

    return {
        // public functions
        init: function () {
            _wizardEl = KTUtil.getById('empresa_add');
            _formEl = KTUtil.getById('empresa_add_form');

            _initWizard();
            _initValidation();
        },
    };
})();

jQuery(document).ready(function () {
    KTEmpresaAdd.init();

    $(document).on('change', 'select[name=pessoa]', function (e) {
        e.preventDefault();

        var valor = $(this).val();

        if (valor == 'juridica') {
            $('.alter-name-1').html('Nome Fantasia');
            $('.alter-name-1').attr('placeholder', 'Nome Fantasia');

            $('.alter-name-2').html('Nome');
            $('.alter-name-2').attr('placeholder', 'Nome');

            $('.remove-required').show();
        }
        if (valor == 'fisica') {
            $('.alter-name-1').html('Nome');
            $('.alter-name-1').attr('placeholder', 'Nome');

            $('.alter-name-2').html('Sobrenome');
            $('.alter-name-2').attr('placeholder', 'Sobrenome');

            $('.remove-required').hide();

        }
    });

    //$('.menu-cadastros .menu-item:nth-child(2)').addClass('menu-item-active');

});
