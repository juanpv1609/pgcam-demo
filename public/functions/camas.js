/**
 * agregarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 */
function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Cama");
  $("#nombre_cama").val('');
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarCama();">Agregar</button>');
  $('#formModal').modal({
    show: true
  });

}
/**
 * editarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 * @param id se guarda el id de la cama a editar
 * @param habitacion_id se guarda el habitacion_id de la cama a editar
 * @param nombre se muestra el nombre de la cama a editar
 * @param cama_estado se muestra el cama_estado de la cama a editar
 */
function editarModal(id, habitacion_id, cama_estado, nombre) {
  $("#exampleModalLabel").text("Editar - Cama");
  $("#cama_id").val(id);
  $("#nombre_cama").val(nombre);
  $("#comboHabitacion").val(habitacion_id);
  $("#comboEstadoCama").val(cama_estado);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarCama();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * InsertarCama()
 * * Envia valores del formulario a la accion crear
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param nombre nombre de la cama a insertar
 * @param habitacion habitacion de la cama a insertar
 * @param cama_estado cama_estado de la cama a insertar
 */
function InsertarCama() {
  var nombre = $("#nombre_cama").val();
  var tipoCama = $("#comboTipoCama").val();
  var habitacion = $("#comboHabitacion").val();
  var cama_estado = $("#comboEstadoCama").val();
  var dir = $('#dir').val();
  $("#formCamas").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/camas/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "nombre=" + nombre + "&habitacion=" + habitacion + "&cama_estado=" + cama_estado + "&tipo_cama=" + tipoCama, //Se añade el parametro de busqueda del medico
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
            toDataTable("#dataTableCamas");
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
 * ActualizarCama()
 * * Envia valores del formulario a la accion editar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id de la cama a editar
 * @param habitacion habitacion de la cama a editar
 * @param nombre nombre de la cama a editar
 * @param cama_estado cama_estado de la cama a editar
 */
function ActualizarCama() {
  var id = $("#cama_id").val();
  var habitacion = $("#comboHabitacion").val();
  var tipoCama = $("#comboTipoCama").val();
  var cama_estado = $("#comboEstadoCama").val();
  var nombre = $("#nombre_cama").val();
  var dir = $('#dir').val();
  $("#formCamas").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/camas/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombre=" + nombre + "&habitacion=" + habitacion + "&cama_estado=" + cama_estado + "&tipo_cama=" + tipoCama, //Se añade el parametro de busqueda del medico
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
              title: 'Dato actualizado correctamente!'
            });
            $('#formModal').modal('hide');
            $("#data_Table").html(requestData);
            toDataTable("#dataTableCamas");
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
 * @param id id de la cama a eliminar
 * ? mensaje de confirmacion con sweet alert 2
 */
function eliminar(id) {
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
          url: dir + "/camas/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
            toDataTable("#dataTableCamas");
          },
          error: function (requestData, strError, strTipoError) {
            /**
             * TODO: controlar si la cama esta ocupada o en desinfeccion no se puede eliminar
             */
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
              title: 'Error: La cama se encuentra ocupada!'
            });
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
    }
  })
}