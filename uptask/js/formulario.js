document.addEventListener('DOMContentLoaded',function(){

    eventListeners();

});

function eventListeners(){

    document.querySelector('#formulario').addEventListener('submit',validarRegistro);

};

function validarRegistro(e){

    e.preventDefault();

    const usuario = document.querySelector('#usuario').value;
    const password = document.querySelector('#password').value;
    const tipo = document.querySelector('#tipo').value;

    if(usuario === '' || password === ''){
        Swal.fire({
            type: 'error',
            title: 'Error!!',
            text: 'Los 2 campos son obligatorios!',
        })
    } else {

        // datos  que se enviarar por AJAX al servidor
        let datos = new FormData();
        datos.append('usuario',usuario);
        datos.append('password',password);
        datos.append('accion',tipo);

        // iniciamos AJAX
        var xhr = new XMLHttpRequest();

        // Abrimos la conexion
        xhr.open('POST','inc/modelos/modelo-admin.php', true);

        // retorno de Datos
        xhr.onload = function(){

            if(this.status === 200){    
                let respuesta = JSON.parse(xhr.responseText);
                // si es correcto
                if(respuesta.respuesta === 'correcto'){
                    // nuevo usuario
                    if(respuesta.tipo === 'crear'){
                        swal({
                            title: 'Usuario Creado',
                            text: 'El Usuario se creó Correctamente',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal({
                            title: 'Ingreso Existoso',
                            text: 'Te logueaste Correctamente',
                            type: 'success'
                        })
                        .then (result => {
                            if(result.value){
                                window.location.href = 'index.php';
                            }
                        });
                    }
                } else {
                    swal({
                        title: 'Error',
                        text: respuesta.texto,
                        type: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        }

        // enviar la petición al servidor
        xhr.send(datos);
    }
}