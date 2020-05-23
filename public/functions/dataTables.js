const esp = "../public/assets/lang/Spanish.json";
var buttons_config =[
      {
            extend: 'collection',
            text: '<i class="fas fa-download text-white-50 pr-4"></i><span class="text">Exportar</span>',
            className: 'btn btn-dark btn-sm px-4',
            titleAttr: 'Exportar',
            autoClose: true,
            buttons: [
                  /* {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy  pr-2"></i><span>Copiar</span>',
                        className: 'btn btn-light btn-sm',
                        titleAttr: 'Copy',
                        exportOptions: {
                              columns: ':not(:last-child)',
                          }
                  }, */ 
                  {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel text-success pr-2"></i><span >Excel</span>',
                        className: 'btn btn-light btn-sm',
                        titleAttr: 'Excel',
                        exportOptions: {
                              columns: ':not(:last-child)',
                          }
                  },
                  {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv text-info pr-2"></i><span>CSV</span>',
                        className: 'btn btn-light btn-sm',
                        titleAttr: 'CSV',
                        exportOptions: {
                              columns: ':not(:last-child)',
                          }
                  },
                  {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf text-danger pr-2"></i><span>PDF</span>',
                        className: 'btn btn-light btn-sm',
                        titleAttr: 'PDF',
                        exportOptions: {
                              columns: ':not(:last-child)',
                          }
                  },
                  {
                        extend: 'print',
                        text: '<i class="fas fa-print text-dark pr-2"></i><span>Imprimir</span>',
                        className: 'btn btn-light btn-sm',
                        titleAttr: 'Print',
                        exportOptions: {
                              columns: ':not(:last-child)',
                          }
                  }
                  
            ]
      }
];
$.extend(true, $.fn.dataTable.defaults, {
      responsive: true,
      columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
      fixedHeader: true,
      "language": {
            "url": esp
      },
      "pagingType": "simple_numbers",
      stateSave: true,
      "deferRender": true,
      lengthMenu: [
            [10, 25, 50, -1],
            ['10 filas', '25 filas', '50 filas', 'Todo']
      ]    
      
});

function dataTables(){
      var entrata = $('#campoBusqueda').val();
      entrata = typeof entrata !== 'undefined' ?  entrata : ''; 

      var table = $('.dataTable').addClass('table-hover ').DataTable().search(entrata).draw();
      buttons = new $.fn.dataTable.Buttons(table, {
            buttons: buttons_config
    }).container().appendTo($('#exportButtons'));
}
function toDataTable(table) {
      $(table).addClass('table-hover').DataTable();
      
}

