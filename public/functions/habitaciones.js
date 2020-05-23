
/**
 * agregarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 */
function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Habitacion");
  $("#nombre_habitacion").val('');
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarHabitacion();">Agregar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * editarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 * @param id se guarda el id de la habitacion a editar
 * @param especialidad_id se guarda el especialidad_id de la habitacion a editar
 * @param nombre se muestra el nombre de la habitacion a editar
 */
function editarModal(id, especialidad_id, nombre) {
  $("#exampleModalLabel").text("Editar - Habitacion");
  $("#habitacion_id").val(id);
  $("#nombre_habitacion").val(nombre);
  $("#comboEspecialidad").val(especialidad_id);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarHabitacion();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * InsertarHabitacion()
 * * Envia valores del formulario a la accion crear
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param nombre nombre de la habitacion a insertar
 * @param especialidad especialidad_id de la habitacion a insertar
 */
function InsertarHabitacion() {
  var nombre = $("#nombre_habitacion").val();
  var especialidad = $("#comboEspecialidad").val();
  var dir = $('#dir').val();
  $("#formHabitaciones").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/habitaciones/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "nombre=" + nombre + "&especialidad=" + especialidad, //Se añade el parametro de busqueda del medico
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
              title: 'Dato creado correctamente!'
            });
            $("#data_Table").html(requestData);
            toDataTable("#dataTableHabitaciones");
            $("#nombre_habitacion").val("");
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
 * ActualizarHabitacion()
 * * Envia valores del formulario a la accion editar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id de la habitacion a editar
 * @param especialidad especialidad_id de la habitacion a editar
 * @param nombre nombre de la habitacion a editar
 */
function ActualizarHabitacion() {
  var id = $("#habitacion_id").val();
  var especialidad = $("#comboEspecialidad").val();
  var nombre = $("#nombre_habitacion").val();
  var dir = $('#dir').val();
  $("#formHabitaciones").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/habitaciones/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombre=" + nombre + "&especialidad=" + especialidad, //Se añade el parametro de busqueda del medico
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
            toDataTable("#dataTableHabitaciones");
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
 * @param id id de la habitacion a eliminar
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
          url: dir + "/habitaciones/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
            toDataTable("#dataTableHabitaciones");
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
              title: 'Error: Existen camas en esta habitacion!'
            });
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
    }
  })
}