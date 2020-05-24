
/**
 * buscaPorCodigo()
 * * Realiza una busqueda de diagnosticos de acuerdo al codigo o descripcion
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param dato dato a buscar ya sea codigo o descripcion
 */
function buscaPorCodigo() {
    var dato = $("#buscaDescripcion").val();
    var dir = $('#dir').val();
    $("#formDescripcion").submit(function (event) {
        event.preventDefault(); //prevent default action
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
                    success: function (requestData) {
                        $("#data_Table").html(requestData);
                        dataTables();
                        toDataTable("#dataTableCie10");
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
 * buscaPorCategoria()
 * * Realiza una busqueda de diagnosticos por categoria de acuerdo al codigo o descripcion
 * ! envio mediante POST ajax
 * @param dir directorio del proyecto
 * @param dato dato a buscar ya sea codigo o descripcion
 */
function buscaPorCategoria() {
    var dato = $("#buscaDescripcion").val();
    var dir = $('#dir').val();
    $("#formCategoria").submit(function (event) {
        event.preventDefault(); //prevent default action
        if ((!dato == "")) {
            $.ajax(
                {
                    dataType: "html",
                    type: "POST",
                    url: dir + "/cie10/buscacategoria", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                    data: "dato=" + dato, //Se añade el parametro de busqueda del medico
                    beforeSend: function (data) {
                        $("#data_Table").html("Buscando...");
                    },
                    success: function (requestData) {//armar la tabla

                        $("#data_Table").html(requestData);
                        dataTables();
                        toDataTable("#dataTableCie10Cat");
                    },
                    error: function (requestData, strError, strTipoError) {
                    },
                    complete: function (requestData, exito) { //fin de la llamada ajax.

                    }
                });
        }
    });
     

}