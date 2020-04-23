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

    if(isset($peticion->producto)){
        $id_usuario = $peticion->producto->id_usuario;
        $id_producto = $peticion->producto->id_producto;
        $cantidad = $peticion->producto->cantidad;
        $talla = $peticion->producto->talla;

        if($carrito->getCarrito($id_usuario)){
            if($carrito->insertarProducto($id_producto, $cantidad, $talla)){
                $respuesta_arr = array();
                $respuesta_arr["cantidad"] = $carrito->numeroElementos();

                http_response_code(200);
                echo json_encode($respuesta_arr);
            }else{
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo insertar. Error interno"));
            }
        }else{
            http_response_code(404);
            echo json_encode(array("mensaje" => "No se pudo encontrar el carrito"));
        }
    }else{
        // Codigo de respuesta
        http_response_code(400);
        //Mensaje de error
        echo json_encode(
        array("mensaje" => "No se ha recibido ningún producto")
        );
    }





?>