
/**
 * agregarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 */
function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Especialidad");
  $("#nombre_especialidad").val('');
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="InsertarEspecialidad();">Agregar</button>');
  $('#formModal').modal({
    show: true
  });

}
/**
 * editarModal
 * * Setea valores mediante JQUERY al modal como el titulo y el boton
 * ? ya que se usa el mismo modal para crear y editar
 * @param id se guarda el id de la especialidad a editar
 * @param piso_id se guarda el piso_id de la especialidad a editar
 * @param nombre se muestra el nombre de la especialidad a editar
 */
function editarModal(id, piso_id, nombre,color,alias) {
  $("#exampleModalLabel").text("Editar - Especialidad");
  $("#especialidad_id").val(id);
  $("#nombre_especialidad").val(nombre);
  $("#alias_especialidad").val(alias);
  $("#comboPiso").val(piso_id);
  $("#valorBoton").val(color);
  $("#accionForm").html('<button class="btn btn-primary" type="submit"  onclick="ActualizarEspecialidad();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
/**
 * InsertarEspecialidad()
 * * Envia valores del formulario a la accion crear
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param nombre nombre de la especialidad a insertar
 * @param piso piso_id de la especialidad a insertar
 */
function InsertarEspecialidad() {
  var nombre = $("#nombre_especialidad").val();
  var alias = $("#alias_especialidad").val();
  var piso = $("#comboPiso").val();
  var color = $("#valorBoton").val();
  var dir = $('#dir').val();
  $("#formEspecialidades").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      if ((!color == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/especialidades/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "nombre=" + nombre + "&alias="+alias+"&piso=" + piso + "&color=" + color, //Se añade el parametro de busqueda del medico
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
            toDataTable("#dataTableEspecialidad");
            $("#nombre_especialidad").val("");
            nombre = '';      
          },
          error: function (requestData, strError, strTipoError) {
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.
          }
        });
      } else {
        $("#colorHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
          .html('<span>Debe elegir un color!</span>');
      }
    } 
  });
  

}
/**
 * ActualizarEspecialidad()
 * * Envia valores del formulario a la accion editar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id de la especialidad a editar
 * @param piso piso_id de la especialidad a editar
 * @param nombre nombre de la especialidad a editar
 */
function ActualizarEspecialidad() {
  var id = $("#especialidad_id").val();
  var piso = $("#comboPiso").val();
  var nombre = $("#nombre_especialidad").val();
  var alias = $("#alias_especialidad").val();

  var color = $("#valorBoton").val();
  var dir = $('#dir').val();
  $("#formEspecialidades").submit(function (event) {
    event.preventDefault(); //prevent default action
    if ((!nombre == "")) {
      if ((!color == "")) {
      $.ajax(
        {
          dataType: "html",
          type: "POST",
          url: dir + "/especialidades/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
          data: "id=" + id + "&nombre=" + nombre + "&alias="+alias+"&piso=" + piso + "&color=" + color, //Se añade el parametro de busqueda del medico
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
            toDataTable("#dataTableEspecialidad");
            nombre = '';
          },
          error: function (requestData, strError, strTipoError) {
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
      } else {
          $("#colorHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
            .html('<span>Debe elegir un color!</span>');
        }
    } 
  });
  

}
/**
 * eliminar()
 * * Envia valores del formulario a la accion eliminar
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param id id de la especialidad a eliminar
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
          url: dir + "/especialidades/eliminar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
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
            toDataTable("#dataTableEspecialidad");
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
              title: 'Error: Existen habitaciones en este servicio!'
            });
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
    }
  })
}
function colorEspecialidad(comp) {
  let id = comp.id;
  $("#valorBoton").val(id);
}