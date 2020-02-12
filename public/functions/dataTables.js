const esp="//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json";
var tablas=["#dataTableAreas","#dataTablePisos","#dataTableEspecialidad","#dataTableHabitaciones","#dataTableCamas","#dataTableCie10"];

$(document).ready(function() {
   tablas.forEach( function(valor, indice, array) {
      $(valor).DataTable({
         rowReorder: true,
         "language": {
            "url": esp
        }
      });
   });
});
