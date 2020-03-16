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
      var apellido_paterno = $('#apellido_paterno').val(); 
      var primer_nombre = $('#primer_nombre').val(); 
      var cedula = $('#cedula').val(); 
      var telefono = $('#telefono').val(); 
          $("#paciente_admision").submit(function(event){
      //var dataString = $('#paciente_admision').serialize(); //recorre todo el formulario

           event.preventDefault(); //prevent default action           
            $.ajax(
            {
                  dataType: "html",
                  type: "POST",
                  url: dir + "/paciente/admision", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                  data: "apellido_paterno="+apellido_paterno+"&cedula="+cedula+"&primer_nombre="+primer_nombre+"&telefono="+telefono, //Se añade el parametro de busqueda del medico
                  beforeSend: function (data) {
                  //console.log(data)
                  },
                  success: function (requestData) {//armar la tabla
                  
                        /* const Toast = Swal.mixin({
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
                        }); */ 
                  },
                  error: function (requestData, strError, strTipoErro) {
                        alert(xhr.statusText+" "+xhr.status);
                        //alert(xhr.responseText);
                  },
                  complete: function (requestData, exito) { //fin de la llamada ajax.
                  
                  }
            });
               
       }); 
   
   }