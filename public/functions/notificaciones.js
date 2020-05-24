
function NotificacionEstado(id,estado){
   //alert(id)
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/notificaciones/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "id=" + id+"&estado="+estado , //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
         },
         success: function (requestData) {//armar la tabla
            
            const Toast = Swal.mixin({
               toast: true,
               position: 'top-end',
               showConfirmButton: false,
               timer: 3000,
               onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
               }
            });
            Toast.fire({
               icon: 'success',
               title: 'Dato actualizado correctamente!'
            })
            $("#data_Table").html(requestData);
            toDataTable("#dataTableNotificaciones");


         },
         error: function (requestData, strError, strTipoError) {
            //alert(requestData, strError, strTipoError)
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });
   

}

function eliminarNotificacion(id) {
   //alert(id)
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/notificaciones/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "id=" + id , //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
         },
         success: function (requestData) {//armar la tabla

            const Toast = Swal.mixin({
               toast: true,
               position: 'top-end',
               showConfirmButton: false,
               timer: 3000,
               onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
               }
            });
            Toast.fire({
               icon: 'success',
               title: 'Dato eliminado correctamente!'
            })
            $("#data_Table").html(requestData);
            toDataTable("#dataTableNotificaciones");


         },
         error: function (requestData, strError, strTipoError) {
            //alert(requestData, strError, strTipoError)
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });


}