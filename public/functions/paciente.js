
function setNacidos() {
      var nacidos = $('input:radio[name=recienNacidoRadio]:checked').val();
      console.log(nacidos)
      if (nacidos == 'SI') {
            $("#divNacidos").removeClass('d-none');
      } else {
            $("#numNacidosVivos").val("");
            $("#divNacidos").addClass('d-none');
      }
}
function calculaEdad(){
      var fecha_nac = $('#fecha_n').val();
   $('#comboEdad').val(moment().diff(fecha_nac,'years',false));
      //console.log(fecha_nac)
}
function eligeCama(especialidad_id, especialidad, habitacion, cama, cama_id, estado) {
      //console.log(cama)
      if ((estado == 0)) { //verifica si el estado de la cama es DISPONIBLE
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
            $('#especialidad_id').val(especialidad_id);
            $('#cama').text(cama);
      } else if ((estado == 1)) {
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
                  icon: 'error',
                  title: 'La cama se encuentra ocupada!'
            })
            $('#servicio').text('');
            $('#habitacion').text('');
            $('#cama_id').val('');
            $('#especialidad_id').val('');
            $('#cama').text('');
      } else if ((estado == 2)) {
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
                  title: 'La cama se encuentra en desinfeccion!'
            })
            $('#servicio').text('');
            $('#habitacion').text('');
            $('#cama_id').val('');
            $('#especialidad_id').val('');

            $('#cama').text('');
      }
}
function setDiagnostico(id, cod, descripcion) {
      for (let i = 1; i <= 3; i++) {
            if (id == 'diagnostico' + i) {
                  $("#diagnostico" + i).val(descripcion);
                  $("#cod" + i).text(cod);
                  $("#lista" + i).html("");
                  $("#pre" + i).prop("checked", true);
            }
      }

}
//formulario diagnostico
function getDiagnostico(diagnostico) {
      var id = $(diagnostico).attr("id");
      var dato = $(diagnostico).val();
      var dir = $('#dir').val();
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
                              for (let i = 0; i <= 3; i++) {
                                    if (id == 'diagnostico' + i) {
                                          $("#lista" + i).html(requestData);
                                    }

                              }
                        },
                        error: function (requestData, strError, strTipoError) {
                              //alert(requestData, strError, strTipoError)
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                  });
      } else {
            for (let i = 0; i <= 3; i++) {
                  if ($('#diagnostico' + i).val() == "") {
                        $("#cod" + i).text('');
                        $("#pre" + i).prop("checked", false);
                        $("#def" + i).prop("checked", false);
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

      }

}
function asignarCama() {
      var Toast;
      var dir = $('#dir').val();
      var paciente_hc = $('#paciente_hc').text();
      var opcionBusquedaPaciente = $('#opcionBusquedaPaciente').val();
      var cedula = $('#cedula').text();
      var especialidad_id = $('#especialidad_id').val();
      var cama_id = $('#cama_id').val();
      var cie10_cod = [];
      var cie10_tipo = [];
      console.log(paciente_hc + ' ' + opcionBusquedaPaciente + ' ' + cedula + ' ' + especialidad_id + ' ' + cama_id)
      for (let i = 1; i <= 3; i++) {
            cie10_cod[i - 1] = ["'" + $('#cod' + i).text() + "'"];
            if (($("#pre" + i).is(':checked')) && ($('#cod' + i).text().length)) {
                  cie10_tipo[i - 1] = ["'PRE'"];
            } else if (($("#def" + i).is(':checked')) && ($('#cod' + i).text().length)) {
                  cie10_tipo[i - 1] = ["'DEF'"];
            } else {
                  cie10_tipo[i - 1] = ["''"];
            }
      }
      if (paciente_hc.length) {
            if (cama_id.length) {
                  if ((cie10_cod[0] == "''") && (cie10_cod[1] == "''") && (cie10_cod[2] == "''")) {
                        $("#diagnostico1").focus();
                        Toast = Swal.mixin({
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
                              title: 'Debe ingresar al menos un diagnostico'
                        })

                  } else {
                        Swal.fire({
                              position: 'top',
                              title: 'Está seguro?',
                              text: "¡Seleccione 'Aceptar' para confirmar la asignacion de cama!",
                              icon: 'question',
                              width: '22rem',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Aceptar',
                              cancelButtonText: 'Cancelar'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax(
                                          {
                                                dataType: "html",
                                                type: "POST",
                                                url: dir + "/paciente/asignarcama", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                                                data: "paciente_hc=" + paciente_hc + "&cedula=" + cedula
                                                      + "&cama_id=" + cama_id + "&cie10_cod=" + cie10_cod + "&cie10_tipo=" + cie10_tipo +
                                                      "&especialidad_id=" + especialidad_id + "&opcion=" + opcionBusquedaPaciente, //Se añade el parametro de busqueda del medico
                                                beforeSend: function (data) {
                                                },
                                                success: function (requestData) {//armar la tabla
                                                      if (requestData == 'error') {
                                                            Swal.fire({
                                                                  position: 'top',
                                                                  title: 'Error!',
                                                                  text: 'El paciente ya tiene asignada una cama',
                                                                  html: 'El paciente ya tiene asignada una cama<br>' +
                                                                        'Desea realizar un <b>CAMBIO DE CAMA/SERVICIO?</b>, <br>' +
                                                                        '<a href="' + dir + '/cambio_cama_paciente">Realizar cambio</a>',
                                                                  width: '32rem',
                                                                  icon: 'error',
                                                                  confirmButtonText: 'Aceptar'
                                                            })
                                                      } else {
                                                            Toast = Swal.mixin({
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
                                                                  title: 'Cama asignada correctamente'
                                                            })
                                                            getCamas(especialidad_id);
                                                            //limpiar el formulario
                                                            limpiaFormAsignacion();
                                                      }
                                                },
                                                error: function (requestData, strError, strTipoError) {
                                                      //alert(requestData, strError, strTipoError)
                                                },
                                                complete: function (requestData, exito) { //fin de la llamada ajax.

                                                }
                                          });
                              }
                        })
                  }
            } else {
                  getCamas(1);
                  Toast = Swal.mixin({
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
                        title: 'Debe seleccionar una cama'
                  })
            }
      } else {
            $("#paciente_id").removeClass('border border-success').addClass('border border-danger').focus();
            Toast = Swal.mixin({
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
                  title: 'Debe ingresar un paciente'
            })
      }


}
function limpiaFormAsignacion() {
      $('#paciente_hc').text('');
      $('#paciente_id').val('').removeClass('border border-danger').focus();

      $('#cedula').text('');
      $('#apellido_paterno').text('');
      $('#servicio').text('');
      $('#habitacion').text('');
      $('#cama').text('');
      $('#especialidad_id').val('');
      $('#cama_id').val('');
      for (let i = 1; i <= 3; i++) {
            $('#diagnostico' + i).val('');
            $('#cod' + i).text('');
            $("#pre" + i).prop("checked", false);
            $("#def" + i).prop("checked", false);
      }
}
function getCantones(canton, parroquia) {
      canton = typeof canton !== 'undefined' ? canton : ''; // 3 es un valor por defecto e incluye todos los datos
      parroquia = typeof parroquia !== 'undefined' ? parroquia : ''; // 3 es un valor por defecto e incluye todos los datos
      var prov = $("#comboProv").val(); // 3 es un valor por defecto e incluye todos los datos
      var dir = $('#dir').val();
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
                        $("#comboCant").val(canton);
                        getParroquias(parroquia);
                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}
function getParroquias(parroquia) {
      parroquia = typeof parroquia !== 'undefined' ? parroquia : ''; // 3 es un valor por defecto e incluye todos los datos

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
                        $("#comboParroq").val(parroquia);
                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}
function DatosCamaPaciente() {
      $('#p_especialidad').text('');
      $('#p_habitacion').text('');
      $('#p_cama').text('');
      $('#p_fecha').text('');
      $('#p_causa').text('');
      for (let i = 1; i <= 3; i++) {
            $("#diagnostico" + i).val('');
            $("#cod" + i).text('');
            $("#pre" + i).prop("checked", false);
            $("#def" + i).prop("checked", false);

      }
      var tipo = [];
      var paciente_id = $('#paciente_id').val();
      var dir = $('#dir').val();
      $.ajax({
            dataType: "json",
            type: "POST",
            url: dir + "/paciente/buscacamapaciente", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
            data: "paciente=" + paciente_id, //Se añade el parametro de busqueda del medico
            beforeSend: function (data) {
                  $('#mensaje').removeClass('d-none').html('Procesando...');
                  $('#datosPacienteCama').addClass('d-none');
                  $('#datosPacienteDiagnosticos').addClass('d-none');
                  $('#datosPacienteMasInfo').addClass('d-none');
                  $('#datosPacienteContacto').addClass('d-none');
            },
            success: function (requestData) {//armar la tabla
                  $('#mensaje').addClass('d-none').html('');
                  if (requestData.data[0]) {
                        $('#datosPacienteCama').removeClass('d-none');
                        $('#datosPacienteDiagnosticos').removeClass('d-none');
                        $('#p_especialidad').text(requestData.data[0].especialidad_nombre);
                        $('#p_habitacion').text(requestData.data[0].habitacion_nombre);
                        $('#p_cama').text(requestData.data[0].cama_nombre);
                        $('#p_fecha').text(requestData.data[0].fecha_ingreso);
                        $('#p_causa').text(requestData.data[0].causa_descripcion);
                        tipo = requestData.data[0].tipo_diagnosticos.slice(1, -1).split(',');
                        //console.log(requestData.data[0].descripcion_sub)                        
                        for (let i = 1; i <= 3; i++) {
                              $("#diagnostico" + i).val(requestData.data[(i - 1)].descripcion_sub).prop('disabled', true).addClass('bg-white');
                              $("#cod" + i).text(requestData.data[(i - 1)].sub_cod);
                              //console.log(requestData.data[0].tipo_diagnosticos)   

                              if (tipo[i - 1] == 'PRE') {
                                    $("#pre" + i).prop("checked", true).prop('disabled', true);
                                    $("#def" + i).prop("checked", false).prop('disabled', true);


                              } else if (tipo[i - 1] == 'DEF') {
                                    $("#def" + i).prop("checked", true).prop('disabled', true);
                                    $("#pre" + i).prop("checked", false).prop('disabled', true);

                              } else {
                                    $("#pre" + i).prop("checked", false).prop('disabled', true);
                                    $("#def" + i).prop("checked", false).prop('disabled', true);
                              }
                        }
                  } else {

                  }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (requestData, exito) { //fin de la llamada ajax.

            }
      });

}
function setDatosPaciente() {
      $('#opcionCausa').prop('disabled', false).val('');
      $("#data_TableCamas").html('');
      $("#cardEspecialidades").addClass('d-none');
      var paciente = $('#opcionPaciente').val();
      var dir = $('#dir').val();
      for (let i = 1; i <= 3; i++) {
            $("#diagnostico" + i).val('');
            $("#cod" + i).text('');
            $("#pre" + i).prop("checked", false);
            $("#def" + i).prop("checked", false);

      }
      $.ajax(
            {
                  dataType: "json",
                  type: "POST",
                  url: dir + "/paciente/buscacamapaciente", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "paciente=" + paciente, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                        //$("#data_PacienteInfo").html('<div class="d-flex justify-content-center  text-primary py-4"><i class="fas fa-spinner fa-pulse fa-5x "></i></div>');
                  },
                  success: function (requestData) {//armar la tabla
                        console.log(requestData.data[0])
                        $('.dataPacienteCama').removeClass('d-none');
                        
                        $('#paciente_hc').text(requestData.data[0].p_id);
                        $('#cedula').text(requestData.data[0].paciente_ci);
                        $('#paciente_nombre').text($('#opcionPaciente option:selected').text());

                        $('#cama_paciente_id').val(requestData.data[0].cama_paciente_id);
                        $('#entrada').val(requestData.data[0].entrada);
                        $('#cama_id_origen').val(requestData.data[0].cama_id);
                        $('#especialidad_id_origen').val(requestData.data[0].especialidad_id);
                        $('#servicio_origen').text(requestData.data[0].especialidad_nombre);
                        $('#habitacion_origen').text(requestData.data[0].habitacion_nombre);
                        $('#cama_origen').text(requestData.data[0].cama_nombre);
                        tipo = requestData.data[0].tipo_diagnosticos.slice(1, -1).split(',');
                        //console.log(requestData.data[0].descripcion_sub)                        
                        for (let i = 1; i <= 3; i++) {
                              $("#diagnostico" + i).val(requestData.data[(i - 1)].descripcion_sub).addClass('bg-white');
                              $("#cod" + i).text(requestData.data[(i - 1)].sub_cod);
                              //console.log(requestData.data[0].tipo_diagnosticos)   

                              if (tipo[i - 1] == 'PRE') {
                                    $("#pre" + i).prop("checked", true);
                                    $("#def" + i).prop("checked", false);


                              } else if (tipo[i - 1] == 'DEF') {
                                    $("#def" + i).prop("checked", true);
                                    $("#pre" + i).prop("checked", false);

                              } else {
                                    $("#pre" + i).prop("checked", false);
                                    $("#def" + i).prop("checked", false);
                              }
                        }
                        //getCamasCambio();

                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });
}
function DatosPaciente(paciente_id, opcion) {
      var paciente = typeof paciente_id !== 'undefined' ? paciente_id : $('#paciente_id').val();
      var op = typeof opcion !== 'undefined' ? opcion : $('#opcionBusquedaPaciente').val();
      var dir = $('#dir').val();
      if (op != '') {
            if (paciente != '') {
                  $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: dir + "/paciente/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "paciente=" + paciente + "&opcion=" + op, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                              $('#mensaje').removeClass('d-none').html('Procesando...');
                              $('#navs-info').html('Procesando...');
                              $('#datosPacienteCama').addClass('d-none');
                              $('#datosPacienteDiagnosticos').addClass('d-none');
                              $('#datosPacienteMasInfo').addClass('d-none');
                              $('#datosPacienteContacto').addClass('d-none');

                        },
                        success: function (requestData) {//armar la tabla 
                              $('#mensaje').addClass('d-none').html('');
                              if (requestData.data) {
                                    if (op == 1) { //emergencia
                                          $('#navs-info').html(`<nav class="nav nav-pills flex-column flex-sm-row text-xs" >
                                                      <a class="flex-sm-fill text-sm-center nav-link active py-1" id="info-tab" data-toggle="tab"
                                                            href="#datosPacienteMasInfo" role="tab" aria-controls="datosPacienteMasInfo" aria-selected="false">
                                                            <i class="fas fa-plus-circle px-2"></i>Mas Info</a>
                                                      <a class="flex-sm-fill text-sm-center nav-link  py-1" id="paciente-tab" data-toggle="tab"
                                                            href="#datosPacienteContacto" role="tab" aria-controls="datosPacienteContacto" aria-selected="true">
                                                            <i class="fas fa-address-book px-2"></i>Contacto</a>
                                                      <a class="flex-sm-fill text-sm-center nav-link py-1" id="cama-tab" data-toggle="tab"
                                                            href="#datosPacienteCama" role="tab" aria-controls="datosPacienteCama" aria-selected="false">
                                                            <i class="fas fa-bed px-2"></i>Cama</a>
                                                      <a class="flex-sm-fill text-sm-center nav-link py-1" id="diag-tab" data-toggle="tab"
                                                            href="#datosPacienteDiagnosticos" role="tab" aria-controls="datosPacienteDiagnosticos"
                                                            aria-selected="false">
                                                            <i class="fas fa-heartbeat px-2"></i>Diagnosticos</a>
                                                </nav>`);
                                          $('#datosPacienteCama').removeClass('d-none');
                                          $('#datosPacienteDiagnosticos').removeClass('d-none');
                                          $('#datosPacienteMasInfo').removeClass('d-none');
                                          $('#datosPacienteContacto').removeClass('d-none');

                                          var contacto = requestData.data.p_contacto.slice(1, -1).split(','); //--elimina el primer y ultimo caracter y separa los elementos
                                          $('#contacto_nombre').text(contacto[0]);
                                          $('#contacto_parentezco').text(contacto[1]);
                                          $('#contacto_direccion').text(contacto[2]);
                                          $('#contacto_telefono').text(contacto[3]);

                                          $('#telefono').text(requestData.data.p_telefono);
                                          $('#Prov').text(requestData.data.nombre_provincia);
                                          // getCantones();
                                          $('#Cant').text(requestData.data.nombre_canton);
                                          //getParroquias();
                                          $('#Parroq').text(requestData.data.nombre_parroquia);
                                          $('#barrio').text(requestData.data.p_barrio);
                                          $('#direccion').text(requestData.data.p_direccion);
                                          $('#fecha_n').text(requestData.data.p_fecha_n);
                                          $('#lugar_n').text(requestData.data.p_lugar_n);
                                          $('#Nacionalidad').text(requestData.data.descripcion);
                                          $('#Grupo').text(requestData.data.nombre_grupocultural);

                                          $('#paciente_hc').text(requestData.data.p_id);
                                          $('#paciente_id').text(requestData.data.p_id);
                                          $('#apellido_paterno').text(requestData.data.p_apellidos + " " + requestData.data.p_nombres);
                                          $('#cedula').text(requestData.data.nuhc);
                                    } else if (op == 2) {
                                          $('#navs-info').html(`<nav class="nav nav-pills flex-column flex-sm-row text-xs" >
                                                      
                                                      <a class="flex-sm-fill text-sm-center nav-link active py-1" id="cama-tab" data-toggle="tab"
                                                            href="#datosPacienteCama" role="tab" aria-controls="datosPacienteCama" aria-selected="false">
                                                            <i class="fas fa-bed px-2"></i>Cama</a>
                                                      <a class="flex-sm-fill text-sm-center nav-link py-1" id="diag-tab" data-toggle="tab"
                                                            href="#datosPacienteDiagnosticos" role="tab" aria-controls="datosPacienteDiagnosticos"
                                                            aria-selected="false">
                                                            <i class="fas fa-heartbeat px-2"></i>Diagnosticos</a>
                                                </nav>`);
                                          $('#datosPacienteCama').removeClass('d-none');
                                          $('#datosPacienteDiagnosticos').removeClass('d-none');

                                          $('#paciente_hc').text(requestData.data.hc_digital);
                                          $('#paciente_id').text(requestData.data.hc_digital);
                                          $('#apellido_paterno').text(requestData.data.apellido_paterno + " " +
                                                requestData.data.apellido_materno + " " + requestData.data.primer_nombre + " " + requestData.data.segundo_nombre);
                                          $('#cedula').text(requestData.data.ci);
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
                  $('#paciente_hc').text('');
                  $('#apellido_paterno').text('');
                  $('#cedula').text('');
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
      } else {
            $('#paciente_hc').text('');
            $('#apellido_paterno').text('');
            $('#cedula').text('');
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
                  title: 'Debe seleccionar una opcion'
            })
      }
}
function AdmisionPaciente() {
      var dir = $('#dir').val();
      $('#numNac').val($('#numNacidosVivos').val());
      var dataString = $('#paciente_admision').serialize(); //recorre todo el formulario
      console.log(dataString)
      $("#paciente_admision").submit(function (event) {
            event.preventDefault(); //prevent default action  
            //if (($('#cedula').val().length == 10) && ($('#telefono').val().length == 10)
                  //&& ($('#contacto_telefono').val().length == 10) && ($('#institucion_telefono').val().length == 10)) {
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
                                    window.location.href = dir + "/registrar_paciente";

                              });



                        },
                        error: function (requestData, strError, strTipoErro) {
                              // alert(xhr.statusText+" "+xhr.status);
                              //alert(xhr.responseText);
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                  });
            //}

      });

}

function EditarAdmisionPaciente() {
      var dir = $('#dir').val();
      $('#numNac').val($('#numNacidosVivos').val());
      var dataString = $('#paciente_admision').serialize(); //recorre todo el formulario
      $("#paciente_admision").submit(function (event) {
            event.preventDefault(); //prevent default action  
          //  if (($('#cedula').val().length == 10) && ($('#telefono').val().length == 10)
                //  && ($('#contacto_telefono').val().length == 10) && ($('#institucion_telefono').val().length == 10)) {
                  $.ajax({
                        dataType: "html",
                        type: "POST",
                        url: dir + "/paciente/editaadmision", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
                                    title: 'Informacion actualizada!'
                              }).then((result) => {
                                    window.location.href = dir + "/registrar_paciente";

                              });

                        },
                        error: function (requestData, strError, strTipoErro) {
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                  });
           // }

      });

}
function BuscaPaciente() {
      var paciente = $('#busca_paciente').val(); // 

      var dir = $('#dir').val();
      $("#form_paciente_busca").submit(function (event) {
            event.preventDefault(); //prevent default action 
            if (paciente.length) {
                  $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: dir + "/paciente/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "paciente=" + paciente + "&opcion=" + 1, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                        },
                        success: function (requestData) {//armar la tabla                          
                              if (requestData.data) {
                                    $('#btn_admision').addClass('d-none');
                                    $('#btn_edicion').removeClass('d-none');
                                    var array_apellidos = requestData.data.p_apellidos.split(" ");
                                    var array_nombres = requestData.data.p_nombres.split(" ");
                                    $('#apellido_paterno').val(array_apellidos[0]);
                                    $('#apellido_materno').val(array_apellidos[1]);
                                    $('#primer_nombre').val(array_nombres[0]);
                                    $('#segundo_nombre').val(array_nombres[1]);
                                    $('#cedula').val(requestData.data.p_ci);
                                    $('#telefono').val(requestData.data.p_telefono);

                                    $('#comboProv').val(requestData.data.id_provincia);
                                    getCantones(requestData.data.id_canton, requestData.data.id_parroquia);

                                    $('#barrio').val(requestData.data.p_barrio);
                                    $('#direccion').val(requestData.data.p_direccion);
                                    $('#fecha_n').val(requestData.data.p_fecha_n);
                                    $('#lugar_n').val(requestData.data.p_lugar_n);
                                    $('#comboNacionalidad').val(requestData.data.nacionalidad_id);
                                    $('#comboGrupo').val(requestData.data.id_grupocultural);
                                 $('#comboEdad').val(requestData.data.p_edad);
                                    $('#comboGenero').val(requestData.data.p_sexo);
                                    $('#comboEstado').val(requestData.data.id_estcivil);
                                    $('#comboInstruccion').val(requestData.data.id_instruccion);
                                    $('#ocupacion').val(requestData.data.p_ocupacion);
                                    $('#trabajo').val(requestData.data.p_trabajo);
                                    $('#comboTipoSeguro').val(requestData.data.tipo_seguro_id);
                                    $('#referido').val(requestData.data.p_referido);
                                    var arreglo = requestData.data.p_contacto.slice(1, -1).split(','); //--elimina el primer y ultimo caracter y separa los elementos
                                    $('#contacto_nombre').val(arreglo[0].replace(/[^a-zA-Z ]/g, ""));
                                    $('#contacto_parentezco').val(arreglo[1].replace(/[^a-zA-Z ]/g, ""));
                                    $('#contacto_direccion').val(arreglo[2].replace(/[^a-zA-Z ]/g, ""));
                                    $('#contacto_telefono').val(arreglo[3].replace(/[^0-9 ]/g, ""));
                                    $('#comboForma').val(requestData.data.p_forma_lleg_id);
                                    $('#fuente_info').val(requestData.data.p_fuente_inf);
                                    var arreglo2 = requestData.data.p_quien_entrega.slice(1, -1).split(','); //--elimina el primer y ultimo caracter y separa los elementos

                                    $('#institucion').val(arreglo2[0].replace(/[^a-zA-Z ]/g, ""));
                                    $('#institucion_telefono').val(arreglo2[1].replace(/[^0-9 ]/g, ""));
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
                                    $('#btn_edicion').addClass('d-none');
                                    $('#btn_admision').removeClass('d-none');
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
                                    msg = 'Error en la aplicacion.';
                              } else {
                                    msg = 'No se encontraron resultados [500]';
                                    $('#btn_edicion').addClass('d-none');
                                    $('#btn_admision').removeClass('d-none');
                                    $("#paciente_admision").trigger('reset');
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
                        $("#data_TableCamas").html('<div class="d-flex justify-content-center  text-primary py-4"><i class="fas fa-spinner fa-pulse fa-5x "></i></div>');
                  },
                  success: function (requestData) {//armar la tabla
                        $("#data_TableCamas").html(requestData);
                  },
                  error: function (requestData, strError, strTipoError) {
                        alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}
function getCamasCambio() {
      var dir = $('#dir').val();
      var especialidad_id = $('#especialidad_id_origen').val();
      var cama_id_origen = $('#cama_id_origen').val();
      var opcionCausa = $('#opcionCausa').val();
      //alert(opcionCausa);
      if (especialidad_id.length || opcionCausa.length) {
           
            if (opcionCausa == 7) { //en el mismo servicio            
                  $.ajax(
                        {
                              dataType: "html",
                              type: "POST",
                              url: dir + "/paciente/getcamas", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                              data: "especialidad_id=" + especialidad_id, //Se añade el parametro de busqueda del medico
                              beforeSend: function (data) {
                                    $("#data_TableCamas").html('<div class="d-flex justify-content-center  text-primary py-4"><i class="fas fa-spinner fa-pulse fa-5x "></i></div>');
                              },
                              success: function (requestData) {//armar la tabla
                                    $("#TituloCambioDeCama").text('Cambio de cama en el mismo servicio');
                                    $("#cardEspecialidades").removeClass('d-none');
                                    $("#dataEspecialidades").addClass('d-none');
                                    
                                    $("#data_TableCamas").html(requestData).find("table tbody tr td button#" + cama_id_origen).
                                    removeClass('btn btn-outline-danger').addClass('btn btn-outline-success').prop('title','Cama actual');
                                    $("#data_TableCamas").find('table caption ').append('<span class="badge badge-success text-wrap px-2">Actual</span>')
                              },
                              error: function (requestData, strError, strTipoError) {
                                    //alert(requestData, strError, strTipoError)
                              },
                              complete: function (requestData, exito) { //fin de la llamada ajax.

                              }
                        });
            } else if (opcionCausa == 6) { //en otro servicio
                  $("#TituloCambioDeCama").text('Cambio de cama en diferente servicio');
                  $("#dataEspecialidades").removeClass('d-none').find("li a#" + especialidad_id).addClass('d-none');
                  $("#cardEspecialidades").removeClass('d-none');
                  if (especialidad_id == 1) {
                        getCamas(2);
                  } else {
                        getCamas(1);
                  }
            } else if (opcionCausa == 5) { //en otro servicio
                  $("#TituloCambioDeCama").text('Referencia');
                  $("#dataEspecialidades").addClass('d-none');
                  $("#data_TableCamas").html('<div class="alert alert-info text-left">Haga clic en <strong>Finalizar</strong> para confirmar la <strong>Referencia</strong> del paciente</div>');
                  $("#cardEspecialidades").removeClass('d-none');
            } else if (opcionCausa == 4) { //en otro servicio
                  $("#TituloCambioDeCama").text('Egreso / Alta de paciente');
                  $("#dataEspecialidades").addClass('d-none');
                  $("#data_TableCamas").html('<div class="alert alert-info text-left">Haga clic en <strong>Finalizar</strong> para confirmar el <strong>Egreso / Alta</strong> del paciente</div>');

                  $("#cardEspecialidades").removeClass('d-none');
            } else if (opcionCausa == 3) { //en otro servicio
                  $("#TituloCambioDeCama").text('Defuncion');
                  $("#dataEspecialidades").addClass('d-none');
                  $("#data_TableCamas").html('<div class="alert alert-info text-left">Haga clic en <strong>Finalizar</strong> para confirmar la <strong>Defuncion</strong> del paciente</div>');

                  $("#cardEspecialidades").removeClass('d-none');
            } else if (opcionCausa == 2) { //en otro servicio
                  $("#TituloCambioDeCama").text('Contrareferencia');
                  $("#dataEspecialidades").addClass('d-none');
                  $("#data_TableCamas").html('<div class="alert alert-info text-left">Haga clic en <strong>Finalizar</strong> para confirmar la <strong>Contrareferencia</strong> del paciente</div>');

                  $("#cardEspecialidades").removeClass('d-none');
            }
      }
      
      

}
function cambioCama() {
      var Toast;
      var dir = $('#dir').val();
      var cama_paciente_id = $('#cama_paciente_id').val();      
      var opcionCausa = $('#opcionCausa').val();
      var cie10_cod = [];
      var cie10_tipo = [];
      var cama_origen=$('#cama_id').val();
      var cama_destino=$('#cama_id_origen').val();
      var cama_id = (opcionCausa == 6 || opcionCausa == 7) ? cama_origen : cama_destino ;
      
      for (let i = 1; i <= 3; i++) {
            cie10_cod[i - 1] = ["'" + $('#cod' + i).text() + "'"];
            if (($("#pre" + i).is(':checked')) && ($('#cod' + i).text().length)) {
                  cie10_tipo[i - 1] = ["'PRE'"];
            } else if (($("#def" + i).is(':checked')) && ($('#cod' + i).text().length)) {
                  cie10_tipo[i - 1] = ["'DEF'"];
            } else {
                  cie10_tipo[i - 1] = ["''"];
            }
      }
      if (cama_paciente_id.length) {
            if (opcionCausa.length) {
                  if (cama_id.length) {
                        if ((cie10_cod[0] == "''") && (cie10_cod[1] == "''") && (cie10_cod[2] == "''")) {
                              $("#diagnostico1").focus();
                              Toast = Swal.mixin({
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
                                    title: 'Debe ingresar al menos un diagnostico'
                              })

                        } else {
                              Swal.fire({
                                    position: 'top',
                                    title: 'Está seguro?',
                                    text: "¡Seleccione 'Aceptar' para confirmar el cambio de cama!",
                                    icon: 'question',
                                    width: '22rem',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Aceptar',
                                    cancelButtonText: 'Cancelar'
                              }).then((result) => {
                                    if (result.value) {
                                          $.ajax(
                                                {
                                                      dataType: "html",
                                                      type: "POST",
                                                      url: dir + "/paciente/updatecama", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                                                      data: "cama_paciente_id=" + cama_paciente_id + "&opcionCausa=" + opcionCausa
                                                            + "&cama_id=" + cama_id + "&cie10_cod=" + cie10_cod + "&cie10_tipo=" + cie10_tipo , //Se añade el parametro de busqueda del medico
                                                      beforeSend: function (data) {
                                                      },
                                                      success: function (requestData) {
                                                            //localStorage.setItem(cama_origen, cama_origen);
                                                                  Toast = Swal.mixin({
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
                                                                        title: 'Informacion actualizada correctamente'
                                                                  }).then((result) => {
                                                                        window.location.href = dir + "/cambio_cama_paciente";

                                                                  });
                                                                  
                                                      },
                                                      error: function (requestData, strError, strTipoError) {
                                                            alert(requestData, strError, strTipoError)
                                                      },
                                                      complete: function (requestData, exito) { //fin de la llamada ajax.

                                                      }
                                                });
                                    }
                              })
                        }
                  } else {
                        Toast = Swal.mixin({
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
                              title: 'Debe seleccionar una cama'
                        })
                  } 
            } else {
                  Toast = Swal.mixin({
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
                        title: 'Debe seleccionar una causa'
                  })
            }
            
      } else {
            Toast = Swal.mixin({
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
                  title: 'Debe seleccionar un paciente'
            })
      }


}
function porEspecialidad(especialidad_id) {
      var dir = $('#dir').val();
      $.ajax(
            {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/paciente/especialidad", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "especialidad_id=" + especialidad_id, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                        //$("#data_Table").html('<div class="d-flex justify-content-center  text-primary"><i class="fas fa-spinner fa-pulse fa-5x "></i></div>');
                  },
                  success: function (requestData) {//armar la tabla
                        
                        $("#exportButtons").html('');
                        $("#data_Table").html(requestData);
                        dataTables();
                        //toDataTable("#dataTablePacienteCamaEspecialidad");
                        $('[data-toggle="popover"]').popover({
                              trigger: 'hover',
                              placement: 'auto'
                        })
                  },
                  error: function (requestData, strError, strTipoError) {
                        //alert(requestData, strError, strTipoError)
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.

                  }
            });

}