<?php 

    $accion = $_POST['accion'];
    
    if($accion === 'crear'){

        $nombreTarea = $_POST['nombreTarea'];
        $idProyecto = $_POST['id'];
        
        // código para guardar los proyectos.
        $estado = 0;
        // abrir la conexion
        include '../funciones/conexion.php';
        
        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("INSERT INTO tareas (nombre,estado,id_proyecto) VALUES (?,?,?)");
            // Pasamos los parametros
            $stmt ->bind_param("sii",$nombreTarea,$estado,$idProyecto);
            // Ejecutamos el statement
            $stmt -> execute();
            
            if($stmt ->affected_rows >0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_tarea' => $stmt ->insert_id,
                    'tipo' => $accion,
                    'nombre_tarea' => $nombreTarea,
                    'estado' => $estado
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'No se pudo guardar la tarea'
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

    if($accion === 'borrar'){
        // código para guardar los proyectos.
        $idTarea = $_POST['id'];
        $nombreTarea = $_POST['nombreTarea'];
        
        // abrir la conexion
        include '../funciones/conexion.php';
        
        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("DELETE FROM tareas WHERE id = ?");
            // Pasamos los parametros
            $stmt ->bind_param("i",$idTarea);
            // Ejecutamos el statement
            $stmt -> execute();
            
            if($stmt ->affected_rows >0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'tipo' => $accion,
                    'nombre_tarea' => $nombreTarea,
                    'id_tarea' => $idTarea
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'No se pudo borrar la tarea'
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

    if($accion === 'finalizar'){
        // código para guardar los proyectos.
        $idTarea = $_POST['id'];
        $estado = $_POST['estado'];
        $nombreTarea = $_POST['nombreTarea'];
        
        // abrir la conexion
        include '../funciones/conexion.php';
        
        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
            // Pasamos los parametros
            $stmt ->bind_param("ii",$estado,$idTarea);
            // Ejecutamos el statement
            $stmt -> execute();
            
            if($stmt ->affected_rows >0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'tipo' => $accion,
                    'nombre_tarea' => $nombreTarea
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'No se pudo borputorar la tarea'
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
