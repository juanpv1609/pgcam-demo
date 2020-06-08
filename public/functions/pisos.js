
/**
 * agregarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 */
function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Piso");
  $("#nombre_piso").val('');
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarPiso();">Agregar</button>');
  $('#formModal').modal({
    show: true
  });

}
/**
 * editarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 * @param id se guarda el id del piso a editar
 * @param area_id se guarda el area_id del piso a editar
 * @param nombre se muestra el nombre del piso a editar
 */
function editarModal(id, area_id, nombre) {
  $("#exampleModalLabel").text("Editar - Piso");
  $("#piso_id").val(id);
  $("#nombre_piso").val(nombre);
  $("#comboArea").val(area_id);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarPiso();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * InsertarPiso()
 * * Envia valores del formulario a la accion crear
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param nombre nombre del piso a insertar
 * @param area id del area a insertar
 */
function InsertarPiso() {
  var nombre = $("#nombre_piso").val();
  var area = $("#comboArea").val();
  var dir = $('#dir').val();
  $("#formPisos").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/pisos/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "nombre=" + nombre + "&area=" + area, //Se añade el parametro de busqueda del medico
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
            $('#formModal').modal('hide');
            $("#data_Table").html(requestData);
            toDataTable("#dataTablePisos");
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
 * ActualizarPiso()
 * * Envia valores del formulario a la accion editar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id del piso a editar
 * @param nombre nombre del piso a editar
 * @param area id del area a editar
 */
function ActualizarPiso() {
  var id = $("#piso_id").val();
  var area = $("#comboArea").val();
  var nombre = $("#nombre_piso").val();
  var dir = $('#dir').val();
  $("#formPisos").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/pisos/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombre=" + nombre + "&area=" + area, //Se añade el parametro de busqueda del medico
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
            toDataTable("#dataTablePisos");
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
 * @param id id del piso a eliminar
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
          url: dir + "/pisos/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
            toDataTable("#dataTablePisos");
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
              title: 'Error: Existen servicios en este piso!'
            });
            
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
      //window.location.href = dir + "/areas/logout";
    }
  })
}
