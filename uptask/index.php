<?php
    include('inc/funciones/sesiones.php');
    include('inc/funciones/conexion.php');
    include('inc/funciones/funciones.php');
    include('inc/templates/header.php');
    include('inc/templates/barra.php');

    // obtener el id de la url

    if(isset($_GET['id_proyecto'])){

        $id = $_GET['id_proyecto'];
        
    }
    else {
        $id = null;
    }
?>
<div class="contenedor">
    
<?php include('inc/templates/sidebar.php'); ?>

    <main class="contenido-principal">
        <h1>
            <?php 
                $proyectos = obtenerProyecto($id);
                if($proyectos){
                    foreach($proyectos as $proyecto){
                        $nombreProyecto = $proyecto['nombre'];
                    }
            ?>
                <span><?php echo $nombreProyecto;?></span>
            </h1>

            <form action="#" class="agregar-tarea">
                <div class="campo">
                    <label for="tarea">Tarea:</label>
                    <input type="text" placeholder="Nombre Tarea" class="nombre-tarea"> 
                </div>
                <div class="campo enviar">
                    <input type="hidden" id="id_proyecto" value="<?php echo $id;?>">
                    <input type="submit" class="boton nueva-tarea" data-accion="crear" value="Agregar">
                </div>
            </form>
            <h2>Listado de tareas:</h2>

            <div class="listado-pendientes">
                <ul>
                <?php 
                    $tareas = obtenerTareas($id);
                    if($tareas -> num_rows > 0){
                        foreach($tareas as $tarea):
                ?>
                    <li id="tarea-<?php echo $tarea['id'] ?>" class="tarea">
                        <p><?php echo $tarea['nombre'] ?></p>
                            <div class="acciones">
                                <i class="far fa-check-circle finalizar <?php echo $tarea['estado'] === '1' ? 'completo' : '';?>" data-accion='finalizar'></i>
                                <i class="fas fa-trash borrar" data-accion='borrar'></i>
                            </div>
                        </li>    
                <?php
                        endforeach;
                    } else {
                        echo "<p class='lista-vacia'>No hay tareas asociadas a este proyecto</p>";
                    }
                ?>  
                </ul>
            </div>
            <?php        
                } else {
                    echo "<p class='mensaje'> <- elige un proyecto en el listado izquierdo</p>";
                }
            ?>
            <div class="avance">
                <h2>Avance del Proyecto</h2>
                <div class="barra-avance" id="barra-avance">
                    <div id="porcentaje" class="porcentaje"></div>
                </div>
            </div>
    </main>
</div><!--.contenedor-->
<?php
    include('inc/templates/footer.php');
?>