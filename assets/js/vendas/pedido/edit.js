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
			var validator = _validations[0];

			if (validator) {
				if ($("input[name=sem]").is(':checked') == false) {
					_validations[0].disableValidator('clientesem');
					_validations[0].enableValidator('cliente');
				} else {
					_validations[0].enableValidator('clientesem');
					_validations[0].disableValidator('cliente');
				}
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

						//console.log(form_data);

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
					clientesem: {
						validators: {
							notEmpty: {
								message: 'Cliente é Necessário',
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
var KTmateriaisAdd = (function () {
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

			var validator = _validations[0];

			if (validator) {
				validator.validate().then(function (status) {
					if (status == 'Valid') {

						var largura = moedaParaNumero($('.largura').val());
						var altura = moedaParaNumero($('.altura').val());

						var metro = largura * altura;

						$('input[name="metro"]').val(Number(metro).toFixed(3).replace('.', ','));

						var data = new FormData();

						//Form data
						var form_data = $('#materiais_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: '../orcamento/sql.php',
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
										text: 'Material inserido com sucesso!',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										$('.action-hidden').show();

										$('#addMaterial').modal('toggle');

										$('.codigo_search').val('');
										$('.descricao_search').val('');
										$('.nome_search').val('');

										$('input[name=id_material').val('');
										$('input[name=material]').val('');
										$('input[name=id_tipo').val('');
										$('input[name=tipo]').val('');
										$('input[name=coeficiente]').val('');
										$('textarea[name=descricao]').val('');
										$('input[name=qtd]').val('');
										$('select[name=unid]').val('');
										$('input[name=largura]').val('');
										$('input[name=altura]').val('');
										$('input[name=metro]').val('');
										$('input[name=acabamento]').val('');
										$('input[name=outros]').val('');
										$('input[name=acrescimo]').val('');
										$('input[name=decrescimo]').val('');

										var id_orcamento = $('#id_orcamento').val();

										$.ajax({
											url: '../orcamento/sql.php',
											type: 'POST',
											dataType: 'html',
											data: {
												type: 'materiais_view_edit',
												id_orcamento: id_orcamento,
											},
											success: function (data) {
												$('.materiais').html(data);
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
									html: 'O Material não foi adicionado!',
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
					material: {
						validators: {
							notEmpty: {
								message: 'Material é Necessário',
							},
						},
					},
					tipo: {
						validators: {
							notEmpty: {
								message: 'Tipo é Necessário',
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
					largura: {
						validators: {
							notEmpty: {
								message: 'Largura é Necessário',
							},
						},
					},
					altura: {
						validators: {
							notEmpty: {
								message: 'Altura é Necessário',
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
			_wizardEl = KTUtil.getById('materiais_add');
			_formEl = KTUtil.getById('materiais_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();
var KTmateriaisEdit = (function () {
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

		// Submit event
		_wizardObj.on('submit', function (wizard) {
			$('.action-hidden').hide();

			var validator = _validations[0];

			if (validator) {

				validator.validate().then(function (status) {
					if (status == 'Valid') {

						var larguraE = moedaParaNumero($('.larguraE').val());
						var alturaE = moedaParaNumero($('.alturaE').val());

						var metroE = larguraE * alturaE;

						$('input[name="metroE"]').val(Number(metroE).toFixed(3).replace('.', ','));

						var data = new FormData();

						//Form data
						var form_data = $('#materiais_edit_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						//Custom data
						data.append('key', 'value');

						$.ajax({
							url: '../orcamento/sql.php',
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
										text: 'Material atualizado com sucesso!',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										$('.action-hidden').show();

										$('textarea[name=descricaoE]').val('');
										$('input[name=qtdE]').val('');
										$('select[name=unidE]').val('');
										$('input[name=larguraE]').val('');
										$('input[name=alturaE]').val('');
										$('input[name=metroE]').val('');
										$('input[name=acabamentoE]').val('');
										$('input[name=outrosE]').val('');
										$('input[name=acrescimoE]').val('');
										$('input[name=decrescimoE]').val('');

										$('input[name=id_tipoE]').val('');
										$('input[name=id_tipo]').val('');

										$('input[name=tipoE]').val('');
										$('input[name=tipo]').val('');

										$('input[name=id_materialE]').val('');
										$('input[name=id_material]').val('');

										$('input[name=materialE]').val('');
										$('input[name=material]').val('');

										$('input[name=coeficienteE]').val('');
										$('input[name=coeficiente]').val('');

										var id_orcamento = $('#id_orcamento').val();

										$.ajax({
											url: '../orcamento/sql.php',
											type: 'POST',
											dataType: 'html',
											data: {
												type: 'materiais_view_edit',
												id_orcamento: id_orcamento,
											},
											success: function (data) {
												$('.materiais').html(data);
											},
										});

										$('#EditMaterial').modal('hide');

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
									html: 'O Material não foi atualizado!',
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

					materialE: {
						validators: {
							notEmpty: {
								message: 'Material é Necessário',
							},
						},
					},
					// areaE: {
					// 	validators: {
					// 		notEmpty: {
					// 			message: 'Área é Necessário',
					// 		},
					// 	},
					// },

					qtdE: {
						validators: {
							notEmpty: {
								message: 'Quantidade é Necessário',
							},
						},
					},
					unidE: {
						validators: {
							notEmpty: {
								message: 'Unidade é Necessário',
							},
						},
					},
					larguraE: {
						validators: {
							notEmpty: {
								message: 'Largura é Necessário',
							},
						},
					},
					alturaE: {
						validators: {
							notEmpty: {
								message: 'Altura é Necessário',
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
			_wizardEl = KTUtil.getById('materiais_edit');
			_formEl = KTUtil.getById('materiais_edit_form');

			_initWizard();
			_initValidation();
		},
	};
})();
var KTDatatableMateriaisServer = (function () {
	var initTable2 = function () {
		var table = $('#kt_datatable_materiais').DataTable({
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
			ajax: HOST_URL + 'vendas/orcamento/materiais.php',
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
							'<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-materiais" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
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
var KTDatatableTiposServer = (function () {
	var initTable2 = function () {
		var table = $('#kt_datatable_tipos').DataTable({
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
			ajax: HOST_URL + 'vendas/orcamento/tipos.php',
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
							'<a href="javascript:;" class="btn btn-sm btn-clean btn-icon incluir-tipos" data-dismiss="modal" aria-label="Close" title="Incluir" id="' + data + '">\
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
		$('#vendedor').select2({
			placeholder: "Selecione o Vendedor",
			ajax: {
				url: HOST_URL + 'vendas/orcamento/vendedor.php',
				dataType: "json",
				type: "GET",
				data: function (params) {

					var queryParameters = {
						term: params.term
					}
					return queryParameters;
				},
				processResults: function (data) {
					console.log(data);
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

		var id_vendedor = $('#id_vendedor').val();
		var nome_vendedor = $('#nome_vendedor').val();

		$("#vendedor").append($("<option />")
			.attr("value", id_vendedor)
			.html(nome_vendedor)
		).val(id_vendedor).trigger("change");

		$('#cliente').select2({
			placeholder: "Selecione o Cliente",
			ajax: {
				url: HOST_URL + 'vendas/orcamento/cliente.php',
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

		var id_cliente = $('#id_cliente').val();
		var nome_cliente = $('#nome_cliente').val();

		$("#cliente").append($("<option />")
			.attr("value", id_cliente)
			.html(nome_cliente)
		).val(id_cliente).trigger("change");

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
				.slice(3)
			: '')
	);
}

jQuery(document).ready(function () {
	KTOrcamentoEdit.init();
	KTmateriaisAdd.init();
	KTmateriaisEdit.init();
	KTDatatableMateriaisServer.init();
	KTDatatableTiposServer.init();
	KTSelect2.init();

	if ($('input[name="sem"]').is(':checked')) {
		$('.comCliente').hide();
		$('.semCliente').show();
		$('input[name=telefone]').prop('readonly', false);
		$('input[name=email]').prop('readonly', false);
	} else {
		$('.comCliente').show();
		$('.semCliente').hide();
		$('input[name=telefone]').prop('readonly', true);
		$('input[name=email]').prop('readonly', true);
	}

	$(document).on('click', 'input[name="sem"]', function () {
		// e.preventDefault();

		if ($(this).is(':checked')) {
			$('.comCliente').hide();
			$('.semCliente').show();
			$('input[name=telefone]').prop('readonly', false);
			$('input[name=email]').prop('readonly', false);
		} else {
			$('.comCliente').show();
			$('.semCliente').hide();
			$('input[name=telefone]').prop('readonly', true);
			$('input[name=email]').prop('readonly', true);
		}
	});

	$('.menu-item-submenu:nth-child(4)').addClass('menu-item-open menu-item-here');
	$('.menu-vendas .menu-item:nth-child(2)').addClass('menu-item-active');

	$('#cliente').on('select2:select', function (e) {
		var data = e.params.data;

		$.ajax({
			url: HOST_URL + 'vendas/orcamento/sql.php',
			type: 'POST',
			data: {
				type: 'load_cliente',
				id: data.id,
			},
			success: function (data, status) {
				//console.log(data);
				var resposta = data.split('||');

				$('input[name=telefone]').val(resposta[0]);
				$('input[name=email]').val(resposta[1]);

				$('input[name=telefone]').prop('readonly', true);
				$('input[name=email]').prop('readonly', true);
			},
		});

	});

	$(document).on('click', '#kt_search_materiais', function (e) {
		e.preventDefault();

		var params = {};

		$('.datatable-input-materiais').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		//console.log(params);
		$.each(params, function (i, val) {
			$('#kt_datatable_materiais')
				.DataTable()
				.column(i)
				.search(val ? val : '', false, false);
		});
		$('#kt_datatable_materiais').DataTable().table().draw();
	});

	$(document).on('click', '#kt_search_tipos', function (e) {
		e.preventDefault();

		var params = {};

		$('.datatable-input-tipos').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		//console.log(params);
		$.each(params, function (i, val) {
			$('#kt_datatable_tipos')
				.DataTable()
				.column(i)
				.search(val ? val : '', false, false);
		});
		$('#kt_datatable_tipos').DataTable().table().draw();
	});

	$(document).on('click', '.copiar', function (e) {

		var textArea = $('textarea[name=descricaoE]').val();
		//alert(textArea);
		// textArea.focus();
		// document.execCommand('copy');
		navigator.clipboard.writeText(textArea);

	});
	$(document).on('click', '.incluir-materiais', function (e) {
		e.preventDefault();

		var id = $(this).attr('id');

		if (id == 1) {
			$('input[name=valor]').prop('readonly', false);
			$('input[name=valorE]').prop('readonly', false);
		} else {
			$('input[name=valor]').prop('readonly', true);
			$('input[name=valorE]').prop('readonly', true);
		}
		$.ajax({
			url: '../orcamento/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'materiais_load',
				id: id,
			},
			success: function (data) {
				var resposta = data.split('|');

				$('input[name=id_material]').val(resposta[0]);
				$('input[name=material]').val(resposta[1]);
				$('input[name=valor]').val(Number(resposta[2]).toFixed(2).replace('.', ','));
				$('select[name=unid]').val(resposta[3]);
				$('input[name=observacao]').val(resposta[4]);

				$('input[name=id_materialE]').val(resposta[0]);
				$('input[name=materialE]').val(resposta[1]);
				$('input[name=valorE]').val(Number(resposta[2]).toFixed(2).replace('.', ','));
				$('select[name=unidE]').val(resposta[3]);
				$('input[name=observacaoE]').val(resposta[4]);
			},
		});
	});
	$(document).on('click', '.incluir-tipos', function (e) {
		e.preventDefault();

		var id = $(this).attr('id');

		$.ajax({
			url: '../orcamento/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'tipos_load',
				id: id,
			},
			success: function (data) {
				var resposta = data.split('|');

				$('input[name=id_tipo]').val(resposta[0]);
				$('input[name=tipo]').val(resposta[1]);
				$('input[name=coeficiente]').val(resposta[2]);

				$('input[name=id_tipoE]').val(resposta[0]);
				$('input[name=tipoE]').val(resposta[1]);
				$('input[name=coeficienteE]').val(resposta[2]);

			},
		});
	});

	$(document).on('focusout', 'input[name=acrescimo]', function (e) {
		if ($(this).val() != "") {
			$('input[name=decrescimo]').prop('disabled', true);
		} else {
			$('input[name=decrescimo]').prop('disabled', false);
		}
	});

	$(document).on('focusout', 'input[name=decrescimo]', function (e) {
		if ($(this).val() != "") {
			$('input[name=acrescimo]').prop('disabled', true);
		} else {
			$('input[name=acrescimo]').prop('disabled', fase);
		}
	});

	$(document).on('click', '.RemoverMaterial', function (e) {
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
					url: HOST_URL + 'vendas/orcamento/sql.php',
					type: 'POST',
					data: {
						type: 'materiais_remove',
						id: id,
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Removido!',
								text: 'Material removido com sucesso!',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								},
							}).then(function (result) {
								var id_orcamento = $('#id_orcamento').val();

								$.ajax({
									url: '../orcamento/sql.php',
									type: 'POST',
									dataType: 'html',
									data: {
										type: 'materiais_view_edit',
										id_orcamento: id_orcamento,
									},
									success: function (data) {
										$('.materiais').html(data);
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

	$(document).on('click', '.CopiarMaterial', function (e) {
		e.preventDefault();
		var id = $(this).attr('id');
		Swal.fire({
			title: 'Deseja copiar?',
			html: '',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim, copiar!',
			cancelButtonText: 'Não, cancelar!',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: HOST_URL + 'vendas/orcamento/sql.php',
					type: 'POST',
					data: {
						type: 'materiais_copiar',
						id: id,
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Copiado!',
								text: 'Material copiado com sucesso!',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								}
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

	$(document).on('click', '.ColarMaterial', function (e) {
		e.preventDefault();
		var id = $(this).attr('id');
		Swal.fire({
			title: 'Deseja colar?',
			html: '',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim, colar!',
			cancelButtonText: 'Não, cancelar!',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: HOST_URL + 'vendas/orcamento/sql.php',
					type: 'POST',
					data: {
						type: 'materiais_colar',
						id: id,
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Colado!',
								text: 'Material colado com sucesso!',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								}
							}).then(function (result) {
								var id_orcamento = $('#id_orcamento').val();

								$.ajax({
									url: '../orcamento/sql.php',
									type: 'POST',
									dataType: 'html',
									data: {
										type: 'materiais_view_edit',
										id_orcamento: id_orcamento,
									},
									success: function (data) {
										$('.materiais').html(data);
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

	$(document).on('click', '.DuplicarMaterial', function (e) {
		e.preventDefault();
		var id = $(this).attr('id');
		Swal.fire({
			title: 'Deseja duplicar?',
			html: '',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sim, duplicar!',
			cancelButtonText: 'Não, cancelar!',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: HOST_URL + 'vendas/orcamento/sql.php',
					type: 'POST',
					data: {
						type: 'materiais_duplicar',
						id: id,
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Duplicado!',
								text: 'Material duplicado com sucesso!',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								},
							}).then(function (result) {
								var id_orcamento = $('#id_orcamento').val();

								$.ajax({
									url: '../orcamento/sql.php',
									type: 'POST',
									dataType: 'html',
									data: {
										type: 'materiais_view_edit',
										id_orcamento: id_orcamento,
									},
									success: function (data) {
										$('.materiais').html(data);
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

	$(document).on('focusout', '.altura', function () {

		var largura = moedaParaNumero($('.largura').val());
		var altura = moedaParaNumero($('.altura').val());

		var metro = largura * altura;

		$('input[name="metro"]').val(Number(metro).toFixed(3).replace('.', ','));

	});

	$(document).on('focusout', '.alturaE', function () {

		var larguraE = moedaParaNumero($('.larguraE').val());
		var alturaE = moedaParaNumero($('.alturaE').val());

		var metroE = larguraE * alturaE;

		$('input[name="metroE"]').val(Number(metroE).toFixed(3).replace('.', ','));

	});
	$(document).on('click', '.EditMaterial', function () {
		var id_produto = $(this).attr('id');
		$('#EditMaterial').modal('show');

		$.ajax({
			url: '../orcamento/sql.php',
			type: 'POST',
			dataType: 'html',
			data: {
				type: 'materiais_load_edit',
				id: id_produto,
			},
			success: function (data) {
				var resposta = data.split('|');

				$('input[name=id_materialE]').val(resposta[0]);
				$('input[name=materialE]').val(resposta[1]);

				if (resposta[0] == 1) {
					$('input[name=valor]').prop('readonly', false);
					$('input[name=valorE]').prop('readonly', false);
				} else {
					$('input[name=valor]').prop('readonly', true);
					$('input[name=valorE]').prop('readonly', true);
				}

				$('input[name=coeficienteE]').val(resposta[17]);
				$('input[name=valorE]').val(Number(resposta[2]).toFixed(2).replace('.', ','));
				$('select[name=unidE]').val(resposta[3]);
				$('input[name=observacaoE]').val(resposta[4]);
				$('textarea[name=descricaoE]').val(resposta[6]);
				$('input[name=qtdE]').val(Number(resposta[7]).toFixed(2).replace('.', ','));
				$('input[name=larguraE]').val(resposta[14]);
				$('input[name=alturaE]').val(resposta[15]);
				$('input[name=metroE]').val(resposta[8]);
				$('input[name=acabamentoE]').val(resposta[9]);
				$('input[name=outrosE]').val(resposta[10]);
				$('input[name=acrescimoE]').val(resposta[11]);
				$('input[name=decrescimoE]').val(resposta[12]);
				$('input[name=idE]').val(resposta[13]);

				if ($('input[name=decrescimoE]').val() != "") {
					$('input[name=acrescimoE]').prop('disabled', true);
				} else if ($('input[name=acrescimoE]').val() != "") {
					$('input[name=decrescimoE]').prop('disabled', true);
				}


				$('input[name=id_tipoE]').val(resposta[19]);
				$('input[name=tipoE]').val(resposta[5]);
			},
		});
	});


});
