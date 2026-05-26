'use strict';

var KTDatatablesDataSourceAjaxServer = (function () {
    function FormataStringData(data) {
        var retorno = data.split(' ');

        var dia = retorno[0].split('-')[2];
        var mes = retorno[0].split('-')[1];
        var ano = retorno[0].split('-')[0];
        return ('0' + dia).slice(-2) + '/' + ('0' + mes).slice(-2) + '/' + ano;
    }
    var initTable1 = function () {
        var table = $('#kt_datatable').DataTable({
            language: {
                url: '../assets/js/Portuguese-Brasil.json',
            },
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: HOST_URL + 'nota-fiscal/ajax.php',
            order: [[0, 'desc']],
            columnDefs: [
                {
                    targets: 0,
                    width: '50px',
                    className: 'text-center',
                },
                {
                    targets: 2,
                    width: '180px',
                    className: 'text-center',
                    render: function (data, type, row) {

                        return FormataStringData(data);

                    },
                },

                {

                    className: 'text-center',
                    targets: 4,
                    title: 'Status',
                    orderable: false,
                    render: function (data, type, row) {

                        if (data == 0) {
                            return '<span class="label label-warning label-dot mr-2" title="Aguardando Assinatura"></span>';
                        } else if (data == 1) {
                            return '<span class="label label-primary label-dot mr-2" title="Aguardando Transmissão"></span>';
                        } else if (data == 2) {
                            return '<span class="label label-success label-dot mr-2" title="Nfe Transmitida"></span>';
                        } else if (data == 3) {
                            return '<span class="label label-danger label-dot mr-2" title="Nfe Cancelada"></span>';
                        }


                    },
                },
                {
                    width: '40px',
                    className: 'text-center',
                    targets: 5,
                    title: 'Ações',
                    orderable: false,
                    render: function (data, type, row) {

                        if (row[4] == 0) {
                            var retorno = '<a href="edit?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Editar">\
                                                <i class="la la-edit"></i>\
                                            </a>\
                                            <a href="#" class="btn btn-sm btn-clean btn-icon assinar" id="' + data + '" title="Assinar Nota">\
                                                <i class="la la-file-signature"></i>\
                                            </a>';
                        } else if (row[4] == 1) {
                            var retorno = '<a href="edit?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Editar">\
                                                <i class="la la-edit"></i>\
                                            </a>\
                                            <a href="#" class="btn btn-sm btn-clean btn-icon retornar" id="' + data + '" title="Retornar Assinatura">\
								                <i class="la la-undo-alt"></i>\
							                </a>\
                                            <a href="mostrar-nfe?id=' + data + '" class="btn btn-sm btn-clean btn-icon" title="Mostrar Nota">\
                                                <i class="la la-search"></i>\
                                            </a>\
                                            <a href="#" class="btn btn-sm btn-clean btn-icon transmitir" id="' + data + '" title="Transmitir Nota">\
                                                <i class="la la-arrow-circle-right"></i>\
                                            </a>';
                        } else if (row[4] == 2) {
                            var retorno = '<a href="#" class="btn btn-sm btn-clean btn-icon cancelar" id="' + data + '" title="Cancelar Nota" target="blank">\
                                                <i class="la la-ban"></i>\
                                            </a>\
                                            <a href="imprimir-nfe?id=' + data + '" class="btn btn-sm btn-clean btn-icon" id="' + data + '" title="Imprimir Nota" target="blank">\
                                                <i class="la la-print"></i>\
                                            </a>\
                                            <a href="download-nfe?id=' + data + '" class="btn btn-sm btn-clean btn-icon" id="' + data + '" title="Baixar Nota" target="blank">\
                                                <i class="la la-archive"></i>\
                                            </a>';
                        } else if (row[4] == 3) {
                            var retorno = '<div class="btn btn-sm btn-icon" style="cursor: default;"></div>';
                        }
                        return (retorno);
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

    $('.menu-item-submenu:nth-child(6)').addClass('menu-item-open menu-item-here');

    $(document).on('click', '.assinar', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');

        $.ajax({
            url: '../nota-fiscal/assinar-nfe.php',
            type: 'POST',
            dataType: 'html',
            data: {
                id: id,
            },
            success: function (data) {
                var resposta = data.split('||');
                if (resposta[0] == 'success') {
                    Swal.fire({
                        title: 'Assinada!',
                        text: 'Nota Fiscal assinada e pronta se transmitida!',
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
    });
    $(document).one('click', '.retornar', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');

        $.ajax({
            url: '../nota-fiscal/sql.php',
            type: 'POST',
            dataType: 'html',
            data: {
                type: 'retornar_assinatura',
                id: id,
            },
            success: function (data) {
                var resposta = data.split('||');
                if (resposta[0] == 'success') {
                    Swal.fire({
                        title: 'Retornado!',
                        text: 'Nota Fiscal voltou para ser assinada!',
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

    });
    $(document).on('click', '.transmitir', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');

        $.ajax({
            url: '../nota-fiscal/transmitir-nfe.php',
            type: 'POST',
            dataType: 'html',
            data: {
                id: id,
            },
            success: function (data) {
                var resposta = data.split('||');
                if (resposta[0] == 'success') {
                    Swal.fire({
                        title: 'Transmitida!',
                        text: 'Nota Fiscal transmitida com sucesso!',
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
                    }).then(function (result) {
                        $('#kt_datatable').DataTable().ajax.reload(null, false);
                    });
                }

            },
        });
    });
    $(document).on('click', '.cancelar', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Deseja cancelar a Nfe?',
            html: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Não, cancelar!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '../nota-fiscal/cancelar-nfe.php',
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function (data, status) {
                        //console.log(data);
                        var resposta = data.split('||');
                        if (resposta[0] == 'success') {
                            Swal.fire({
                                title: 'Cancelada!',
                                text: 'Nfe cancelada com sucesso!',
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
