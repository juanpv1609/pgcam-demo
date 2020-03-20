
function validar_entrada() {
    $("#buscaDescripcion").removeClass('border border-danger').addClass('border border-success');
    $("#nombreHelp").removeClass('badge badge-danger text-wrap').addClass('badge badge-success text-wrap').html('');
}

function buscaPorCodigo() {
    //var nombre = $("#ci").val();
    var dato = $("#buscaDescripcion").val();
    //dato=dato.toUpperCase();
    var dir = $('#dir').val();
    //console.log(nombre);
    if ((!dato == "")) {
        $.ajax(
            {
                dataType: "html",
                type: "POST",
                url: dir + "/cie10/busca", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                data: "dato=" + dato, //Se añade el parametro de busqueda del medico
                beforeSend: function (data) {
                    $("#data_Table").html("Buscando...");

                },
                success: function (requestData) {//armar la tabla

                    $("#data_Table").html(requestData);
                    toDataTable("#dataTableCie10");
                    //$("#buscaDescripcion").removeClass('border border-success').removeClass('border border-danger').val("");
                },
                error: function (requestData, strError, strTipoError) {
                },
                complete: function (requestData, exito) { //fin de la llamada ajax.

                }
            });
    } else {
        $("#buscaDescripcion").removeClass('border border-success').addClass('border border-danger');
        $("#nombreHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
            .html('<span>Este campo es necesario!</span>');
    }

}
function buscaPorCategoria() {
    //var nombre = $("#ci").val();
    var dato = $("#buscaDescripcion").val();
    //dato=dato.toUpperCase();
    var dir = $('#dir').val();
    //console.log(nombre);
    if ((!dato == "")) {
        $.ajax(
            {
                dataType: "html",
                type: "POST",
                url: dir + "/cie10/buscacategoria", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                data: "dato=" + dato, //Se añade el parametro de busqueda del medico
                beforeSend: function (data) {
                },
                success: function (requestData) {//armar la tabla

                    $("#data_Table").html(requestData);
                    toDataTable("#dataTableCie10");
                    //$("#buscaDescripcion").removeClass('border border-success').removeClass('border border-danger').val("");
                },
                error: function (requestData, strError, strTipoError) {
                },
                complete: function (requestData, exito) { //fin de la llamada ajax.

                }
            });
    } else {
        $("#buscaDescripcion").removeClass('border border-success').addClass('border border-danger');
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