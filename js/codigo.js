$('#formLogin').submit(function(e){
    e.preventDefault();
    var User = $.trim($("#usuario").val());
    var Pwd = $.trim($("#password").val());
    if (User == ""){
        swal.fire({
            type: 'warning',
            title: 'Debe Ingresar un Usuario',
        });
        return false;
    } else if (Pwd == ""){
        swal.fire({
            type: 'warning',
            title: 'Debe Ingresar una Contraseña',
        });
        return false;
    }else{
        $.ajax({
            url: "../../swell/php/servidor.php",
            type: "POST",
            datatype: "json",
            data: {rq: "0", Usuario: User, Password: Pwd},
            success: function(data) {
                if(data == -2){
                    swal.fire({
                        type: 'error',
                        title: 'Usuario Incorrecto',
                    });
                } else if(data == -3){
                    swal.fire({
                        type: 'error',
                        title: 'Clave Incorrecto',
                    });
                } else if (data.length > 2 ) {
                /*    var jDatos = JSON.parse(data);
                    swal.fire({
                        type: 'success',
                        title: 'Conexion Exitosa ',
                        confirmButtonColor: '#3885d6',
                        confirmButtonText: 'Ingresar'
                    }).then((result)=>{
                        if(result.value){ */
                              window.location.href =  data;
                            
                       // }
                   // })
                }
            }
        });

    } 
});