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
			language: {
				url: HOST_URL + 'assets/js/Portuguese-Brasil.json',
			},
			responsive: true,
			processing: true,
			serverSide: true,
			ajax: HOST_URL + 'estoque/produto/estoque.ajax.php?id=' + id_produto,
			order: [[0, 'asc']],
			columnDefs: [

				{
					targets: 1,
					width: '100px',
					className: 'text-center',
					render: function (data, type, row) {

						return FormataStringData(data);

					},
				},
				{
					targets: 5,
					width: '100px',
					className: 'text-center',
					render: function (data, type, row) {
						return data;
					},
				}
			],
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
			type: 'totais_view_estoque',
			id_produto: id_produto
		},
		success: function (data) {
			$('.totais').html(data);
		},
	});

});
