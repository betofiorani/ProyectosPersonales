<?php
    // obtener página actual
    function obtenerPaginaActual(){
        $archivo = basename($_SERVER['PHP_SELF']);
        $pagina = str_replace(".php", "", $archivo);
        return $pagina;
    }

    // realizar consultas
    function obtenerProyectos(){
        
        include('conexion.php');

        try {
            //realizar consulta...
            // Preparamos la consulta.
            return $conn ->query("SELECT id, nombre FROM proyectos");

        } catch (Exception $e) {
            //en caso de error. tomar la excepcion
            echo "error! : " . $e->getMessage();
            return false;
        }
    }
    
    function obtenerProyecto($id = null){
        
        include('conexion.php');

        try {
            //realizar consulta...
            // Preparamos la consulta.
            return $conn ->query("SELECT id, nombre FROM proyectos WHERE id=$id");

        } catch (Exception $e) {
            //en caso de error. tomar la excepcion
            echo "error! : " . $e->getMessage();
            return false;
        }

    }

    function obtenerTareas($id_proyecto){
        
        include('conexion.php');

        try {
            //realizar consulta...
            // Preparamos la consulta.
            return $conn ->query("SELECT id, nombre, estado, id_proyecto FROM tareas WHERE id_proyecto=$id_proyecto");
            
        } catch (Exception $e) {
            //en caso de error. tomar la excepcion
            echo "error! : " . $e->getMessage();
            return false;
        }
    }
?>