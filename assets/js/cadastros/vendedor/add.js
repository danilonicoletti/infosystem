'use strict';

// Class definition
var KTVendedorAdd = (function () {
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

				validator.validate().then(function (status) {
					if (status == 'Valid') {
						var data = new FormData();

						//Form data
						var form_data = $('#vendedor_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: HOST_URL + 'cadastros/vendedor/sql.php',
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
										text: 'Vendedor inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'cadastros/vendedor';
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
									html: 'Vendedor não foi inserido!',
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
					nome: {
						validators: {
							notEmpty: {
								message: 'Nome é Necessário',
							},

						},
					},
					email: {
						validators: {
							notEmpty: {
								message: 'E-mail é Necessário',
							},
							emailAddress: {
								message: 'Preencha um e-mail valido!',
							},
						},
					},
					documento: {
						validators: {
							notEmpty: {
								message: 'Documento é Necessário',
							},
							id: {
								// The id validator is enabled by default
								enabled: true,
								country: 'BR',
								message: 'Preencha um CPF válido',
							},
							vat: {
								// The vat validator is disabled initially
								enabled: false,
								country: 'BR',
								message: 'Preencha um CNPJ válido',
							},
						},
					},
					celular: {
						validators: {
							notEmpty: {
								message: 'Celular é Necessário',
							},

						},
					},
					p_comissao: {
						validators: {
							notEmpty: {
								message: 'Comissão é Necessário',
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
			_wizardEl = KTUtil.getById('vendedor_add');
			_formEl = KTUtil.getById('vendedor_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();

jQuery(document).ready(function () {
	KTVendedorAdd.init();

	$('.menu-item-submenu:nth-child(1)').addClass('menu-item-open menu-item-here');
	$('.menu-cadastros .menu-item:nth-child(2)').addClass('menu-item-active');

});
