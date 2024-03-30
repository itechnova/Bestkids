'use strict';
$(document).ready(function () {

    $('.Datatable').DataTable({
        responsive: $('.Datatable').attr('responsive') === '1',
        aLengthMenu: [
            [10, 30, 50, -1],
            [10, 30, 50, "Todos"]
        ],
        iDisplayLength: 10,
        language: {
            search: 'Filtrar',
            lengthMenu: '_MENU_ registros por página',
            zeroRecords: 'No hay resultados.',
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay registros disponibles.',
            infoFiltered: 'filtrados de _MAX_ registros totales',
            paginate: {
                previous: 'Anterior',
                next: 'Siguiente'            
            }
        }
    });

    $('#example2').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true
    });

});