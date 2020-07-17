function setNowDate() {
   var now = new Date();
   var day = ("0" + now.getDate()).slice(-2);
   var month = ("0" + (now.getMonth() + 1)).slice(-2);
   var today = now.getFullYear() + "-" + (month) + "-" + (day);
   $("#fecha").val(today);
}
function getSala() {
   var comboEspecialidad = $("#comboEspecialidad").val();
   console.log(comboEspecialidad)
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/reportes/selectsala", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "especialidad=" + comboEspecialidad, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            //$("#comboSala").html('<option>Cargando...</option>');
         },
         success: function (requestData) {//armar la tabla
               $("#comboSala").html(requestData);
            
         },
         error: function (requestData, strError, strTipoError) {
            //
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });

}
function getDatos() {
   var especialidad = $("#comboEspecialidad").val();
   var sala = $("#comboSala").val();
   var fecha = $("#fecha").val();
   console.log(comboEspecialidad)
   var dir = $('#dir').val();
   if (!especialidad.length && !sala.length && !fecha.length) {
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
         title: 'Debe seleccionar un servicio, sala y una fecha!'
      });
   }else{
      if (!fecha.length) {
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
            title: 'Debe seleccionar una fecha!'
         });
      } else {
         $.ajax(
            {
               dataType: "html",
               type: "POST",
               url: dir + "/reportes/especialidad", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
               data: "especialidad=" + especialidad + "&sala=" + sala + "&fecha=" + fecha, //Se añade el parametro de busqueda del medico
               beforeSend: function (data) {
                  //$("#comboSala").html('<option>Cargando...</option>');
               },
               success: function (requestData) {//armar la tabla
                  $("#data_Table").html(requestData);
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
                     title: 'Información Encontrada!'
                  });
                  $("#btnSave").attr('disabled',false);


               },
               error: function (requestData, strError, strTipoError) {
                  //
               },
               complete: function (requestData, exito) { //fin de la llamada ajax.

               }
            });
      }
   }
   

}
function generatePDF() {
   var especialidad = $("#comboEspecialidad option:selected").text();
   var sala = $("#comboSala option:selected").text();
   var fecha = $("#fecha").val();

   kendo.drawing
      .drawDOM("#data_Table",
         {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: { top: "1cm", bottom: "1cm", left: "1cm", right:"1cm"},
            scale: 0.6,
            height: 500,
            keepTogether: ".prevent-split"
         })
      .then(function (group) {
         kendo.drawing.pdf.saveAs(group, especialidad + "_" + sala + "_" + fecha+".pdf")
      });
}