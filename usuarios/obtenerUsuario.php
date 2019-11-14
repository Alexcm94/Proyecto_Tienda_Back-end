<?php

    //Cabeceras

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    //Incluimos los ficheros que vamos a necesitar
    include_once '../config/basededatos.php';
    include_once '../modelos/usuarios.php';

    //Conectamos con las Base de Datos
    $bd = new BaseDatos();
    $conexion = $bd->getConexion();
    $usuario = new Usuario($conexion);
     if(isset($_GET["id"])){
        $id = $_GET["id"];
        if($usuario->buscarPorId($id) != 0){
            http_response_code(200);
            // Codificamos los datos en JSON
            $usuario_arr = array ();
            $usuario_arr["usuario"] = $usuario;
            echo json_encode($bd->utf8ize($usuario_arr));


        }else{
            http_response_code(404);
            echo json_encode(array("mensaje" => "El id indicado no pertenece a ningun usuario"));
        }
     }else{
         //Codigo de respuesta
         http_response_code(400);
         //Mensaje de error
         echo json_encode(array("mensaje" => "Los datos recibidos están incompletos"));
     }



?>