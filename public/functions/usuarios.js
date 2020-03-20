//---FUNCION QUE CAMBIA EL ESTADO DE UN USUARIO
$(function () {
  $('.toggle-event').change(function (event) {
    event.preventDefault(); //prevent default action
    //alert('Toggle: ' + $(this).prop('checked'))
    var opcion = 0;
    var dir = $('#dir').val();
    var id = $(this).val();
    opcion = $(this).prop('checked') ? 1 : 2; //operador ternario comprueba si es true = 1 sino =2    

    $.ajax(
      {
        dataType: "html",
        type: "POST",
        url: dir + "/usuarios/estado", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
        data: "opcion=" + opcion + "&id=" + id, //Se añade el parametro de busqueda del medico
        beforeSend: function (data) {
        },
        success: function (requestData) {//armar la tabla
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });
          Toast.fire({
            icon: 'success',
            title: 'Dato actualizado correctamente!'
          }).then((result) => {
            window.location.href = dir + "/usuarios/index";

          });


          //$("#nombre_habitacion").removeClass('border border-success').removeClass('border border-danger').val("");
        },
        error: function (requestData, strError, strTipoError) {
          //alert('error')
        },
        complete: function (requestData, exito) { //fin de la llamada ajax.

        }
      });
    // alert(opcion+" "+dir+" "+id);
  })
})


function agregarModalU() {
  $("#exampleModalLabel").text("Agregar - Usuario");
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarUsuario();">Agregar</button>');

  $('#formModal').modal({
    show: true
  });

}

function editarModalU(id, nombre, apellido, correo, estado, perfil) {
  $("#exampleModalLabel").text("Editar - Usuario");
  $("#usuario_id").val(id);
  $("#nombres").val(nombre);
  $("#apellidos").val(apellido);
  $("#correo").val(correo);
  $("#comboPerfil").val(perfil);
  $("#comboEstado").val(estado);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarUsuario();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
//-----------------Perfil
function agregarModalUperfil() {
  $("#exampleModalLabel").text("Agregar - Perfil");
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarPerfil();">Agregar</button>');

  $('#formModal').modal({
    show: true
  });

}

function editarModalUperfil(id, nombre, apellido, correo, estado, perfil) {
  $("#exampleModalLabel").text("Editar - Usuario");
  $("#usuario_id").val(id);
  $("#nombres").val(nombre);
  $("#apellidos").val(apellido);
  $("#correo").val(correo);
  $("#comboPerfil").val(perfil);
  $("#comboEstado").val(estado);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarUsuario();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
function cambiarClaveU(id) {
  $("#accionFormCambiaClave").show();
  $('#formModal_password').modal({
    show: true
  });
}
function activaPerfilForm() {
  if ($("#perfil_nombres").prop("disabled") == true)
    $("#perfil_nombres").prop("disabled", false).addClass('border border-warning');
  if ($("#perfil_apellidos").prop("disabled") == true)
    $("#perfil_apellidos").prop("disabled", false).addClass('border border-warning');
  if ($("#perfil_correo").prop("disabled") == true)
    $("#perfil_correo").prop("disabled", false).addClass('border border-warning');
  $("#formGroupPassword").show();

  $("#accionForm").html('<button class="btn btn-danger" type="button"  onclick="descativarPerfilForm();">Cancelar</button><button class="btn btn-primary" type="submit"  onclick="ActualizarInformacionUsuario();">Actualizar</button>');

}
function descativarPerfilForm() {
  if ($("#perfil_nombres").prop("disabled") == false)
    $("#perfil_nombres").prop("disabled", true).removeClass('border border-warning');
  if ($("#perfil_apellidos").prop("disabled") == false)
    $("#perfil_apellidos").prop("disabled", true).removeClass('border border-warning');
  if ($("#perfil_correo").prop("disabled") == false)
    $("#perfil_correo").prop("disabled", true).removeClass('border border-warning');
  $("#formGroupPassword").hide();

  $("#accionForm").html('');

}
function eliminarU(id) {
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
            $('.toggle-event').bootstrapToggle();
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
  var nombres = $("#nombres").val();
  var apellidos = $("#apellidos").val();
  var correo = $("#correo").val();
  var perfil = $("#comboPerfil").val();
  var estado = $("#comboEstado").val();
  var dir = $('#dir').val();
  $("#register").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombres == "") && (!apellidos == "") && (!correo == "")) {
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
            if (requestData == '') {
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
                icon: 'error',
                title: 'Correo ingresado ya existe!'
              });
            } else {
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
              $('.toggle-event').bootstrapToggle();
            }

            //console.log(requestData.data);

          },
          error: function (requestData, strError, strTipoError) {
            //console.log(strError+"\n"+strTipoError);
            $("#clave_igual").addClass('alert alert-danger').html(requestData + " " + strError + " " + strTipoError).show(100).delay(2500).hide(100);

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
  var nombres = $("#nombres").val();
  var apellidos = $("#apellidos").val();
  var correo = $("#correo").val();
  var perfil = $("#comboPerfil").val();
  var estado = $("#comboEstado").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  $("#register").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombres == "") && (!apellidos == "") && (!correo == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/usuarios/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
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
            $('.toggle-event').bootstrapToggle();


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
function ActualizarInformacionUsuario() {
  var id = $("#perfil_usuario_id").val();
  var nombres = $("#perfil_nombres").val();
  var apellidos = $("#perfil_apellidos").val();
  var correo = $("#perfil_correo").val();
  var confirma_clave = $("#confirma_clave").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  $("#perfil_register").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombres == "") && (!apellidos == "") && (!correo == "") && (!confirma_clave == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/usuarios/editarperfil", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
            + "&confirma_clave=" + confirma_clave, //Se añade el parametro de busqueda del medico
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
            window.location.href = dir + "/usuarios/perfil";



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
function ActualizaClaveUsuario() {
  //var clave_actual = $("#clave_actual").val();
  var nueva_clave = $("#nueva_clave").val();
  var nueva_clave_2 = $("#nueva_clave_2").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  $("#register").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nueva_clave == "") && (!nueva_clave_2 == "")) {
      if (nueva_clave == nueva_clave_2) {
        $.ajax(
          {
            dataType: "html",
            type: "POST",
            url: dir + "/usuarios/editarclave", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
            data: "nueva_clave=" + nueva_clave, //Se añade el parametro de busqueda del medico
            beforeSend: function (data) {
            },
            success: function (requestData) {//armar la tabla
              //alert("Area creada exitosamente!");
              Swal.fire({
                position: 'top',
                title: 'Está seguro?',
                text: "¡Seleccione 'Aceptar' para confirmar el cambio de contraseña!",
                icon: 'question',
                width: '22rem',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
              }).then((result) => {
                if (result.value) {
                  Swal.fire({
                    position: 'top',
                    title: 'Correcto!',
                    text: 'Su contraseña ah sido actualizada.' +
                      '\n Redirigiendo al login.',
                    width: '22rem',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                  }).then((result) => {
                    window.location.href = dir + "/auth/logout";
                  })

                }

              })
            },
            error: function (requestData, strError, strTipoError) {
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
                icon: 'error',
                title: 'Ah ocurido un error!'
              });
            },
            complete: function (requestData, exito) { //fin de la llamada ajax.

            }
          });
      } else {
        $("#clave_igual").addClass('badge badge-danger text-wrap')
          .html('Deben coincidir las contraseñas').show(100).delay(2500).hide(100);
      }
    }
  });

}
function toDataTable(table) {
  const esp = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json";
  $(table).DataTable({
    "language": {
      "url": esp
    }
  });
}