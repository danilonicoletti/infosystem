'use strict';

var KTDatatablesDataSourceAjaxServer = (function () {

	var initTable1 = function () {
		var table = $('#kt_datatable').DataTable({
			language: {
				url: HOST_URL + 'assets/js/Portuguese-Brasil.json',
			},
			responsive: true,
			processing: true,
			serverSide: true,
			ajax: HOST_URL + 'estoque/produto/ajax.php',
			order: [[0, 'desc']],
			columnDefs: [
				{
					targets: 0,
					width: '70px',
					className: 'text-center',
				},
				{
					targets: 3,
					width: '150px',
					render: function (data, type, row) {
						return (row[6] + ' ' + data);
					},
				},
				{
					width: '145px',
					className: 'text-center',
					targets: -2,
					title: 'Ações',
					orderable: false,
					render: function (data, type, row) {
						return ('<a href="estoque?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Estoque">\
									<i class="la la-box-open"></i>\
								</a>\
								<a href="entradas-saidas?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Entradas e Saídas">\
									<i class="la la-exchange"></i>\
								</a>\
								<a href="edit?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Editar">\
									<i class="la la-edit"></i>\
								</a>\
								<a href="javascript:;" class="btn btn-sm btn-clean btn-icon remover" title="Remover" id="' + data + '">\
									<i class="la la-trash"></i>\
								</a>');
					},
				},
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

	$(document).on('click', '.remover', function (e) {
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
					url: HOST_URL + 'estoque/produto/sql.php',
					type: 'POST',
					data: {
						type: 'produto_remove',
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
								$('#kt_datatable').DataTable().ajax.reload(null, false);
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
});
