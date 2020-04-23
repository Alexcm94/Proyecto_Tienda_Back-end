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

    if(isset($peticion->usuario)){
        if(!$usuario->buscarPorCorreo($peticion->usuario->correo_electronico)){
            $peticion->usuario->admin=0;
            $peticion->usuario->id=-1;
            $peticion->usuario->numero_tarjeta="";
            $peticion->usuario->fecha_tarjeta="";
            $peticion->usuario->tipo_tarjeta="";
            $peticion->usuario->cvv="";
            $usuario->copiar($peticion->usuario);
            if($usuario->insertar()){
                //Mandamos respuesta de éxito
                $usuario->id = $conexion->insert_id;
                //Transformamos en un array
                $usuario_arr = array();
                $usuario_arr["usuario"] = array($usuario);
                //Codigo de respuesta
                http_response_code(200);
                echo json_encode($bd->utf8ize($usuario_arr));
            }else{
                //Mandamos respuesta de error
                http_response_code(503);
                echo json_encode(array("mensaje" => "El usuario no se pudo registar, error interno"));
            }
        }
        else{
            http_response_code(409);

            //Mensaje error
            echo json_encode(array("mensaje" => "El correo electrónico ya está registrado"));
        }
        
    }else{
        http_response_code(400);
        echo json_encode(array("mensaje" => "Los datos recibidos por el usuario no son correctos"));
    }

?>