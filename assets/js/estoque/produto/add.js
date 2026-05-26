'use strict';

// Class definition
var KTProdutoAdd = (function () {
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
						var form_data = $('#produto_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: HOST_URL + 'estoque/produto/sql.php',
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
										text: 'Produto inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'estoque/produto';
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
									html: 'Produto não foi inserido!',
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
					fornecedor: {
						validators: {
							notEmpty: {
								message: 'Fornecedor é Necessário',
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
					especie: {
						validators: {
							notEmpty: {
								message: 'Espécie é Necessário',
							},
						},
					},
					origem: {
						validators: {
							notEmpty: {
								message: 'Origem é Necessário',
							},
						},
					},
					formato: {
						validators: {
							notEmpty: {
								message: 'Formato é Necessário',
							},
						},
					},
					estado: {
						validators: {
							notEmpty: {
								message: 'Estado é Necessário',
							},
						},
					},
					espessura: {
						validators: {
							notEmpty: {
								message: 'Espessura é Necessário',
							},
						},
					},
					moeda: {
						validators: {
							notEmpty: {
								message: 'Moeda é Necessário',
							},
						},
					},
					unidade: {
						validators: {
							notEmpty: {
								message: 'Unidade é Necessário',
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
			_wizardEl = KTUtil.getById('produto_add');
			_formEl = KTUtil.getById('produto_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();

var KTDatatableNCMServer = (function () {
	var initTable2 = function () {
		var table = $('#kt_datatable_ncm').DataTable({
			pageLength: 5,
			lengthMenu: [
				[5, 10, 20, -1],
				[5, 10, 20, 'Todos'],
			],
			language: {
				url: HOST_URL + 'assets/js/Portuguese-Brasil.json',
			},
			//info: false,
			responsive: true,
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: HOST_URL + 'estoque/produto/ncm.php',
			order: [[1, 'asc']],

			columnDefs: [
				{
					targets: 0,
					width: '100px',
				},
				{
					targets: 2,
					width: '100px',
				},
				{
					className: 'text-center',
					targets: -1,
					title: 'Ações',
					orderable: false,
					width: '40px',
					render: function (data, type, row) {
						return ('<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-ncm" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
									<i class="la la-check-circle"></i>\
								</a>');
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

jQuery(document).ready(function () {
	KTProdutoAdd.init();
	KTDatatableNCMServer.init();

	$('.menu-item-submenu:nth-child(3)').addClass('menu-item-open menu-item-here');
	$('.menu-estoque .menu-item:nth-child(1)').addClass('menu-item-active');

	$(document).on('click', '#kt_search_ncm', function (e) {
		e.preventDefault();

		var params = {};

		$('.datatable-input-ncm').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		//console.log(params);
		$.each(params, function (i, val) {
			$('#kt_datatable_ncm')
				.DataTable()
				.column(i)
				.search(val ? val : '', false, false);
		});
		$('#kt_datatable_ncms').DataTable().table().draw();
	});

	$(document).on('click', '.incluir-ncm', function (e) {
		e.preventDefault();

		var id = $(this).attr('id');

		$.ajax({
			url: HOST_URL + 'estoque/produto/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'ncm_load',
				id: id,
			},
			success: function (data) {
				var resposta = data.split('|');

				$('input[name=id_ncm]').val(resposta[0]);
				$('input[name=ncm]').val(resposta[1]);
			},
		});
	});
});
