function validar(){
    $("#nombre_area").removeClass('border border-danger').addClass('border border-success');
    $("#nombreHelp").removeClass('badge badge-danger text-wrap').addClass('badge badge-success text-wrap').html('');
}

function agregarModal() {
    $("#exampleModalLabel").text("Agregar - Area");
    $("#btnModal").addClass('btn btn-primary').text("Agregar");
    $('#formModal').modal({
        show:true
    });
    
}
function editarModal() {
    $("#exampleModalLabel").text("Editar - Area");
    $("#btnModal").addClass('btn btn-warning').text("Actualizar");
    $('#formModal').modal({
        show:true
    });    
}
function InsertarArea() {
    //var nombre = $("#ci").val();
    var nombre = $("#nombre_area").val();
    var dir = $('#dir').val();
        //console.log(nombre);
        if ((!nombre=="")) {
                $.ajax(
                    {
                        dataType: "html",
                        type: "POST",
                        url: dir + "/areas/crear", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "nombre=" + nombre, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {
                        },
                        success: function (requestData) {//armar la tabla
                            //alert("Area creada exitosamente!");
                            $("#mensaje").addClass('alert alert-success').html('Area creada correctamente!').show(100).delay(1500).hide(100);
                            $("#dataTable").html(requestData);
                            $("#nombre_area").val("");
                        },
                        error: function (requestData, strError, strTipoError) {
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                    });
        }else{
            $("#nombre_area").addClass('border border-danger');
            $("#nombreHelp").addClass('badge badge-danger text-wrap').html('Este campo es necesario!');
        }

}