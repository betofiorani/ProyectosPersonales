const listaProyectos = document.querySelector('#proyectos');
const listaTareas = document.querySelector('.listado-pendientes ul');
const btAcciones = document.querySelector('.listado-pendientes');

document.addEventListener('DOMContentLoaded',function(){   

    eventListeners();
    actualizarProgreso();

});

function eventListeners(){

    //registrar evento para boton de nuevo proyecto
    const btNuevoProyecto = document.querySelector('.crear-proyecto a');
    btNuevoProyecto.addEventListener("click",nuevoProyecto);

    // registrar evento para el click en el boton nueva tarea
    const btNuevaTarea = document.querySelector('.nueva-tarea');
    
    if(btNuevaTarea){ 
        btNuevaTarea.addEventListener('click',abmTarea);    
    }
    
    if(btAcciones){
        btAcciones.addEventListener('click',abmTarea);
    }
        
};

function abmTarea(e){
    e.preventDefault();
    
    let accion = e.target.getAttribute('data-accion');
    
    if(accion === 'crear'){

        let nombreTarea = document.querySelector('.nombre-tarea').value;
        let id = document.querySelector('#id_proyecto').value;
        let estado = 0;
        
        // validar si la tarea no está vacía
        if(nombreTarea === ''){
            swal({
                title: 'Error',
                text: 'No ingresaste ninguna tarea Nueva',
                type: 'error'
            })
            .then (result => {
                if(result.value){
                    document.querySelector('.nombre-tarea').focus();
                }
            });
        }
        else {
            
            guardarTarea(nombreTarea,id,accion,estado);   
        }
    } else {
        
        let idTarea = e.target.parentElement.parentElement.id.split("-")[1];
        let nombreTarea = e.target.parentElement.parentElement.childNodes[1].textContent;
        let estado;
        
        if(accion === 'finalizar'){
            if(e.target.classList.contains('completo')){
                e.target.classList.remove('completo');
                estado = 0;
            } else {
                e.target.classList.add('completo');
                estado = 1;
            }
            guardarTarea(nombreTarea,idTarea,accion,estado);
        } 
        if(accion === 'borrar'){

            Swal.fire({
                title: 'Estás Seguro?',
                text: "No podrás recuperar esta tarea!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI, quiero borrarla!',
                cancelButtonText: 'cancelar'
              }).then((result) => {
                if (result.value) {
                    // eliminar la tarea del dom
                    guardarTarea(nombreTarea,idTarea,accion,estado);
                    const tareaBorrar = document.querySelector(`#tarea-${idTarea}`);
                    tareaBorrar.parentElement.removeChild(tareaBorrar);
                    actualizarProgreso();

                    // revisamos si no quedan tareas e ingresamos la frase no hay tarea
                    let listaTareasRestantes = document.querySelectorAll('li.tarea');
                    
                    if(listaTareasRestantes.length === 0){
                        document.querySelector('.listado-pendientes ul').innerHTML = "<p class='lista-vacia'>No Hay tareas en este Proyecto</p>";
                    }

                    Swal.fire(
                    'Borrada!',
                    `${nombreTarea} eliminada exitosamente`,
                    'success'
                  )
                }
              });
        }   
    } 
}

function guardarTarea(nombreTarea,id,accion,estado){

    // ajax 
    let xhr = new XMLHttpRequest();

    // armamos un FormData para pasar los datos
    let datos = new FormData();
    datos.append('nombreTarea',nombreTarea);
    datos.append('id',id);
    datos.append('accion',accion);
    datos.append('estado',estado);

    // abrimos la conexion
    xhr.open('POST','inc/modelos/modelo-tareas.php',true);

    // on load
    xhr.onload = function(){
        
        if(this.status === 200){
            
            let respuesta = JSON.parse(xhr.responseText);

            if(respuesta.respuesta === 'correcto'){
                if(respuesta.tipo === 'crear'){

                    const listaVacia = document.querySelectorAll('.lista-vacia');
                    if(listaVacia.length >0){
                        
                        document.querySelector('.lista-vacia').remove();
                    }


                    const tarea = document.createElement('li');
                    tarea.id = `tarea-${respuesta.id_tarea}`;
                    tarea.classList.add('tarea');
                    tarea.innerHTML = `
                        <p>${nombreTarea}</p>
                            <div class="acciones">
                                <i class="far fa-check-circle" data-accion="finalizar"></i>
                                <i class="fas fa-trash" data-accion="borrar"></i>
                            </div>
                    `;
                    swal({
                        title: 'Tarea Agregada',
                        text: `${nombreTarea} guardada exitosamente`,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    listaTareas.appendChild(tarea);
                    document.querySelector('.agregar-tarea').reset();
                    document.querySelector('.listado-pendientes').addEventListener('click',abmTarea);
                    actualizarProgreso();
                    // borramos mensaje de lista vacia por las dudas

                } else if (respuesta.tipo === 'finalizar') {
                    swal({
                        title: 'Tarea Finalizada',
                        text: `${respuesta.nombre_tarea} terminada exitosamente`,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    // agregar clase para que se vea terminada
                    actualizarProgreso();

                } else {
                    
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
    };

    // mandamos los datos al servidor
    xhr.send(datos);
}

function nuevoProyecto(e){

    e.preventDefault();
    
    // revisa si está activo el campo de crear proyecto
    const inputExiste = document.querySelector('#nuevo-proyecto');

    if(inputExiste){
        inputExiste.focus();
    }

    else {
    
        // crea un input
        const inputProyecto = document.createElement('li');
        inputProyecto.innerHTML = '<input type="text" id="nuevo-proyecto">';
        listaProyectos.appendChild(inputProyecto);
        

        // capturar el evento enter para guardar el proyecto
        const inputCreado = document.querySelector('#nuevo-proyecto');
        inputCreado.focus();
        inputCreado.addEventListener('keypress',function(e){
            let tecla = e.which || e.keyCode;

            if(tecla === 13){

                guardarProyectoDB(inputCreado.value);
                listaProyectos.removeChild(inputProyecto); // para remover siempre desde el padre y le pasamos el hijo
            }
        });

    }

    
}

function guardarProyectoDB(nombreProyecto){

    // AJAX para guardar el proyecto
    let xhr = new XMLHttpRequest();

    // enviar datos por formdata
    let datos = new FormData();

    datos.append('proyecto', nombreProyecto);
    datos.append('accion', 'crear');

    // abrimos la conexion
    xhr.open('POST','inc/modelos/modelo-proyecto.php',true);

    // mientras carga

    xhr.onload = function (){

        if(this.status === 200){
            let respuesta = JSON.parse(xhr.responseText);

            if(respuesta.respuesta === 'correcto'){
                
                if(respuesta.tipo === 'crear'){

                    
                    // inyectar el html 
                    const nuevoProyecto = document.createElement('li');
                    nuevoProyecto.innerHTML = `<a href="index.php?id_proyecto=${respuesta.id}" id="proyecto-${respuesta.id}">${nombreProyecto}</a>`;
                    listaProyectos.appendChild(nuevoProyecto);
                    swal({
                        title: 'Proyecto Creado',
                        text: `${respuesta.nombre_proyecto} creado exitosamente`,
                        type: 'success'
                    })
                    .then (result => {
                        if(result.value){
                            window.location.href = "index.php?id_proyecto="+respuesta.id;
                        }
                    });
                } else {
                    swal({
                        title: 'Proyecto Editado',
                        text: respuesta.nombre_proyecto,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }
            }
            else {
                // hubo un error
                swal({
                    title: 'Error',
                    text: respuesta.texto,
                    type: 'error',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    };
    
    // Enviamos los datos
    xhr.send(datos);
}

// actualiza avance del proyecto
function actualizarProgreso(){
    // tomar todas las tareas
    const tareas = document.querySelectorAll('li.tarea').length;

    // obtener las tareas completadas
    const tareasCompletadas = document.querySelectorAll('i.completo').length;

    // porcentaje de avance
    let avance = Math.round((tareasCompletadas / tareas)*100);
    console.log(avance);
    // cambiar el width de la barra
    const porcentajeDiv = document.querySelector('#porcentaje');
    porcentajeDiv.style.width = avance+'%';

    // mostrar alerta
    if(avance === 100){
        swal({
            title: 'Proyecto Terminado',
            text: 'No tienes tareas Pendientes',
            type: 'success'
        });
    }
}