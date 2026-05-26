'use strict';

// Class definition
var KTMotivoDeCancelamentoAdd = (function () {
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
						var form_data = $('#motivo_de_cancelamento_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: HOST_URL + 'cadastros/motivo-de-cancelamento/sql.php',
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
										text: 'Motivo de Cancelamento inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'cadastros/motivo-de-cancelamento';
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
									html: 'Motivo de Cancelamento não foi inserido!',
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
					descricao: {
						validators: {
							notEmpty: {
								message: 'Descrição é Necessário',
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
			_wizardEl = KTUtil.getById('motivo_de_cancelamento_add');
			_formEl = KTUtil.getById('motivo_de_cancelamento_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();

jQuery(document).ready(function () {
	KTMotivoDeCancelamentoAdd.init();

	$('.menu-item-submenu:nth-child(1)').addClass('menu-item-open menu-item-here');
	$('.menu-cadastros .menu-item:nth-child(6)').addClass('menu-item-active');
});
