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
 include_once '../modelos/lineaPedido.php';
 include_once '../modelos/pedido.php';
 include_once '../modelos/carrito.php';

 //Conectamos con las Base de Datos
 $bd = new BaseDatos();
 $conexion = $bd->getConexion();
 $pedido = new Pedido($conexion);

 //Comprobamos que los datos han llegado
 $datos_post = file_get_contents("php://input");
 $peticion = json_decode($datos_post);

 if(isset($peticion->id_usuario)){
     $id_usuario = $peticion->id_usuario;
     $carrito = new Carrito($conexion);
     if($carrito->getCarrito($id_usuario)){
        if($pedido->crearPedido($carrito)){
            // Codigo de respuesta
            http_response_code(200);
            //Mensaje de error
            echo json_encode(
            array("mensaje" => "Pedido  creado.")
            );
        }else{
            // Codigo de respuesta
            http_response_code(500);
            //Mensaje de error
            echo json_encode(
            array("mensaje" => "No se ha podido realizar el pedido.")
            );
        }
     }else{
        // Codigo de respuesta
        http_response_code(404);
        //Mensaje de error
        echo json_encode(
        array("mensaje" => "No hay ningun carrito del usuario especificado.")
    );
     }

 }else{
    // Codigo de respuesta
    http_response_code(400);
    //Mensaje de error
    echo json_encode(
        array("mensaje" => "No se ha recibido ningún id_usuario")
    );
}


$conexion->close();






?>