<?php 

    $accion = $_POST['accion'];
    $password = $_POST['password'];
    $usuario = $_POST['usuario'];

    if($accion === 'crear'){
        // código para crear los usuarios.

        // hashear password

        $opciones = array(
            'cost' => 12
        );

        $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        // abrir la conexion
        include '../funciones/conexion.php';
        
        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("INSERT INTO usuarios (usuario,password) VALUES (?,?)");
            // Pasamos los parametros
            $stmt ->bind_param("ss",$usuario,$hash_password);
            // Ejecutamos el statement
            $stmt -> execute();
            
            if($stmt ->affected_rows >0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_insertado' => $stmt ->insert_id,
                    'tipo' => $accion
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'No se puedo crear el Usuario'
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

    if($accion === 'login'){

        // abrir la conexion
        include '../funciones/conexion.php';

        try {
            //realizar consulta...
            // Preparamos la consulta.
            $stmt = $conn ->prepare("SELECT usuario, id, password FROM usuarios WHERE usuario = ?");
            // Pasamos los parametros
            $stmt ->bind_param("s",$usuario);
            // Ejecutamos el statement
            $stmt -> execute();

            // loguear el usuario
            $stmt -> bind_result($nombre_usuario, $id_usuario, $pass_usuario);
            $stmt -> fetch();

            if($nombre_usuario){
                // verificar el password
                if(password_verify($password,$pass_usuario)){
                    // iniciar la session
                    session_start();

                    $_SESSION['nombre'] = $nombre_usuario;
                    $_SESSION['id'] = $id_usuario;
                    $_SESSION['login'] = true;
                    // login correcto
                    $respuesta = array(
                        'respuesta' => 'correcto',
                        'usuario' => $usuario,
                        'tipo' => $accion
                    );

                    //login incorrecto
                } else {
                    $respuesta = array(
                        'respuesta' => 'error',
                        'texto' => 'Password Incorrecto'
                    );
                }
                
            } else {

                $respuesta = array(
                    'respuesta' => 'error',
                    'texto' => 'Usuario Inexistente'
                );
            }

            $stmt -> close();
            $conn -> close();

        } catch (Exception $e) {
            //en caso de error. tomar la excepcion
            $respuesta = array(
            'pass' => $e->getMessage()
            );
        }

        echo json_encode($respuesta);
    }

?>