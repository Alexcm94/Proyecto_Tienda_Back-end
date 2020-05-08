<?php
    //Cabeceras

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/plain; charset=UTF-8");
    //Añadimos las cabeceras necesarias para las peticiones POST
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    //Incluimos los ficheros que vamos a necesitar
    include_once '../config/basededatos.php';
    include_once '../modelos/usuarios.php';

    //Conectamos con las Base de Datos
    $bd = new BaseDatos();
    $conexion = $bd->getConexion();
    $usuario = new Usuario($conexion);

    //Comprobamos que los datos han llegado
    $datos_post = file_get_contents("php://input");
    $peticion = json_decode($datos_post);

    if(isset($peticion->tipo_tarjeta) && isset($peticion->numero_tarjeta) && isset($peticion->fecha_tarjeta) && isset($peticion->cvv) && isset($peticion->id_usuario) ) {
        if($usuario->cambiarTarjeta($peticion->tipo_tarjeta, $peticion->numero_tarjeta, $peticion->fecha_tarjeta, $peticion->cvv, $peticion->id_usuario)){
            if($usuario->buscarPorId($peticion->id_usuario) != 0){
                http_response_code(200);
                // Codificamos los datos en JSON
                $usuario_arr = array ();
                $usuario_arr["usuario"] = $usuario;
                echo json_encode($bd->utf8ize($usuario_arr));
            }
            else{
                http_response_code(404);
                echo json_encode(array("mensaje" => "El id indicado no pertenece a ningun usuario"));
            }
        }else{
            //Mandamos respuesta de error
            http_response_code(503);
            echo json_encode(array("mensaje" => "Error interno del servidor"));
        }
    }
    else {
     // Respondemos con codigo 400
     http_response_code(400);
     echo json_encode(array("mensaje" => "Los datos recibidos por el usuario no son correctos"));
    }

    $conexion->close();
    
?>