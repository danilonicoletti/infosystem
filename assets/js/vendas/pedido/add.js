'use strict';

// Class definition
var KTpedidoAdd = (function () {
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
						var form_data = $('#pedido_add_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						$.ajax({
							url: HOST_URL + 'vendas/pedido/sql.php',
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
										text: 'Pedido inserido com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {
										window.location.href = HOST_URL + 'vendas/pedido';
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
									html: 'Pedido não foi inserido!',
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
					cliente: {
						validators: {
							notEmpty: {
								message: 'Cliente é Necessário',
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
					transportadora: {
						validators: {
							notEmpty: {
								message: 'Transportadora é Necessário',
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
			_wizardEl = KTUtil.getById('pedido_add');
			_formEl = KTUtil.getById('pedido_add_form');

			_initWizard();
			_initValidation();
		},
	};
})();
var KTpagamentoAdd = (function () {
	// Base elements

	// Private functions
	var _initWizard = function () {
		// Initialize form wizard

		// Submit event
		$(document).on('click', '.add-pagamento', function (e) {
			e.preventDefault();
			$('.action-hidden').hide();

			var forma = $('select[name="forma"]').val();
			var prazo = $('select[name="prazo"]').val();
			var valor = $('input[name="valor"]').val();
			var id_pedido = $('input[name="id_pedido"]').val();

			if (forma != '' || prazo != '' || valor != '') {

				$.ajax({
					url: HOST_URL + 'vendas/pedido/sql.php',
					type: 'POST',
					data: {
						forma: forma,
						prazo: prazo,
						valor: valor,
						type: "pagamento_add",
						id_pedido: id_pedido
					},
					success: function (data, status) {

						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Inserido!',
								text: 'Prazo de Pagamento inserido com sucesso no Pedido.',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								},
							}).then(function (result) {
								$('.action-hidden').show();

								$('#kt_datatable_pagamento').DataTable().ajax.reload(null, false);

								$('select[name="forma"]').val('');
								$('select[name="prazo"]').val('');
								$('input[name="valor"]').val('');
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
							html: 'O Prazo de Pagamento não foi adicionado!',
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

				Swal.fire({
					html: 'Preencha todos os campos do pagamento, para Adicionar!',
					icon: 'error',
					buttonsStyling: false,
					confirmButtonText: 'OK, entendi!',
					customClass: {
						confirmButton: 'btn font-weight-bold btn-primary',
					},
				});
			}

		});
	};

	return {
		// public functions
		init: function () {

			_initWizard();
		},
	};
})();
var KTSelect2 = function () {
	// Private functions
	var demos = function () {
		// basic
		$('#transportadora').select2({
			placeholder: "Selecione a Transportadora",
			ajax: {
				url: HOST_URL + 'vendas/pedido/transportadora.php',
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
		$('#cliente').select2({
			placeholder: "Selecione o Cliente",
			ajax: {
				url: HOST_URL + 'vendas/pedido/cliente.php',
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
		$('#vendedor').select2({
			placeholder: "Selecione o Vendedor",
			ajax: {
				url: HOST_URL + 'vendas/pedido/vendedor.php',
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
var KTDatatablePagamentoServer = (function () {
	return {
		//main function to initiate the module
		init: function () {
			var id_pedido = $('input[name="id_pedido"').val();
			// alert(id_material);

			var table = $('#kt_datatable_pagamento').DataTable({
				info: false,
				ordering: false,
				paging: false,
				searching: false,
				language: {
					url: '../../assets/js/Portuguese-Brasil.json',
				},
				responsive: true,
				processing: true,
				serverSide: true,
				autoWidth: false,
				ajax: HOST_URL + 'vendas/pedido/pagamento.ajax.php?id_pedido=' + id_pedido,
				columnDefs: [
					{
						targets: 2,
						render: function (data, type, row) {
							return 'R$ ' + data.replaceAll('.', ',');
							// return ('R$ ' + data.toFixed(2).replaceAll('.', ','));
						},
					},
					{
						width: '30px',
						className: 'text-center',
						targets: -1,
						title: 'Ações',
						orderable: false,
						render: function (data, type, row) {

							return ('<a href="javascript:;" class="btn btn-sm btn-clean btn-icon remover-pagamento" title="Remover" id="' + data + '">\
										<i class="la la-trash"></i>\
									</a>');
						},
					},
				],
				footerCallback: function (row, data, start, end, display) {
					var api = this.api(),
						data;

					// Total over this page
					var pageTotal_qtd = api
						.column(2, {
							page: 'current',
						})
						.data()
						.reduce(function (a, b) {
							return moedaParaNumero(a) + moedaParaNumero(b);
						}, 0);
					// Update footer
					$(api.column(1).footer()).html('<b>Total</b>');
					$(api.column(2).footer()).html('<b>R$ ' + pageTotal_qtd.toFixed(2).replaceAll('.', ',') + '</b>');
				},
			});
		},
	};
})();
var KTDatatablemateriaisServer = (function () {
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
			ajax: HOST_URL + 'vendas/pedido/materiais.php',
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
							'<a href="javascript:;" class="btn btn-sm btn-clean btn-icon listaProduto" data-toggle="modal" data-target="#listaMateriais" title="Incluir" id="' + data + '">\
								<i class="la la-check-circle"></i>\
							</a>'
						);
					},
				},
			],
		});

		$(document).on('click', '.listaProduto', function (e) {

			var id_r_produto = $(this).attr('id');
			// $("#id_r_produto").val(id_r_produto);

			var table = $('#kt_datatable_estoque').DataTable({
				pageLength: 12,
				/*lengthMenu: [
					[10, 10, 20, -1],
					[10, 10, 20, 'Todos'],
				],*/
				language: {
					url: '../../assets/js/Portuguese-Brasil.json',
				},
				// searching: false,
				//info: false,
				retrieve: true,
				responsive: true,
				processing: true,
				serverSide: true,
				autoWidth: false,
				paging: false,
				ajax: HOST_URL + 'vendas/pedido/estoques.php?id=' + id_r_produto,
				order: [[1, 'asc']],
				columnDefs: [
					{
						targets: 0,
						width: '30px',
						orderable: false
					},

				],
				initComplete: function (settings, json) {
					$('.scroll').scrollTop(0);
				}
			});

			$('#kt_search').on('click', function (e) {
				e.preventDefault();

				var params = {};
				$('.datatable-input').each(function () {
					var i = $(this).data('col-index');
					if (params[i]) {
						params[i] += '|' + $(this).val();
					} else {
						params[i] = $(this).val();
					}
				});
				$.each(params, function (i, val) {
					// apply search params to datatable
					table.column(i).search(val ? val : '', false, false);
				});


				table.table().draw();
			});

			$('#kt_reset').on('click', function (e) {
				e.preventDefault();

				$('.datatable-input').each(function (data) {
					$(this).val('');
					table.column($(this).data('col-index')).search('', false, false);
				});

				$('#kt_datatable_estoque').DataTable().table().draw();

			});

			$(document).on('click', '.close-produtos', function (e) {
				// $('#kt_datatable_estoque').DataTable().destroy();
				table.destroy();
				$('.datatable-input').val('');

			});

		});


	};
	return {
		//main function to initiate the module
		init: function () {
			initTable2();
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
			var validator = _validations[0];

			if (validator) {

				validator.validate().then(function (status) {
					if (status == 'Valid') {
						var data = new FormData();

						//Form data
						var form_data = $('#produtos_edit_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						$.ajax({
							url: HOST_URL + 'vendas/pedido/sql.php',
							type: 'POST',
							data: data,
							processData: false,
							contentType: false,
							success: function (data, status) {
								//console.log(data);
								var resposta = data.split('||');
								if (resposta[0] == 'success') {
									Swal.fire({
										title: 'Alterado!',
										text: 'Produto alterado com sucesso',
										icon: 'success',
										timer: 1000,
										onOpen: function () {
											Swal.showLoading();
										},
									}).then(function (result) {

										var id_pedido = $('#id_pedido').val();

										$.ajax({
											url: '../pedido/sql.php',
											type: 'POST',
											dataType: 'html',
											data: {
												type: 'materiais_view',
												id_pedido: id_pedido,
											},
											success: function (data) {

												var resposta = data.split('||');
												var group = $('#modal_ativo').val();

												$('#editProduto').modal('toggle');
												$('#viewProduto' + group).modal('toggle');

												$(".txt_total").html(resposta[3]);

												$("input[name='vl_produto']").val(resposta[2]);
												$("input[name='vl_pedido']").val(resposta[2]);

												setTimeout(function () {
													$('.materiais').html(resposta[0]);
													$('.modais').html(resposta[1]);

													$('input[name=custo]').val('');
													$('select[name=moeda').val('R$');
												}, 1000);


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
							error: function (xhr, desc, err) {
								Swal.fire({
									html: 'Orçamento não foi inserido!',
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
					comprimento: {
						validators: {
							notEmpty: {
								message: 'Comprimento é Necessário',
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
					metro: {
						validators: {
							notEmpty: {
								message: 'Metro² é Necessário',
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
					custo: {
						validators: {
							notEmpty: {
								message: 'Preço é Necessário',
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
	KTpedidoAdd.init();
	KTpagamentoAdd.init();
	KTDatatablePagamentoServer.init();
	KTDatatablemateriaisServer.init();
	KTSelect2.init();

	/*  Temporário */

	/*
	var id_pedido = $('#id_pedido').val();

	$.ajax({
		url: '../pedido/sql.php',
		type: 'POST',
		dataType: 'html',
		data: {
			type: 'materiais_view',
			id_pedido: id_pedido,
		},
		success: function (data) {

			var resposta = data.split('||');

			$(".txt_total").html(resposta[3]);

			$("input[name='vl_produto']").val(resposta[2]);
			$("input[name='vl_pedido']").val(resposta[2]);

			$('.materiais').html(resposta[0]);
			$('.modais').html(resposta[1]);

			KTprodutosEdit.init();
		},
	});
	*/

	/* Temporário */

	$('.menu-item-submenu:nth-child(4)').addClass('menu-item-open menu-item-here');
	$('.menu-vendas .menu-item:nth-child(2)').addClass('menu-item-active');

	$(document).on('click', '.remover-pagamento', function (e) {
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
					url: HOST_URL + 'vendas/pedido/sql.php',
					type: 'POST',
					data: {
						type: 'pagamento_remove',
						id: id,
						temp: '1'
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Removido!',
								text: 'Pagamento removido com sucesso no Material.',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								},
							}).then(function (result) {
								$('#kt_datatable_pagamento').DataTable().ajax.reload(null, false);
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

	$(document).on('click', '.add-produto', function (e) {
		e.preventDefault();

		var id_pedido = $('#id_pedido').val();

		var produtos = new Array();
		$(".produtos:checked").each(function () {
			produtos.push($(this).val());
		});

		if (produtos.length >= 1) {

			Swal.fire({
				title: 'Deseja adicionar?',
				html: '',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Sim, adicionar!',
				cancelButtonText: 'Não, cancelar!',
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: HOST_URL + 'vendas/pedido/sql.php',
						type: 'POST',
						data: {
							type: 'produto_add',
							id_pedido: id_pedido
						},
						success: function (data, status) {
							//console.log(data);
							var resposta = data.split('||');
							if (resposta[0] == 'success') {
								Swal.fire({
									title: 'Adicionado!',
									text: 'Produtos adicionado com sucesso no Pedido.',
									icon: 'success',
									timer: 1000,
									onOpen: function () {
										Swal.showLoading();
									},
								}).then(function (result) {

									var id_pedido = $('#id_pedido').val();

									$.ajax({
										url: '../pedido/sql.php',
										type: 'POST',
										dataType: 'html',
										data: {
											type: 'materiais_view',
											id_pedido: id_pedido,
										},
										success: function (data) {
											$('#listaMateriais').modal('toggle');
											$('#addProduto').modal('toggle');

											var resposta = data.split('||');

											$("input[name='vl_produto']").val(resposta[2]);

											$(".txt_total").html(resposta[3]);
											$('.materiais').html(resposta[0]);
											$('.modais').html(resposta[1]);

											$('#kt_datatable_estoque').DataTable().destroy();
											$('.datatable-input').val('');

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
		} else {
			Swal.fire({
				html: 'Selecione ao menos 1 Item',
				icon: 'error',
				buttonsStyling: false,
				confirmButtonText: 'OK, entendi!',
				customClass: {
					confirmButton: 'btn font-weight-bold btn-primary',
				},
			});
		}
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

	$(document).on('click', '.zerar', function () {

		$('.datatable-input-materiais').val('');

		var params = {};

		$('.datatable-input-materiais').each(function () {
			var i = $(this).data('col-index');
			params[i] = $(this).val();
		});
		$.each(params, function (i, val) {
			$('#kt_datatable_materiais')
				.DataTable()
				.column(i)
				.search('', false, false);
		});
		$('#kt_datatable_materiais').DataTable().table().draw();

	});

	$(document).on('click', '.produtos', function () {

		var produtos = $(this).val();
		if ($(this).is(':checked')) {

			$.ajax({
				url: "sql.php",
				type: "POST",
				data: {
					type: 'produtos_session_add',
					produtos: produtos,
				}
			});
		} else {
			$.ajax({
				url: "sql.php",
				type: "POST",
				data: {
					type: 'produtos_session_remove',
					produtos: produtos,
				}
			});
		}

	});

	$('.selectAll').click(function (event) {
		if (this.checked) {

			$('.checkbox input').each(function () {
				this.checked = true;
			});

			var produtos = $(this).val();

			$.ajax({
				url: "sql.php",
				type: "POST",
				data: {
					type: 'produtos_session_add',
					produtos: produtos,
				}
			});


		} else {

			$('.checkbox input').each(function () {
				this.checked = false;
			});

			$.ajax({
				url: "sql.php",
				type: "POST",
				data: {
					type: 'produtos_session_remove_all',
				}
			});

		}
	});

	$(document).on('focusout', 'input[name=vl_desconto]', function (e) {

		if ($(this).val() != "") {
			var vl_desconto = moedaParaNumero($(this).val());
		} else {
			var vl_desconto = 0;
		}

		if ($('input[name=vl_outros]').val() != "") {
			var vl_outros = moedaParaNumero($('input[name=vl_outros]').val());
		} else {
			var vl_outros = 0;
		}

		if ($('input[name=vl_produto]').val() != "") {
			var vl_produto = moedaParaNumero($('input[name=vl_produto]').val());
		} else {
			var vl_produto = 0;
		}


		var novo = vl_produto - vl_desconto + vl_outros;

		$('input[name=vl_pedido]').val(novo);

		$('.txt_total').html('<div class="mr-5 text-right" style="line-height: 1.25;">\
				<span>Total do Pedido</span><br>\
				<span style="font-weight: 800;font-size: 22px;">R$ ' + novo.toFixed(2).replaceAll('.', ',') + '</span>\
			</div>');

	});

	$(document).on('focusout', 'input[name=vl_outros]', function (e) {

		if ($(this).val() != "") {
			var vl_outros = moedaParaNumero($(this).val());
		} else {
			var vl_outros = 0;
		}

		if ($('input[name=vl_desconto]').val() != "") {
			var vl_desconto = moedaParaNumero($('input[name=vl_desconto]').val());
		} else {
			var vl_desconto = 0;
		}

		if ($('input[name=vl_produto]').val() != "") {
			var vl_produto = moedaParaNumero($('input[name=vl_produto]').val());
		} else {
			var vl_produto = 0;
		}

		var novo = vl_produto - vl_desconto + vl_outros;

		$('input[name=vl_pedido]').val(novo);

		$('.txt_total').html('<div class="mr-5 text-right" style="line-height: 1.25;">\
				<span>Total do Pedido</span><br>\
				<span style="font-weight: 800;font-size: 22px;">R$ ' + novo.toFixed(2).replaceAll('.', ',') + '</span>\
			</div>');

	});

	$(document).on('click', '.removeProduto', function (e) {
		e.preventDefault();
		var id = $(this).attr('id');
		var group = $(this).data('group');

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
					url: HOST_URL + 'vendas/pedido/sql.php',
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
								text: 'Produto removido com sucesso!',
								icon: 'success',
								timer: 1000,
								onOpen: function () {
									Swal.showLoading();
								},
							}).then(function (result) {

								var id_pedido = $('#id_pedido').val();

								$.ajax({
									url: '../pedido/sql.php',
									type: 'POST',
									dataType: 'html',
									data: {
										type: 'materiais_view',
										id_pedido: id_pedido,
									},
									success: function (data) {
										var resposta = data.split('||');

										$(".txt_total").html(resposta[3]);

										$("input[name='vl_produto']").val(resposta[2]);

										$('.materiais').html(resposta[0]);

										$('.modais').html(resposta[1]);

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
						$('#viewProduto' + group).modal('toggle');
					},
				});
			}
		});
	});

	var typingTimer; //timer identifier
	var doneTypingInterval = 2000; //time in ms, 1 second for example

	//on keyup, start the countdown
	$(document).on('keyup', 'input[name=custo]', function () {
		var valor = $(this).val();
		clearTimeout(typingTimer);
		if ($('input[name=custo]').val()) {
			typingTimer = setTimeout(doneTyping(valor), doneTypingInterval);
		}
	});

	//user is "finished typing," do something
	function doneTyping(valor) {

		if (valor != "") {
			var custo = moedaParaNumero(valor);
		} else {
			var custo = 0;
		}
		var moeda = $('select[name=moeda]').val();

		if ($('input[name=metro]').val() != "") {
			var metro = moedaParaNumero($('input[name=metro]').val());
		} else {
			var metro = 0;
		}

		$.ajax({
			url: "sql.php",
			type: "POST",
			data: {
				type: 'calcular_valor',
				custo: custo,
				moeda: moeda,
				metro: metro,
			},
			success: function (data, status) {
				var resposta = data.split("||");

				$('input[name=valor_real]').val(resposta[0]);
				$('input[name=subtotal]').val(resposta[1]);
			}
		});
	}

	$(document).on('change', 'input[name=custo]', function (e) {



	});

	$(document).on('click', '.la-edit', function () {
		$('.moneyMask').maskMoney({ allowNegative: true, thousands: '.', decimal: ',', affixesStay: false });

		$('.qtdMask').mask('###0,00', {
			reverse: true,
		});
		$('.qtd3Mask').mask('###0,000', {
			reverse: true,
		});
	});

	$(document).on('click', '.viewProduto', function (e) {
		e.preventDefault();
		var id_p_produto = $(this).data('group');

		$('#modal_ativo').val(id_p_produto);
	});

	$(document).on('click', '.editProduto', function (e) {
		e.preventDefault();
		var id_p_produto = $(this).attr('id');

		$.ajax({
			url: "sql.php",
			type: "POST",
			data: {
				type: 'produtos_edit_view',
				id_p_produto: id_p_produto,
			},
			success: function (data, status) {
				var resposta = data.split("||");

				$("#descricaoProduto").html(resposta[0]);

				$("input[name=id_produto]").val(resposta[2]);
				$("input[name=produto]").val(resposta[1]);
				$("input[name=espessura]").val(resposta[3]);
				$("input[name=chapas]").val(resposta[4]);
				$("input[name=comprimento]").val(resposta[5]);
				$("input[name=altura]").val(resposta[6]);
				$("input[name=metro]").val(resposta[7]);
				$("input[name=valor_real]").val(resposta[8]);
				$("input[name=subtotal]").val(resposta[9]);

				$('#editProduto').modal('show');

			}
		});

	});

	$(document).on('click', '.atualizar', function (e) {
		e.preventDefault();
		var comprimento = moedaParaNumero($("input[name=comprimento]").val());
		var altura = moedaParaNumero($("input[name=altura]").val());

		var metro = comprimento * altura;

		$("input[name=metro]").val(metro.toFixed(3).replaceAll('.', ','));

	});
});
