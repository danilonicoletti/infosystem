'use strict';

// Class definition
var KTCFOPAdd = (function () {
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
						var form_data = $('#cfop_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: HOST_URL + 'cadastros/cfop/sql.php',
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
										text: 'CFOP inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'cadastros/cfop';
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
									html: 'CFOP não foi inserido!',
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
					codigo: {
						validators: {
							notEmpty: {
								message: 'Código é Necessário',
							},
						},
					},
					cfop: {
						validators: {
							notEmpty: {
								message: 'CFOP é Necessário',
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
					icms: {
						validators: {
							notEmpty: {
								message: 'ICMS é Necessário',
							},
						},
					},
					ipi: {
						validators: {
							notEmpty: {
								message: 'IPI é Necessário',
							},
						},
					},
					cofins: {
						validators: {
							notEmpty: {
								message: 'Cofins é Necessário',
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
			_wizardEl = KTUtil.getById('cfop_add');
			_formEl = KTUtil.getById('cfop_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();

jQuery(document).ready(function () {
	KTCFOPAdd.init();

	$('.menu-item-submenu:nth-child(1)').addClass('menu-item-open menu-item-here');
	$('.menu-cadastros .menu-item:nth-child(8)').addClass('menu-item-active');
});
