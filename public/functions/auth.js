/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function isValidEmail(mail) {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}
$(document).ready(function() {
});
function login() {

}
function SendFormLogin(e)
{

    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 13){
        e.preventDefault(); //prevent default action
        
        document.login.submit();
    }

}

function SendFormRegister() {
    //var nombre = $("#ci").val();
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var email = $("#email").val();
    var clave = $("#clave1").val();
    var clave2 = $("#clave2").val();
    var dir = $('#dir').val();
    $("#register").submit(function(event){
        event.preventDefault(); //prevent default action
        //console.log(nombre);
        if ((!nombre=="") && (!apellido=="") && (!email=="") && (!clave=="") && (!clave2=="")) {
            if (clave == clave2) {
                $.ajax(
                    {
                        dataType: "json",
                        type: "POST",
                        url: dir + "/auth/agregar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "nombre=" + nombre + "&apellido=" + apellido + "&email=" + email + "&clave=" + clave, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {

                        },
                        success: function (requestData) {//armar la tabla

                            alert("Cuenta creada exitosamente!");
                            window.location.href = dir + "/auth/login";
                            //console.log(requestData.data);

                        },
                        error: function (requestData, strError, strTipoError) {
                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.

                        }
                    });
            } else {
                $("#clave_igual").addClass('alert alert-warning').html('Deben coincidir las contraseñas').show(100).delay(2500).hide(100);
            }
        }
    });
}

function logout(){
    var dir = $('#dir').val();
    Swal.fire({
        position: 'top',
        title: 'Está seguro?',
        text: "¡Seleccione 'Aceptar' a continuación si está listo para finalizar su sesión actual!",
        icon: 'question',
        width: '22rem',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            position: 'top',
            title: 'Correcto!',
            text: 'Ah finalizado su sesion.',
            width: '22rem',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then((result)=>{
            window.location.href = dir + "/auth/logout";
        })
        
        }
        
      })
      
}
function recuperarClave() {
    const { value: email } = await Swal.fire({
        title: 'Ingrese su direccion de correo',
        input: 'email',
        inputPlaceholder: 'ejemplo@ejemplo.com'
      })
      
      if (email) {
        Swal.fire(`Correo: ${email}`)
      }
}

