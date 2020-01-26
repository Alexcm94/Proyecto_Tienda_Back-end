<?php
//Cabeceras

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluimos los ficheros que vamos a necesitar
include_once '../config/basededatos.php';
include_once '../modelos/productos.php';

//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$producto = new Producto($conexion);

//Leer todos los productos
$resultado = $producto->todos();
$num = $resultado->num_rows;
if($num>0){
    $productos_arr = array();
    $productos_arr["filas"]=array();

    while($fila = $resultado->fetch_assoc()){
        $producto_fila = array(
            "id" => $fila["id"],
            "tipo" => $fila["tipo"],
            "subtipo" => $fila["subtipo"],
            "nombre" => $fila["nombre"],
            "descripcion" => $fila["descripcion"],
            "precio" => $fila["precio"],
            "descuento" => $fila["descuento"],
            "imagen" => $fila["imagen"],
        );
        array_push($productos_arr["filas"], $producto_fila);
    }
    // Codigo respuesta http - 200 OK
    http_response_code(200);

    //Codificamos los datos en json
    echo json_encode($bd->utf8ize($productos_arr));
}else{
    // Codigo de respuesta
    http_response_code(404);
    //Mensaje de error
    echo json_encode(
        array("mensaje" => "No se encuentran productos")
    );
}
$conexion->close();
?>