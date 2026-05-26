'use strict';

var KTDatatablesDataSourceAjaxServer = (function () {
	var initTable1 = function () {
		var table = $('#kt_datatable').DataTable({
			language: {
				url: '../../assets/js/Portuguese-Brasil.json',
			},
			responsive: true,
			processing: true,
			serverSide: true,
			ajax: HOST_URL + 'cadastros/forma-de-recebimento/ajax.php',
			order: [[1, 'asc']],
			columnDefs: [
				{
					targets: 0,
					width: '70px',
					className: 'text-center',
				},
				{
					width: '170px',
					className: 'text-center',
					targets: -2,
					render: function (data, type, row) {
						if (data == 1) {
							return 'Sim';
						} else {
							return 'Não';
						}
					},
				},
				{
					width: '80px',
					className: 'text-center',
					targets: -1,
					title: 'Ações',
					orderable: false,
					render: function (data, type, row) {
						return ('<a href="edit?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Editar">\
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

	$('.menu-item-submenu:nth-child(1)').addClass('menu-item-open menu-item-here');
	$('.menu-cadastros .menu-item:nth-child(3)').addClass('menu-item-active');

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
					url: HOST_URL + 'cadastros/forma-de-recebimento/sql.php',
					type: 'POST',
					data: {
						type: 'forma_de_recebimento_remove',
						id: id,
					},
					success: function (data, status) {
						//console.log(data);
						var resposta = data.split('||');
						if (resposta[0] == 'success') {
							Swal.fire({
								title: 'Removido!',
								text: 'Forma de Recebimento removida com sucesso!',
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
