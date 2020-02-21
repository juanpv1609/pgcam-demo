

function agregarModal() {
   $("#exampleModalLabel").text("Agregar - Usuario");
   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarUsuario();">Agregar</button>');

   $('#formModal').modal({
       show:true
   });
   
}

function editarModal(id,nombre,apellido,correo,estado,perfil) {
   $("#exampleModalLabel").text("Editar - Usuario");
   $("#usuario_id").val(id);
   $("#nombres").val(nombre);
    $("#apellidos").val(apellido);
    $("#correo").val(correo);
    $("#comboPerfil").val(perfil);
    $("#comboEstado").val(estado);
   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarUsuario();">Actualizar</button>');
   $('#formModal').modal({
       show:true
   });    
}
function cambiarClave() {
  $("#exampleModalLabel").text("Cambio de clave");
  $("#accionFormCambiaClave").show();
  $('#formModal').modal({
    show:true
});
}
function activaPerfilForm() {
  if ($("#nombres").prop("disabled")==true)
    $("#nombres").prop("disabled",false).addClass('border border-warning');
  if ($("#apellidos").prop("disabled")==true)
    $("#apellidos").prop("disabled",false).addClass('border border-warning');
  if ($("#correo").prop("disabled")==true)
    $("#correo").prop("disabled",false).addClass('border border-warning');
  
    $("#accionForm").html('<button class="btn btn-danger" type="button"  onclick="descativarPerfilForm();">Cancelar</button><button class="btn btn-primary" type="submit"  onclick="ActualizarUsuario();">Actualizar</button>');
 
}
function descativarPerfilForm() {
  if ($("#nombres").prop("disabled")==false)
    $("#nombres").prop("disabled",true).removeClass('border border-warning');
  if ($("#apellidos").prop("disabled")==false)
    $("#apellidos").prop("disabled",true).removeClass('border border-warning');
  if ($("#correo").prop("disabled")==false)
    $("#correo").prop("disabled",true).removeClass('border border-warning');
  
    $("#accionForm").html('');
 
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
  var nombres =   $("#nombres").val();
  var apellidos = $("#apellidos").val();
  var correo =    $("#correo").val();
  var perfil =    $("#comboPerfil").val();
  var estado =    $("#comboEstado").val();
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
function ActualizarUsuario() {
 var id = $("#usuario_id").val();
 var nombres =   $("#nombres").val();
  var apellidos = $("#apellidos").val();
  var correo =    $("#correo").val();
  var perfil =    $("#comboPerfil").val();
  var estado =    $("#comboEstado").val();
 var dir = $('#dir').val();
     //console.log(nombre);
     $("#register").submit(function(event){
      event.preventDefault(); //prevent default action
      if ((!nombres=="") && (!apellidos=="") && (!correo=="")) {
             $.ajax(
                 {
                     dataType: "html",
                     type: "POST",
                     url: dir + "/usuarios/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                     data: "id="+id+ "&nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
                     + "&perfil=" + perfil + "&estado=" + estado, //Se añade el parametro de busqueda del medico
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
                           toDataTable("#dataTableUsuarios");
                        
                         
                         //$("#nombre_habitacion").removeClass('border border-success').removeClass('border border-danger').val("");
                     },
                     error: function (requestData, strError, strTipoError) {
                     },
                     complete: function (requestData, exito) { //fin de la llamada ajax.

                     }
                 });
     }
    });

}
function toDataTable(table){
  const esp="//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json";
  $(table).DataTable({
    "language": {
       "url": esp
   }
 });
}