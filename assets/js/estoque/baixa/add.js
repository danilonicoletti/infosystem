'use strict';

// Class definition
var KTRomaneioAdd = (function () {
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
						var form_data = $('#baixa_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						$.ajax({
							url: HOST_URL + 'estoque/baixa/sql.php',
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
										text: 'Baixa de estoque inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'estoque/baixa';
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
									html: 'Baixa de estoque não foi inserido!',
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
					entrada: {
						validators: {
							notEmpty: {
								message: 'Entrada é Necessário',
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
			_wizardEl = KTUtil.getById('baixa_add');
			_formEl = KTUtil.getById('baixa_add_form');

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
var KTDatatableFornecedoresServer = (function () {
	var initTable2 = function () {
		var table = $('#kt_datatable_fornecedores').DataTable({
			pageLength: 5,
			lengthMenu: [
				[5, 10, 20, -1],
				[5, 10, 20, 'Todos'],
			],
			language: {
				url: '../../assets/js/Portuguese-Brasil.json',
			},
			//info: false,
			responsive: true,
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: HOST_URL + 'estoque/baixa/fornecedores.php',
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
							'<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-fornecedores" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
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
var KTDatatableProdutosServer = (function () {
	var initTable2 = function () {
		var table = $('#kt_datatable_produtos').DataTable({
			pageLength: 5,
			lengthMenu: [
				[5, 10, 20, -1],
				[5, 10, 20, 'Todos'],
			],
			language: {
				url: '../../assets/js/Portuguese-Brasil.json',
			},
			//info: false,
			responsive: true,
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: HOST_URL + 'estoque/baixa/produtos.php',
			order: [[0, 'asc']],
			columnDefs: [
				{
					targets: 0,
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
							'<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-produtos" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
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
	KTRomaneioAdd.init();
	KTBootstrapDatepicker.init();
	KTDatatableFornecedoresServer.init();
	KTDatatableProdutosServer.init();

	$('.menu-item-submenu:nth-child(3)').addClass('menu-item-open menu-item-here');
	$('.menu-estoque .menu-item:nth-child(3)').addClass('menu-item-active');

	$(document).on('click', '#kt_search_fornecedores', function (e) {
		e.preventDefault();

		var params = {};

		$('.datatable-input-fornecedores').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		//console.log(params);
		$.each(params, function (i, val) {
			$('#kt_datatable_fornecedores')
				.DataTable()
				.column(i)
				.search(val ? val : '', false, false);
		});
		$('#kt_datatable_fornecedores').DataTable().table().draw();
	});

	$(document).on('click', '.incluir-fornecedores', function (e) {
		e.preventDefault();

		var id = $(this).attr('id');

		$.ajax({
			url: '../baixa/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'fornecedores_load',
				id: id,
			},
			success: function (data) {
				var resposta = data.split('|');

				$('input[name=id_fornecedor]').val(resposta[0]);
				$('input[name=fornecedor]').val(resposta[1]);

			},
		});
	});

	$(document).on('click', '#kt_search_produtos', function (e) {
		e.preventDefault();

		var params = {};

		$('.datatable-input-produtos').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		//console.log(params);
		$.each(params, function (i, val) {
			$('#kt_datatable_produtos')
				.DataTable()
				.column(i)
				.search(val ? val : '', false, false);
		});
		$('#kt_datatable_produtos').DataTable().table().draw();
	});

	$(document).on('click', '.incluir-produtos', function (e) {
		e.preventDefault();

		var id = $(this).attr('id');
		var id_baixa = $('#id_baixa').val();

		$.ajax({
			url: '../baixa/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'produtos_add',
				id: id,
				id_baixa: id_baixa
			},
			success: function (data) {

				var resposta = data.split('||');
				if (resposta[0] == 'success') {
					var id_baixa = $('#id_baixa').val();

					$.ajax({
						url: '../baixa/sql.php',
						type: 'POST',
						dataType: 'html',
						data: {
							type: 'produtos_view',
							id_baixa: id_baixa,
						},
						success: function (data) {
							$('.produtos').html(data);
						},
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
	});


});
