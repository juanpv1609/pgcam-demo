/////////
function eligeCama(especialidad, habitacion, cama, cama_id, estado) {
      //console.log(cama)
      if ((estado == 0)) {
            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
                  onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
            });
            Toast.fire({
                  icon: 'success',
                  title: 'Cama reservada correctamente!'
            })
            $('#servicio').text(especialidad);
            $('#habitacion').text(habitacion);
            $('#cama_id').val(cama_id);
            $('#cama').text(cama);
      } else {
            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
                  onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
            });
            Toast.fire({
                  icon: 'warning',
                  title: 'Cama ocupada o en desinfeccion!'
            })
            $('#servicio').text('');
            $('#habitacion').text('');
            $('#cama_id').val('');
            $('#cama').text('');
      }

}
function setDatosCie(id, cod, descripcion) {
      if (id == 'diagnostico1') {
            $("#diagnostico1").val(descripcion);
            $("#cod1").text(cod);
            $("#lista1").html("");
      } else if (id == 'diagnostico2') {
            $("#diagnostico2").val(descripcion);
            $("#cod2").text(cod);
            $("#lista2").html("");
      } else if (id == 'diagnostico3') {
            $("#diagnostico3").val(descripcion);
            $("#cod3").text(cod);
            $("#lista3").html("");
      }
}
//formulario diagnostico
function getDiagnostico(diagnostico) {
      var id = $(diagnostico).attr("id");
      var dato = $(diagnostico).val();
      var dir = $('#dir').val();
      //alert(id);
      if (dato.length) {
            $.ajax(
                  {
                        dataType: "html",
                        type: "POST",
                        url: dir + "/cie10/lista", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "dato=" + dato + "&id=" + id, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                        },
                        success: function (requestData) {//armar la tabla
                              if (id == 'diagnostico1') {
                                    $("#lista1").html(requestData);
                              } else if (id == 'diagnostico2') {
                                    $("#lista2").html(requestData);
                              } else if (id == 'diagnostico3') {
                                    $("#lista3").html(requestData);
                              }
                        },
                        error: function (requestData, strError, strTipoError) {
                              //alert(requestData, strError, strTipoError)
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
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
                  icon: 'warning',
                  title: 'Debe ingresar algun campo a buscar'
            })
      }

}

function getCantones() {
      //var nombre = $("#ci").val();
      var prov = $("#comboProv").val();
      var dir = $('#dir').val();
      //alert(prov);
      $.ajax(
            {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/paciente/canton", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "prov=" + prov, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                  },
                  success: function (requestData) {//armar la tabla
                        $("#comboCant").html(requestData);
                        getParroquias();
                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}
function getParroquias() {
      //var nombre = $("#ci").val();
      var canton = $("#comboCant").val();
      var dir = $('#dir').val();
      //alert(prov);
      $.ajax(
            {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/paciente/parroquia", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "canton=" + canton, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                  },
                  success: function (requestData) {//armar la tabla
                        $("#comboParroq").html(requestData);
                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}
function mostrarModal(id) {
      $("#exampleModalLabel").text("Informacion Paciente");
      //var dir = $('#dir').val();
      $('#paciente_id').val(id);
      $("#accionForm").html('<button class="btn btn-primary" type="submit"  >Aceptar</button>');
      $('#formModal').modal({
            show: true
      });
      DatosPaciente();
}

function DatosPaciente() {

      var paciente_id = $('#paciente_id').val();
      var dir = $('#dir').val();
      if (paciente_id.length) {
            $.ajax({
                  dataType: "json",
                  type: "POST",
                  url: dir + "/paciente/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "paciente=" + paciente_id, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                        //console.log(data)
                        //$('#contenido').html("Cargando contenido...");

                  },
                  success: function (requestData) {//armar la tabla                        
                        if (requestData.data) {
                              $('.d-none').removeClass('d-none');
                              $('#paciente_id').text(requestData.data.p_id);
                              $('#paciente_hc').text(requestData.data.p_hc);
                              $('#apellido_paterno').text(requestData.data.p_apellidos + " " + requestData.data.p_nombres);
                              $('#cedula').text(requestData.data.p_ci);
                              $('#telefono').text(requestData.data.p_telefono);
                              $('#Prov').text("Provincia: " + requestData.data.nombre_provincia);
                              // getCantones();
                              $('#Cant').text("Canton: " + requestData.data.nombre_canton);
                              //getParroquias();
                              $('#Parroq').text("Parroquia: " + requestData.data.nombre_parroquia);
                              $('#barrio').text("Barrio: " + requestData.data.p_barrio);
                              $('#direccion').text("Direccion: " + requestData.data.p_direccion);
                              $('#fecha_n').text(requestData.data.p_fecha_n);
                              $('#lugar_n').text(requestData.data.p_lugar_n);
                              $('#Nacionalidad').text(requestData.data.descrip_lista);
                              $('#Grupo').text(requestData.data.nombre_grupcultural);
                              $('#Edad').text(requestData.data.p_edad);
                              $('#Genero').text(requestData.data.p_sexo);
                              $('#Estado').text(requestData.data.nombre_estcivil);
                              $('#Instruccion').text(requestData.data.descripcion_inst);
                              $('#ocupacion').text(requestData.data.p_ocupacion);
                              $('#trabajo').text(requestData.data.p_trabajo);
                              $('#TipoSeguro').text(requestData.data.p_tipo_seguro);
                              $('#referido').text(requestData.data.p_referido);
                              $('#contacto_nombre').text(requestData.data.p_contacto);
                              $('#contacto_parentezco').text(requestData.data.p_contacto_parentezco);
                              $('#contacto_direccion').text(requestData.data.p_contacto_direc);
                              $('#contacto_telefono').text(requestData.data.p_contacto_tlfn);
                              $('#Forma').text(requestData.data.p_forma_llegada);
                              $('#fuente_info').text(requestData.data.p_fuente_inf);
                              $('#institucion').text(requestData.data.p_quien_entrega);
                              $('#institucion_telefono').text(requestData.data.p_quien_entrega_tlfn);
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
                                    title: 'Paciente encontrado!'
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
                                    title: 'No se encontraron resultados!'
                              })
                        }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                              msg = 'No conectado: Verifique la red.';
                        } else if (jqXHR.status == 404) {
                              msg = 'Página solicitada no encontrada [404]';
                        } else if (jqXHR.status == 500) {
                              msg = 'Solo datos numericos.';
                        } else {
                              msg = 'No se encontraron resultados [500]';
                        }
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
                              title: msg
                        })
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
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
                  icon: 'warning',
                  title: 'Debe ingresar algun campo a buscar'
            })
      }
}
function AdmisionPaciente() {
      var dir = $('#dir').val();
      var dataString = $('#paciente_admision').serialize(); //recorre todo el formulario
      $("#paciente_admision").submit(function (event) {
            event.preventDefault(); //prevent default action  
            if (($('#cedula').val().length == 10) && ($('#telefono').val().length == 10)
                  && ($('#contacto_telefono').val().length == 10) && ($('#institucion_telefono').val().length == 10)) {
                  $.ajax({
                        dataType: "html",
                        type: "POST",
                        url: dir + "/paciente/admision", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: dataString, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                              //console.log(data)
                        },
                        success: function (requestData) {//armar la tabla
                              const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    onOpen: (toast) => {
                                          toast.addEventListener('mouseenter', Swal.stopTimer)
                                          toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                              });
                              Toast.fire({
                                    icon: 'success',
                                    title: 'Paciente registrado correctamente!'
                              }).then((result) => {
                                    window.location.href = dir + "/paciente/registrar";

                              });



                        },
                        error: function (requestData, strError, strTipoErro) {
                              // alert(xhr.statusText+" "+xhr.status);
                              //alert(xhr.responseText);
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                  });
            }

      });

}
function BuscaPaciente() {
      var dir = $('#dir').val();
      var paciente = $('#busca-paciente').val(); //input
      $("#paciente_busca").submit(function (event) {
            event.preventDefault(); //prevent default action  
            if (paciente.length) {
                  $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: dir + "/paciente/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "paciente=" + paciente, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                        },
                        success: function (requestData) {//armar la tabla                        
                              if (requestData.data) {
                                    $("#accionForm").html('<button type="submit" class="btn btn-secondary btn-block" onclick="EditarAdmisionPaciente();"><i class="fas fa-pen"></i> Actualizar datos</button>');
                                    var array_apellidos = requestData.data.p_apellidos.split(" ");
                                    var array_nombres = requestData.data.p_nombres.split(" ");
                                    $('#apellido_paterno').val(array_apellidos[0]);
                                    $('#apellido_materno').val(array_apellidos[1]);
                                    $('#primer_nombre').val(array_nombres[0]);
                                    $('#segundo_nombre').val(array_nombres[1]);
                                    $('#cedula').val(requestData.data.p_ci);
                                    $('#telefono').val(requestData.data.p_telefono);

                                    $('#comboProv').val(requestData.data.id_provincia);
                                    //getCantones();
                                    $('#comboCant').val(requestData.data.id_canton);
                                    //getParroquias();
                                    $('#comboParroq').val(requestData.data.id_parroquia);
                                    $('#barrio').val(requestData.data.p_barrio);
                                    $('#direccion').val(requestData.data.p_direccion);
                                    $('#fecha_n').val(requestData.data.p_fecha_n);
                                    $('#lugar_n').val(requestData.data.p_lugar_n);
                                    $('#comboNacionalidad').val(requestData.data.p_nacionalidad);
                                    $('#comboGrupo').val(requestData.data.p_grupo_c);
                                    $('#comboEdad').val(requestData.data.p_edad);
                                    $('#comboGenero').val(requestData.data.p_sexo);
                                    $('#comboEstado').val(requestData.data.p_est_civil);
                                    $('#comboInstruccion').val(requestData.data.p_instruccion);
                                    $('#ocupacion').val(requestData.data.p_ocupacion);
                                    $('#trabajo').val(requestData.data.p_trabajo);
                                    $('#comboTipoSeguro').val(requestData.data.p_tipo_seguro);
                                    $('#referido').val(requestData.data.p_referido);
                                    $('#contacto_nombre').val(requestData.data.p_contacto);
                                    $('#contacto_parentezco').val(requestData.data.p_contacto_parentezco);
                                    $('#contacto_direccion').val(requestData.data.p_contacto_direc);
                                    $('#contacto_telefono').val(requestData.data.p_contacto_tlfn);
                                    $('#comboForma').val(requestData.data.p_forma_llegada);
                                    $('#fuente_info').val(requestData.data.p_fuente_inf);
                                    $('#institucion').val(requestData.data.p_quien_entrega);
                                    $('#institucion_telefono').val(requestData.data.p_quien_entrega_tlfn);
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
                                          title: 'Paciente encontrado!'
                                    })
                                    paciente = "";
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
                                          title: 'No se encontraron resultados!'
                                    })
                              }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                              var msg = '';
                              if (jqXHR.status === 0) {
                                    msg = 'No conectado: Verifique la red.';
                              } else if (jqXHR.status == 404) {
                                    msg = 'Página solicitada no encontrada [404]';
                              } else if (jqXHR.status == 500) {
                                    msg = 'Solo datos numericos.';
                              } else {
                                    msg = 'No se encontraron resultados [500]';
                              }
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
                                    title: msg
                              })
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
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
                        icon: 'warning',
                        title: 'Debe ingresar algun campo a buscar'
                  })
            }

      });

}

function getCamas(especialidad_id) {
      var dir = $('#dir').val();
      //alert(dir+" "+especialidad_id);
      $.ajax(
            {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/paciente/getcamas", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "especialidad_id=" + especialidad_id, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                        //alert(dir + " " + especialidad_id);
                  },
                  success: function (requestData) {//armar la tabla
                        $("#data_Table1").html(requestData);
                  },
                  error: function (requestData, strError, strTipoError) {
                        alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}