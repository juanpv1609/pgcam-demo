/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function mostrarPassword(){
    var cambio = document.getElementById("password");
    if(cambio.type == "password"){
        cambio.type = "text";
        $('.icon').removeClass('far fa-eye-slash').addClass('far fa-eye');
    }else{
        cambio.type = "password";
        $('.icon').removeClass('far fa-eye').addClass('far fa-eye-slash');
    }
} 

$(document).ready(function () {
//CheckBox mostrar contraseña
$('#ShowPassword').click(function () {
    $('#Password').attr('type', $(this).is(':checked') ? 'text' : 'password');
});
});
function isValidEmail(mail) {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}


function SendFormLogin(e) {

    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 13) {
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
    var comboPerfil = $("#comboPerfil").val();
    var dir = $('#dir').val();
    $("#register").submit(function (event) {
        event.preventDefault(); //prevent default action
        //console.log(nombre);
        if ((!nombre == "") && (!apellido == "") && (!email == "") && (!clave == "") && (!clave2 == "") && (!comboPerfil == "")) {
            if (clave == clave2) {
                $.ajax(
                    {
                        dataType: "json",
                        type: "POST",
                        url: dir + "/auth/agregar", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                        data: "nombre=" + nombre + "&apellido=" + apellido + "&email=" + email
                            + "&clave=" + clave + "&comboPerfil=" + comboPerfil, //Se añade el parametro de busqueda del medico
                        beforeSend: function (data) {

                        },
                        success: function (requestData) {//armar la tabla
                            if (!requestData.data.correo == '') {
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
                                    title: 'El correo ingresado ya existe!'
                                  });
                            } else {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Correcto!',
                                    text: 'Cuenta creada exitosamente.',
                                    width: '22rem',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    window.location.href = dir + "/auth/login";

                                })

                            }
                            //console.log(requestData.data);

                        },
                        error: function (requestData, strError, strTipoError) {
                            //alert(strError+"\n"+strTipoError);
                            //  $("#clave_igual").addClass('alert alert-danger').html('El correo ingresado ya existe').show(100).delay(2500).hide(100);

                        },
                        complete: function (requestData, exito) { //fin de la llamada ajax.
                            // console.log(exito);

                        }
                    });
            } else {
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
                    title: 'Deben coincidir las contraseñas!'
                  });
                }
        }
    });
}

function logout() {
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
            }).then((result) => {
                window.location.href = dir + "/auth/logout";
            })

        }

    })

}
function validarCorreo() {
    $("#recuperar").removeClass('border border-danger').addClass('border border-success');
    $("#correoHelp").removeClass('badge badge-danger text-wrap').addClass('badge badge-success text-wrap').html('');
}
function recuperarclave() {
    // const { value: email } = await 
    var recuperar = $("#recuperar").val();
    var dir = $('#dir').val();
    //console.log(nombre);
    if ((!recuperar == "") && (isValidEmail(recuperar))) {
        $.ajax(
            {
                dataType: "json",
                type: "POST",
                url: dir + "/auth/recupera", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
                data: "recuperar=" + recuperar, //Se añade el parametro de busqueda del medico
                beforeSend: function (data) {
                    $("#mensaje").addClass('alert alert-info')
                        .html('<i class="fas fa-spinner fa-pulse"></i><span> Espere por favor, enviando correo electronico...</span>');
                },
                success: function (requestData) {//armar la tabla
                    if (!requestData.data.correo == "") {
                        Swal.fire({
                            position: 'top',
                            title: 'Correcto!',
                            text: 'Correo electronico enviado.',
                            width: '22rem',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            window.location.href = dir + "/auth/login";

                        })
                    } else {
                        $("#mensaje").addClass('alert alert-info')
                            .html('<i class="fas fa-exclamation-circle"></i><span> El correo ingresado no existe en el sistema!</span>');

                    }

                },
                error: function (requestData, strError, strTipoError) {
                    //console.log(strError+"\n"+strTipoError);

                },
                complete: function (requestData, exito) { //fin de la llamada ajax.
                    // console.log(exito);

                }
            });
    } else {
        $("#recuperar").removeClass('border border-success').addClass('border border-danger');
        $("#correoHelp").removeClass('badge badge-success text-wrap').addClass('badge badge-danger text-wrap')
            .html('<span>Ingrese un correo electronico valido!</span>');
    }

}

