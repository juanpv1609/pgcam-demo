function isValidEmail(mail) {
  return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}
//---  ACTIVA O DESACTIVA UN USUARIO
function activaDesactivaUsuario(id,perfil, opcion) {
  var op=0;
  op = (opcion==1) ? 2 : 1;
  var dir = $('#dir').val();

  if ((perfil==1)&& (opcion==1)) {
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
      icon: 'error',
      title: 'Este tipo de usuario no se puede desactivar!'
    }).then((result) => {
      window.location.href = dir + "/listar_usuarios";

    });

  }else{
    $.ajax(
      {
        dataType: "html",
        type: "POST",
        url: dir + "/usuarios/estado", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
        data: "opcion=" + op + "&id=" + id, //Se añade el parametro de busqueda del medico
        beforeSend: function (data) {
        },
        success: function (requestData) {
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
            title: 'Dato actualizado correctamente!'
          });

        },
        error: function (requestData, strError, strTipoError) {
          //alert('error')
        },
        complete: function (requestData, exito) { //fin de la llamada ajax.

        }
      }); 
  }
   
}

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


function cambiarClaveU() {
  $("#accionFormCambiaClave").show();
  $('#formModal_password').modal({
    show: true
  });
}

function eliminarU(id,perfil) {
  var dir = $('#dir').val();
  if ((perfil == 1) ) {
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
      icon: 'error',
      title: 'Este tipo de usuario no se puede eliminar!'
    });

  } else {
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
    if ((!nombres == "") && (!apellidos == "") && (!correo == "") && (isValidEmail(correo))) {
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
      if ((perfil == 1) && (estado == 2)) {
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
          icon: 'error',
          title: 'Este tipo de usuario no se puede desactivar!'
        });

      } else {
        $.ajax(
          {
            dataType: "html",
            type: "POST",
            url: dir + "/usuarios/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
            data: "id=" + id + "&nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
              + "&perfil=" + perfil + "&estado=" + estado, //Se añade el parametro de busqueda del medico
            beforeSend: function (data) {
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
              toDataTable("#dataTableUsuarios");
              $('.toggle-event').bootstrapToggle();
            },
            error: function (requestData, strError, strTipoError) {
            },
            complete: function (requestData, exito) { //fin de la llamada ajax.

            }
          });
      }
      
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
          url: dir + "/usuarios/editarusuario", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombres=" + nombres + "&apellidos=" + apellidos + "&correo=" + correo
            + "&confirma_clave=" + confirma_clave, //Se añade el parametro de busqueda del medico
          beforeSend: function (data) {
          },
          success: function (requestData) {//armar la tabla
            //alert((requestData))
            if (!requestData) {
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
                title: 'Contraseña incorrecta!'
              });
              $("#confirma_clave").val('').focus();

            } else if (requestData){
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
              window.location.href = dir + "/usuarios/perfil";
            }
            
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
  var clave_actual = $("#clave_actual").val();
  var nueva_clave = $("#nueva_clave").val();
  var nueva_clave_2 = $("#nueva_clave_2").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  $("#register").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!clave_actual == "") && (!nueva_clave == "") && (!nueva_clave_2 == "")) {
      if (nueva_clave == nueva_clave_2) {
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
            $.ajax(
              {
                dataType: "html",
                type: "POST",
                url: dir + "/usuarios/editarclave", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                data: "clave_actual=" + clave_actual + "&nueva_clave=" + nueva_clave, //Se añade el parametro de busqueda del medico
                beforeSend: function (data) {
                },
                success: function (requestData) {//armar la tabla
                  //alert("Area creada exitosamente!");              
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
                    title: 'Ah ocurido un error!'
                  });
                },
                complete: function (requestData, exito) { //fin de la llamada ajax.

                }
              });
          }
        })
      } else {
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
          title: 'Deden coincidir las contraseñas!'
        });
      }
    }
  });

}

