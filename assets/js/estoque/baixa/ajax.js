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
			autoWidth: false,
			ajax: HOST_URL + 'estoque/baixa/ajax.php',
			order: [[0, 'desc']],
			columnDefs: [
				{
					targets: 0,
					width: '100px',
					className: 'text-center',
				},
				{
					targets: 1,
					width: '150px',
					className: 'text-center',
				},
				{
					width: '80px',
					className: 'text-center',
					targets: -1,
					title: 'Ações',
					orderable: false,
					render: function (data, type, row) {
						return ('<a href="view?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Ver">\
									<i class="la la-search"></i>\
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
	$('.menu-estoque .menu-item:nth-child(3)').addClass('menu-item-active');


});
