/**
 * agregarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 */

function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Area");
  $("#nombre_area").val('');
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarArea();">Agregar</button>');
  $('#formModal').modal({
    show: true
  });

}
/**
 * editarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 * @param id se guarda el id del area a editar
 * @param nombre se muestra el nombre del area a editar
 */
function editarModal(id, nombre) {
  $("#exampleModalLabel").text("Editar - Area");
  $("#area_id").val(id);
  $("#nombre_area").val(nombre);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarArea();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * InsertarArea() 
 * * Envia valores del formulario a la accion crear
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param nombre nombre del area a insertar
 */
function InsertarArea() {
  var nombre = $("#nombre_area").val();
  var dir = $('#dir').val();
  $("#formArea").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/areas/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "nombre=" + nombre, //Se añade el parametro de busqueda del medico
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
                title: 'Dato creado correctamente!'
              });
            $('#formModal').modal('hide');
              $("#data_Table").html(requestData);
              toDataTable("#dataTableAreas");
              nombre = '';      
          },
          error: function (requestData, strError, strTipoError) {
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.
          }
        });
    }
  });
}
/**
 * ActualizarArea()
 * * Envia valores del formulario a la accion editar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id del area a insertar
 * @param nombre nombre del area a insertar
 */
function ActualizarArea() {
  var id = $("#area_id").val();
  var nombre = $("#nombre_area").val();
  var dir = $('#dir').val();
  $("#formArea").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/areas/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombre=" + nombre, //Se añade el parametro de busqueda del medico
          beforeSend: function (data) {
            $("#data_Table").html("Procesando...");
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
              title: 'Dato actualizado correctamente!'
            });
            $('#formModal').modal('hide');
            $("#data_Table").html(requestData);
            toDataTable("#dataTableAreas");
            nombre = '';      
          },
          error: function (requestData, strError, strTipoError) {
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.
          }
        });
    }
  });
}
/**
 * eliminar()
 * * Envia valores del formulario a la accion eliminar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id del area a eliminar
 * ? mensaje de confirmacion con sweet alert 2
 */
function eliminar(id) {
  var dir;
  dir = $('#dir').val();
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
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.value) {
      $.ajax(
        {
          dataType: 'html',
          type: "POST",
          url: dir + "/areas/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
            toDataTable("#dataTableAreas");
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
              title: 'Error: Existen pisos en esta area!'
            });
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
    }
  })
}