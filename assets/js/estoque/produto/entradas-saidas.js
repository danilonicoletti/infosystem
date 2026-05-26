'use strict';

var KTDatatablesDataSourceAjaxServer = (function () {
	function FormataStringData(data) {
		var retorno = data.split(' ');

		var dia = retorno[0].split('-')[2];
		var mes = retorno[0].split('-')[1];
		var ano = retorno[0].split('-')[0];
		return ('0' + dia).slice(-2) + '/' + ('0' + mes).slice(-2) + '/' + ano;
	}
	var id_produto = $("#id_produto").val();

	var initTable1 = function () {
		var table = $('#kt_datatable').DataTable({
			info: false,
			ordering: false,
			paging: false,
			// searching: false,
			language: {
				url: HOST_URL + 'assets/js/Portuguese-Brasil.json',
			},
			responsive: true,
			processing: true,
			serverSide: true,
			ajax: HOST_URL + 'estoque/produto/entradas-saidas.ajax.php?id=' + id_produto,
			columnDefs: [
				{
					targets: 0,
					width: '100px',
					className: 'text-center',
					render: function (data, type, row) {

						return FormataStringData(data);

					},
				},
			]
		});

		$('#kt_search').on('click', function (e) {
			e.preventDefault();

			$('.totais').html('');

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

			setTimeout(function () {
				$.ajax({
					url: HOST_URL + 'estoque/produto/sql.php',
					type: 'POST',
					data: {
						type: 'totais_view',
						id_produto: id_produto
					},
					success: function (data) {
						$('.totais').html(data);
					},
				});
			}, 300);


		});

		$('#kt_reset').on('click', function (e) {
			e.preventDefault();

			$('.totais').html('');

			$.ajax({
				url: HOST_URL + 'estoque/produto/sql.php',
				type: 'POST',
				data: {
					type: 'kt_reset',
				},
				success: function () {

					$('.datatable-input').each(function (data) {
						$('input[type="text"').val('');
						table.column($(this).data('col-index')).search('', false, false);
					});

					$('#kt_datatable').DataTable().table().draw();

					$('.selectClasse [value=""]').prop('selected', true);

				},
			});

			setTimeout(function () {
				$.ajax({
					url: HOST_URL + 'estoque/produto/sql.php',
					type: 'POST',
					data: {
						type: 'totais_view',
						id_produto: id_produto
					},
					success: function (data) {
						$('.totais').html(data);
					},
				});
			}, 300);

		});
	};
	return {
		//main function to initiate the module  

		init: function () {
			initTable1();
		},
	};
})();

jQuery(document).ready(function () {
	KTDatatablesDataSourceAjaxServer.init();

	$('.menu-item-submenu:nth-child(3)').addClass('menu-item-open menu-item-here');
	$('.menu-estoque .menu-item:nth-child(1)').addClass('menu-item-active');

	var id_produto = $("#id_produto").val();

	$.ajax({
		url: HOST_URL + 'estoque/produto/sql.php',
		type: 'POST',
		data: {
			type: 'totais_view',
			id_produto: id_produto
		},
		success: function (data) {
			$('.totais').html(data);
		},
	});


});
