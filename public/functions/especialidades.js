function validar() {
  $("#nombre_especialidad").removeClass('border border-danger').addClass('border border-success');
  $("#nombreHelp").removeClass('badge badge-danger text-wrap').addClass('badge badge-success text-wrap').html('');
}

function agregarModal() {
  $("#exampleModalLabel").text("Agregar - Especialidad");
  $("#nombre_especialidad").removeClass('border border-danger').removeClass('border border-success');
  $("#nombreHelp").removeClass('badge badge-danger text-wrap').removeClass('badge badge-success text-wrap').html('');
  $("#accionForm").html('<button class="btn btn-primary" type="button"  onclick="InsertarEspecialidad();">Agregar</button>');

  $('#formModal').modal({
    show: true
  });

}

function editarModal(id, piso_id, nombre) {
  $("#exampleModalLabel").text("Editar - Especialidad");
  $("#especialidad_id").val(id);
  $("#nombre_especialidad").removeClass('border border-danger').removeClass('border border-success').val(nombre);
  $("#comboPiso").val(piso_id);
  $("#nombreHelp").removeClass('badge badge-danger text-wrap').removeClass('badge badge-success text-wrap').html('');
  $("#accionForm").html('<button class="btn btn-primary" type="button"  onclick="ActualizarEspecialidad();">Actualizar</button>');
  $('#formModal').modal({
    show: true
  });
}
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
              timerProgressBar: true,
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
          },
          complete: function (requestData, exito) { //fin de la llamada ajax.

          }
        });
      //window.location.href = dir + "/areas/logout";
    }
  })
}
function InsertarEspecialidad() {
  //var nombre = $("#ci").val();
  var nombre = $("#nombre_especialidad").val();
  var piso = $("#comboPiso").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  if ((!nombre == "")) {
    $.ajax(
      {
        dataType: "html",
        type: "POST",
        url: dir + "/especialidades/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
        data: "nombre=" + nombre + "&piso=" + piso, //Se añade el parametro de busqueda del medico
        beforeSend: function (data) {
        },
        success: function (requestData) {//armar la tabla
          //alert("Area creada exitosamente!");
          //$("#mensaje").addClass('alert alert-success').html('Area creada correctamente!').show(100).delay(1500).hide(100);
          //$('#formModal').modal('hide');
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
            title: 'Dato creado correctamente!'
          });
          $('#formModal').modal('hide');
          $("#data_Table").html(requestData);
          toDataTable("#dataTableEspecialidad");
          $("#nombre_especialidad").removeClass('border border-success').removeClass('border border-danger').val("");
        },
        error: function (requestData, strError, strTipoError) {
        },
        complete: function (requestData, exito) { //fin de la llamada ajax.

        }
      });
  } else {
    $("#nombre_especialidad").removeClass('border border-success').addClass('border border-danger');
    $("#nombreHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
      .html('<span>Este campo es necesario!</span>');
  }

}
function ActualizarEspecialidad() {
  var id = $("#especialidad_id").val();
  var piso = $("#comboPiso").val();
  var nombre = $("#nombre_especialidad").val();
  var dir = $('#dir').val();
  //console.log(nombre);
  if ((!nombre == "")) {
    $.ajax(
      {
        dataType: "html",
        type: "POST",
        url: dir + "/especialidades/editar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
        data: "id=" + id + "&nombre=" + nombre + "&piso=" + piso, //Se añade el parametro de busqueda del medico
        beforeSend: function (data) {
        },
        success: function (requestData) {//armar la tabla
          //alert("Area creada exitosamente!");
          //$("#mensaje").addClass('alert alert-success').html('Area creada correctamente!').show(100).delay(1500).hide(100);
          //$('#formModal').modal('hide');
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
            title: 'Dato actualizado correctamente!'
          });
          $('#formModal').modal('hide');
          $("#data_Table").html(requestData);
          toDataTable("#dataTableEspecialidad");
          $("#nombre_especialidad").removeClass('border border-success').removeClass('border border-danger').val("");
        },
        error: function (requestData, strError, strTipoError) {
        },
        complete: function (requestData, exito) { //fin de la llamada ajax.

        }
      });
  } else {
    $("#nombre_especialidad").removeClass('border border-success').addClass('border border-danger');
    $("#nombreHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
      .html('<span>Este campo es necesario!</span>');
  }

}
function toDataTable(table) {
  const esp = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json";
  $(table).DataTable({
    "language": {
      "url": esp
    }
  });
}