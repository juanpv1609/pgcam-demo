/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function isValidEmail(mail) { 
  return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
}
    $(document).ready(function() {
});
function agregarUsuario() {
    //var nombre = $("#ci").val();
    var checkbox = document.getElementById("acuerdo");
    var usuario = $("#new_user").val();
    var correo = $("#new_email").val();
    var clave = $("#new_clave").val();
    var clave2 = $("#new_clave2").val();
    //var nombre2 = nombre.toUpperCase();
    var dir = $('#dir').val();
    if ((!usuario == "") && (!correo == "") && (!clave == "") && (!clave2 == "")) {//datos paciente
        if (isValidEmail(correo)){
        if ((clave == clave2) ) {//cobertura
            if ((checkbox.checked == true)){
                    $.ajax(
                            {
                                dataType: "json",
                                type: "POST",
                                url: dir + "/login/agregar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                                data: "usuario=" + usuario + "&correo=" + correo + "&clave=" + clave, //Se añade el parametro de busqueda del medico
                                beforeSend: function(data) {

                                },
                                success: function(requestData) {//armar la tabla
                                    
                                    alert("Cuenta creada exitosamente!");
                                    window.location.href = dir+"/login";
                                    
                                },
                                error: function(requestData, strError, strTipoError) {
                                },
                                complete: function(requestData, exito) { //fin de la llamada ajax.
                                   
                                }
                            });
            } else {
            $("#acuerdo_").addClass('alert alert-warning').html('Debe marcar el acuerdo de servicio').show(100).delay(2500).hide(100);
        }    
        } else {
            $("#clave_igual").addClass('alert alert-info').html('Deben coincidir las contraseñas').show(100).delay(2500).hide(100);
        }
        } else {
        $("#correo_valido").addClass('alert alert-warning').html('Ingrese un correo electronico').show(100).delay(2500).hide(100);
    }
    } else {
        $("#vacio").addClass('alert alert-danger').html('Es necesario llenar todos los campos').show(100).delay(2500).hide(100);
    }

}
