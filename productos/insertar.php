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

    //Conectamos con las Base de Datos
    $bd = new BaseDatos();
    $conexion = $bd->getConexion();
    $producto = new Producto($conexion);

    //Comprobamos que los datos han llegado
    $datos_post = file_get_contents("php://input");
    $peticion = json_decode($datos_post);

    if(isset($peticion->producto)){
        $peticion->producto->id = -1;
        $producto->copiar($peticion->producto);
        if($producto->insertar()){
            $producto->id = $conexion->insert_id;
            //Transformamos en un array
            $producto_arr = array();
            $producto_arr["producto"] = array($producto);
            //Codigo de respuesta
            http_response_code(200);
            echo json_encode($bd->utf8ize($producto_arr));
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
        array("mensaje" => "No se ha recibido ningún producto")
    );
    }


    $conexion->close();
?>