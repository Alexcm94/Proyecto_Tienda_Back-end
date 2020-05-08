<?php
//Cabeceras
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain; charset=UTF-8");

//Incluimos los ficheros que vamos a necesitar
include_once '../config/basededatos.php';
include_once '../modelos/productos.php';


//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$producto = new Producto($conexion);


if(isset($_GET['searchPalabra'])){
        $palabra = $_GET['searchPalabra'];
       // Codigo de respuesta
       http_response_code(200);
       $respuesta = $producto->searchPalabra($palabra);
       echo json_encode($respuesta);
   }
   else {
       // Codigo de respuesta
       http_response_code(400);
       //Mensaje de error
       echo json_encode(
            array("mensaje" => "Debes enviar una palabra para buscar")
        );
   }


$conexion->close();
?>