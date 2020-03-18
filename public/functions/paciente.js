/////////
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

function AdmisionPaciente() {
      var dir = $('#dir').val(); 
      var dataString = $('#paciente_admision').serialize(); //recorre todo el formulario
      $("#paciente_admision").submit(function(event){      
      event.preventDefault(); //prevent default action  
            if (($('#cedula').val().length==10)&&($('#telefono').val().length==10)
                  &&($('#contacto_telefono').val().length==10)&&($('#institucion_telefono').val().length==10)) {         
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
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                    });                              
                                    Toast.fire({
                                    icon: 'success',
                                    title: 'Paciente registrado correctamente!'
                                    }).then((result)=>{
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
      $("#paciente_busca").submit(function(event){      
      event.preventDefault(); //prevent default action  
      if ((!paciente=="")) {         
            $.ajax({
                  dataType: "json",
                  type: "POST",
                  url: dir + "/paciente/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "paciente="+paciente, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                  //console.log(data)
                  //$('#contenido').html("Cargando contenido...");

                  },
                  success: function (requestData) {//armar la tabla                        
                  if (requestData.data) {

                        var array_apellidos = requestData.data.p_apellidos.split(" "); 
                        var array_nombres = requestData.data.p_nombres.split(" "); 
                        $('#apellido_paterno').val(array_apellidos[0]);
                        $('#apellido_materno').val(array_apellidos[1]);
                        $('#primer_nombre').val(array_nombres[0]);
                        $('#segundo_nombre').val(array_nombres[1]);
                        $('#cedula').val(requestData.data.p_ci);
                        $('#telefono').val(requestData.data.p_telefono);                            

                        $('#comboProv').val(requestData.data.id_provincia);
                        // getCantones();
                        //$('#comboCant').val(requestData.data.id_canton);
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
                              timerProgressBar: true,
                              onOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                              }
                              });                              
                              Toast.fire({
                              icon: 'success',
                              title: 'Paciente encontrado!'
                              })
                  }else{
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
                              title: 'No se encontraron resultados!'
                              })
                  }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                        var msg='';
                        if (jqXHR.status === 0) {
                              msg='No conectado: Verifique la red.';                                
                              } else if (jqXHR.status == 404) {                                
                              msg='Página solicitada no encontrada [404]';                                
                              } else if (jqXHR.status == 500) {                                
                              msg='Solo datos numericos.';                                
                              } else {                                
                              msg='No se encontraron resultados [500]';                                
                              }
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
                              title: msg
                              }) 
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.
                  
                  }
            });
      }
               
       }); 
   
}