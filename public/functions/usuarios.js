

function agregarModal() {
   $("#exampleModalLabel").text("Agregar - Usuario");
   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarUsuario();">Agregar</button>');

   $('#formModal').modal({
       show:true
   });
   
}

function editarModal(id) {
   $("#exampleModalLabel").text("Editar - Usuario");
   $("#usuario_id").val(id);
   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarUsuario();">Actualizar</button>');
   $('#formModal').modal({
       show:true
   });    
}
function eliminar(id){
 var dir = $('#dir').val();
 Swal.fire({
     position: 'top',
     title: 'Está seguro?',
     text: "¡No podrás revertir esto!",
     icon: 'warning',
     width: '22rem',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Sí, eliminar!',
     cancelButtonText: 'Cancelar'
   }).then((result) => {
     if (result.value) {
       $.ajax(
         {
             dataType: "html",
             type: "POST",
             url: dir + "/usuarios/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
             data: "id=" + id, //Se añade el parametro de busqueda del medico
             beforeSend: function (data) {
             },
             success: function (requestData) {//armar la tabla
               const Toast = Swal.mixin({
                 toast: true,
                 position: 'top-end',
                 showConfirmButton: false,
                 timer: 3000,
                 timerProgressBar: true,
                 onOpen: (toast) => {
                   toast.addEventListener('mouseenter', Swal.stopTimer)
                   toast.addEventListener('mouseleave', Swal.resumeTimer)
                 }
               });                              
               Toast.fire({
                 icon: 'success',
                 title: 'Dato eliminado correctamente!'
               });
               $("#data_Table").html(requestData);
               toDataTable("#dataTableUsuarios");
             },
             error: function (requestData, strError, strTipoError) {
             },
             complete: function (requestData, exito) { //fin de la llamada ajax.

             }
         });
         //window.location.href = dir + "/areas/logout";
     }
   })
}
function InsertarUsuario() {
   //var nombre = $("#ci").val();
   var nombres = $("#nombres").val();
   var apellidos = $("#apellidos").val();
   var correo = $("#correo").val();
   var perfil = $("#comboPerfil").val();
   var estado = $("#comboEstado").val();
   var dir = $('#dir').val();
       //console.log(nombre);
       $("#register").submit(function(event){
        event.preventDefault(); //prevent default action
        //console.log(nombre);
        if ((!nombres=="") && (!apellidos=="") && (!correo=="")) {
                $.ajax(
                    {
                        dataType: "html",
                        type: "POST",
                        url: dir + "/usuarios/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
                         + "&perfil=" + perfil + "&estado=" + estado, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {

                        },
                        success: function (requestData) {//armar la tabla

                          const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                          });                              
                          Toast.fire({
                            icon: 'success',
                            title: 'Dato creado correctamente!'
                          });
                              $('#formModal').modal('hide');
                              $("#data_Table").html(requestData);
                             toDataTable("#dataTableUsuarios");

                            //console.log(requestData.data);

                        },
                        error: function (requestData, strError, strTipoError) {
                            //console.log(strError+"\n"+strTipoError);
                            $("#clave_igual").addClass('alert alert-danger').html(requestData+" "+strError+" "+strTipoError).show(100).delay(2500).hide(100);

                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.
                           // console.log(exito);

                        }
                    });
            
        }
    });

}
function ActualizarHabitacion() {
 var id = $("#habitacion_id").val();
 var especialidad = $("#comboEspecialidad").val();
 var nombre = $("#nombre_habitacion").val();
 var dir = $('#dir').val();
     //console.log(nombre);
      if ((!nombre=="")) {
             $.ajax(
                 {
                     dataType: "html",
                     type: "POST",
                     url: dir + "/habitaciones/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                     data: "id="+id+ "&nombre=" + nombre+"&especialidad="+especialidad, //Se añade el parametro de busqueda del medico
                     beforeSend: function (data) {
                     },
                     success: function (requestData) {//armar la tabla
                         //alert("Area creada exitosamente!");
                         //$("#mensaje").addClass('alert alert-success').html('Area creada correctamente!').show(100).delay(1500).hide(100);
                         //$('#formModal').modal('hide');
                         const Toast = Swal.mixin({
                             toast: true,
                             position: 'top-end',
                             showConfirmButton: false,
                             timer: 3000,
                             timerProgressBar: true,
                             onOpen: (toast) => {
                               toast.addEventListener('mouseenter', Swal.stopTimer)
                               toast.addEventListener('mouseleave', Swal.resumeTimer)
                             }
                           });                              
                           Toast.fire({
                             icon: 'success',
                             title: 'Dato actualizado correctamente!'
                           });
                           $('#formModal').modal('hide');
                            $("#data_Table").html(requestData);
                           toDataTable("#dataTableHabitaciones");
                        
                         
                         $("#nombre_habitacion").removeClass('border border-success').removeClass('border border-danger').val("");
                     },
                     error: function (requestData, strError, strTipoError) {
                     },
                     complete: function (requestData, exito) { //fin de la llamada ajax.

                     }
                 });
     }else{
         $("#nombre_habitacion").removeClass('border border-success').addClass('border border-danger');
         $("#nombreHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
         .html('<span>Este campo es necesario!</span>');
     } 

}
function toDataTable(table){
  const esp="//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json";
  $(table).DataTable({
    "language": {
       "url": esp
   }
 });
}