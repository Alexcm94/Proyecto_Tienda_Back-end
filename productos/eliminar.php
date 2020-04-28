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
include_once '../modelos/productos.php';
include_once '../modelos/pedido.php';

//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$producto = new Producto($conexion);

//Comprobamos que los datos han llegado
$datos_post = file_get_contents("php://input");
$peticion = json_decode($datos_post);

if(isset($peticion->id_producto)) {
   if($producto->eliminar($peticion->id_producto)) {
       $pedido = new Pedido($conexion);
       $pedido->borrarPedidosVacios();
       // Codigo de respuesta
       http_response_code(200);
       $respuesta = $producto->todos();
       echo json_encode($respuesta);
   }
   else {
       // Codigo de respuesta
       http_response_code(503);
       //Mensaje de error
       echo json_encode(
            array("mensaje" => "Error interno del servidor")
        );
   }
}
else {
    // Codigo de respuesta
    http_response_code(400);
    //Mensaje de error
    echo json_encode(
        array("mensaje" => "No se ha recibido ningún id de producto")
    );
}

$conexion->close();
?>