//---  ACTIVA O DESACTIVA PERMISOS
function activaDesactivaPermisos(id, opcion) {
   var perf_id = $('#perf_id').val();
   //opcion = (opcion) ? 'deny' : 'allow';
   var op = '';
   op = (opcion == 'allow') ? 'deny' : 'allow';
   //alert(`${id} ${opcion} ${op}`)
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/usuarios/editapermisos", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "opcion=" + op + "&id=" + id + "&perf_id=" + perf_id, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
         },
         success: function (requestData) {//armar la tabla
            
            $("#data_Table_permisos").html(requestData);
            //--------- toggle buttons inside modal--------
            $('#formModalPermisos').find(".toggle-event-permisos").bootstrapToggle();

            //$("#nombre_habitacion").removeClass('border border-success').removeClass('border border-danger').val("");
         },
         error: function (requestData, strError, strTipoError) {
            //alert('error')
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });
}
function confirmaEdicionPermisos(){
   $('#formModalPermisos').modal('hide');
   const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 1000,
      onOpen: (toast) => {
         toast.addEventListener('mouseenter', Swal.stopTimer)
         toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
   });
   Toast.fire({
      icon: 'success',
      title: 'Permisos actualizados correctamente!'
   });
}
function editarModalUpermisos(id, nombre) {
   $("#exampleModalLabelPermisos").text("Permisos - " + nombre);
   $("#perf_id").val(id);
   var dir = $('#dir').val();

   $('#formModalPermisos').modal({
      show: true,
   });


   //-------------------------
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/usuarios/permisos", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "id=" + id, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            $("#data_Table_permisos").html('Procesando...');

         },
         success: function (requestData) {//armar la tabla

            $("#data_Table_permisos").html(requestData);
            //--------- toggle buttons inside modal--------
            $('#formModalPermisos').find(".toggle-event-permisos").bootstrapToggle();
         },
         error: function (requestData, strError, strTipoError) {
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });
}
//------------------------
function getControladores(op) {
   var perfil = $("#perfil_id").val();
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/usuarios/selectcontroladores", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "perfil=" + perfil + "&op=" + op, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            $("#comboControladores").html('<option>Cargando...</option>');
            $("#comboControladoresRutas").html('<option>Cargando...</option>');

         },
         success: function (requestData) {//armar la tabla
            if (op == 0) {
               $("#comboControladoresRutas").html(requestData);
               getAcciones(0);
            } else if (op == 1) {
               $("#comboControladores").html(requestData);
               getAcciones(1);
            }

         },
         error: function (requestData, strError, strTipoError) {
            //alert(requestData, strError, strTipoError)
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });

}
//------------------------
function getAcciones(op) {
   var comboControladores;
   comboControladores = (op == 0) ? $("#comboControladoresRutas").val() : $("#comboControladores").val();
   var perfil = $("#perfil_id").val();
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/usuarios/selectacciones", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "comboControladores=" + comboControladores + "&perfil=" + perfil + "&op=" + op, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            $("#comboAccionesRutas").html('<option>Cargando...</option>');
            $("#comboAcciones").html('<option>Cargando...</option>');
         },
         success: function (requestData) {//armar la tabla
            if (op == 0) {
               $("#comboAccionesRutas").html(requestData);
            } else if (op == 1) {
               $("#comboAcciones").html(requestData);
            }
         },
         error: function (requestData, strError, strTipoError) {
            //alert(requestData, strError, strTipoError)
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });

}