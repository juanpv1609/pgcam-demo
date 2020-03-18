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
            if ($('#cedula').val().length==10) {         
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
                                    timer: 2000,
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