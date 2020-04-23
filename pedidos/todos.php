<?php
//Cabeceras

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluimos los ficheros que vamos a necesitar
include_once '../config/basededatos.php';
include_once '../modelos/pedido.php';

//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$pedido = new Pedido($conexion);
$pedidos = $pedido->todos();

if(count($pedidos) >  0)  {
    // Codigo respuesta http - 200 OK
    http_response_code(200);

    //Codificamos los datos en json
    echo json_encode($bd->utf8ize($pedidos));
}
else{
    // Codigo de respuesta
    http_response_code(404);
    //Mensaje de error
    echo json_encode(
        array("mensaje" => "No se encuentran pedidos")
    );
}

$conexion->close();
?>