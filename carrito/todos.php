<?php
//Cabeceras

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluimos los ficheros que vamos a necesitar
include_once '../config/basededatos.php';
include_once '../modelos/carrito.php';

//Conexion con la base de datos

$bd = new BaseDatos();
$conexion = $bd->getConexion();
$carrito = new Carrito($conexion);

if(isset($_GET["id_usuario"])){
    $id_usuario = $_GET["id_usuario"];
    if($carrito->getCarrito($id_usuario)){
        $carrito_arr = array();
        $carrito_arr["filas"]=$carrito->productos;
        $carrito_arr["cantidad"]=count($carrito->productos);
        http_response_code(200);
        echo json_encode($carrito_arr);

    }else{
        http_response_code(503);
        echo json_encode(array("mensaje"=> "No se pudo obtener el carrito, error interno."));
    }
}else{
    //Codigo de respuesta
    http_response_code(400);
    //Mensaje de error
    echo json_encode(array("mensaje" => "Los datos recibidos están incompletos"));
}






$conexion->close();
?>