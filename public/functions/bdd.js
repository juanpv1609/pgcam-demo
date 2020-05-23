function upload(){
   var dir = $('#dir').val();
   //Obtengo el fichero que va a ser subido
   var dato_archivo = $('#fileUpload').prop("files")[0];
   console.log($('#fileUpload').val())
   console.log(dato_archivo)
   //Creo un dato de formulario para pasarlo en el ajax
   var form_data = new FormData();
   //Añado los datos del fichero que voy a subir
   //En el lado del servidor puede obtener el archivo a traves de $_FILES["file"]
   form_data.append("file", dato_archivo);
   console.log(form_data)

  /*  $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/database/restaurar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "file=" + dato_archivo, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            
         },
         success: function (requestData) {
            alert(requestData)
         },
         error: function (requestData, strError, strTipoError) {
            alert('error')
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.
         }
      }); */
}
