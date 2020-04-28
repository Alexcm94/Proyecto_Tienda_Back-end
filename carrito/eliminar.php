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
include_once '../modelos/carrito.php';

//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$carrito = new Carrito($conexion);

//Comprobamos que los datos han llegado
$datos_post = file_get_contents("php://input");
$peticion = json_decode($datos_post);

if(isset($peticion->id_producto) && (isset($peticion->id_usuario)))  {
    if($carrito->eliminar($peticion->id_usuario, $peticion->id_producto)){
        // Codigo de respuesta
       http_response_code(200);
       $carrito->getCarrito($peticion->id_usuario);
       $respuesta = $carrito->productos;
       echo json_encode($respuesta);
    }else{
        // Codigo de respuesta
       http_response_code(503);
       //Mensaje de error
       echo json_encode(
            array("mensaje" => "Error interno del servidor")
        );
    }
}
else{
     // Codigo de respuesta
     http_response_code(400);
     //Mensaje de error
     echo json_encode(
         array("mensaje" => "No se ha recibido ningún id de producto o id de usuario.")
     );
}

$conexion->close();
?>