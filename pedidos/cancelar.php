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
include_once '../modelos/pedido.php';


//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$pedido = new Pedido($conexion);

//Comprobamos que los datos han llegado
$datos_post = file_get_contents("php://input");
$peticion = json_decode($datos_post);

if(isset($peticion->id_pedido)){
    $id_pedido = $peticion->id_pedido;
    if($pedido->cancelarPedido($id_pedido)) {
        // Codigo de respuesta
        http_response_code(200);
        $lista_pedidos = $pedido->todos();
        //Mandamos la lista de pedidos actualizada
        echo json_encode($lista_pedidos);
    }else{
        // Codigo de respuesta
        http_response_code(503);
        //Mensaje de error
        echo json_encode(
            array("mensaje" => "Error interno del servidor")
        );
    }
}else{
   // Codigo de respuesta
   http_response_code(400);
   //Mensaje de error
   echo json_encode(
       array("mensaje" => "Petición incompleta, falta id del pedido")
   );
}

$conexion->close();
?>