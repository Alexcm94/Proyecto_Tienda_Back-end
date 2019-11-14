<?php

//Cabeceras
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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

if(isset($peticion->login)){
    $correo_electronico = $peticion->login->correo_electronico;
    $contrasena = $peticion->login->contrasena;
    if($usuario->buscarPorCorreo($correo_electronico)){
        
        if($usuario->contrasena == $contrasena){
            
            //Codigo de respuesta
            http_response_code(200);

            $usuario_arr = array();
            $usuario_arr["usuario"] = $usuario;
            echo json_encode($bd->utf8ize($usuario_arr));
        }else{
            http_response_code(403);

            //Mensaje error
            echo json_encode(array("mensaje" => "La contraseña no es correcta"));
        }
    }else{
        http_response_code(404);

        //Mensaje error
        echo json_encode(array("mensaje" => "El correo electrónico no está registrado"));
    }
}else{
    http_response_code(400);
    echo json_encode(array("mensaje" => "Los datos recibidos por el usuario no son correctos"));
}

?>