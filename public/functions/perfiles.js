//-----------------Perfiles
function agregarModalUperfil() {
   $("#exampleModalLabel").text("Agregar - Perfil");
   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarPerfil();">Agregar</button>');
   $("#valorBoton").val('');
   $("#rutas").addClass('d-none');
   $("#nombre_perfil").val('');
   $('#formModal').modal({
      show: true
   });
}

function editarModalUperfil(id, nombre, color) {
   $("#exampleModalLabel").text("Editar - Perfil");
   $("#perfil_id").val(id);
   $("#nombre_perfil").val(nombre);
   $("#color").val(color);
   $("#rutas").removeClass('d-none');

   $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarPerfil();">Actualizar</button>');
   $('#formModal').modal({
      show: true
   });

   getControladores(0);

}
function activaPerfilForm() {
   $("#form_editar_usuario").removeClass('d-none');
   if ($("#perfil_nombres").prop("disabled") == true)
      $("#perfil_nombres").prop("disabled", false).addClass('border border-warning');
   if ($("#perfil_apellidos").prop("disabled") == true)
      $("#perfil_apellidos").prop("disabled", false).addClass('border border-warning');
   if ($("#perfil_correo").prop("disabled") == true)
      $("#perfil_correo").prop("disabled", false).addClass('border border-warning');

}
function descativarPerfilForm() {
   $("#form_editar_usuario").addClass('d-none');
   if ($("#perfil_nombres").prop("disabled") == false)
      $("#perfil_nombres").prop("disabled", true).removeClass('border border-warning');
   if ($("#perfil_apellidos").prop("disabled") == false)
      $("#perfil_apellidos").prop("disabled", true).removeClass('border border-warning');
   if ($("#perfil_correo").prop("disabled") == false)
      $("#perfil_correo").prop("disabled", true).removeClass('border border-warning');

}
//------------PERFIL----------

function InsertarPerfil() {
   var nombre_perfil = $("#nombre_perfil").val();
   var color = $("#color").val();
   var dir = $('#dir').val();
   $("#agregaPerfil").submit(function (event) {
      event.preventDefault(); //prevent default action
      if ((!color == "")) {
         if ((!nombre_perfil == "")) {
            $.ajax(
               {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/usuarios/crearperfil", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "nombre_perfil=" + nombre_perfil + "&color=" + color, //Se añade el parametro de busqueda del medico
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
                        title: 'Dato creado correctamente!'
                     });
                     $('#formModal').modal('hide');
                     $("#data_Table").html(requestData);
                     toDataTable("#dataTablePerfiles");

                  },
                  error: function (requestData, strError, strTipoError) {
                     //alert(strError+"\n"+strTipoError);

                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.
                     // console.log(exito);

                  }
               });
         }
      } else {
         $("#colorHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
            .html('<span>Debe elegir un color!</span>');
      }
   });

}
function ActualizarPerfil() {
   var id = $("#perfil_id").val();
   var nombre_perfil = $("#nombre_perfil").val();
   var color = $("#color").val();
   var controlador = $("#comboControladoresRutas option:selected").text();
   var accion = $("#comboAccionesRutas option:selected").text();
   var dir = $('#dir').val();
   //console.log(nombre);
   $("#agregaPerfil").submit(function (event) {
      event.preventDefault(); //prevent default action
         if ((!nombre_perfil == "")) {
            $.ajax(
               {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/usuarios/editarperfil", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "id=" + id + "&nombre_perfil=" + nombre_perfil + "&color=" + color + "&controlador=" + controlador + "&accion=" + accion, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                     $("#data_Table").html("Procesando...");
                  },
                  success: function (requestData) {
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
                     });
                     $('#formModal').modal('hide');
                     $("#data_Table").html(requestData);
                     toDataTable("#dataTablePerfiles");
                  },
                  error: function (requestData, strError, strTipoError) {
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
               });
         }
   });

}
function eliminarPerfil(id) {
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
               url: dir + "/usuarios/eliminarperfil", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
               data: "id=" + id, //Se añade el parametro de busqueda del medico
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
                  });
                  $("#data_Table").html(requestData);
                  toDataTable("#dataTablePerfiles");
                  //$('.toggle-event').bootstrapToggle();
               },
               error: function (requestData, strError, strTipoError) {
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
                     icon: 'error',
                     title: 'Error: Existen usuarios en este perfil!'
                  });
               },
               complete: function (requestData, exito) { //fin de la llamada ajax.

               }
            });
         //window.location.href = dir + "/areas/logout";
      }
   })
}