<?php 

    $accion = $_POST['accion'];
    $proyecto = $_POST['proyecto'];

    if($accion === 'crear'){
        // código para guardar los proyectos.

        // abrir la conexion
        include '../funciones/conexion.php';
        
        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("INSERT INTO proyectos (nombre) VALUES (?)");
            // Pasamos los parametros
            $stmt ->bind_param("s",$proyecto);
            // Ejecutamos el statement
            $stmt -> execute();
            
            if($stmt ->affected_rows >0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id' => $stmt ->insert_id,
                    'tipo' => $accion,
                    'nombre_proyecto' => $proyecto
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'No se puedo guardar el proyecto'
                );
            }

        } catch (Exception $e) {
            //en caso de error. tomar la excepcion
            $respuesta = array(
            'pass' => $e->getMessage()
            );
        }

        echo json_encode($respuesta);
    }
