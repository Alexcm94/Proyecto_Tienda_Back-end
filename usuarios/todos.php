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
    
    //Leer todos los usuarios

    $resultado = $usuario->todos();
    $num = $resultado->num_rows;

    if($num > 0){

        //Sacamos usuarios de la Base de Datos
        $usuarios_arr = array();
        $usuarios_arr["filas"]=array();

        $fila = $resultado->fetch_assoc();
        
       

        while($fila){
        array_push($usuarios_arr["filas"], $fila);
        

        $fila = $resultado -> fetch_assoc();
        }
        // Codigo respuesta http - 200 OK
        http_response_code(200);

        //Codificamos los datos en json
        echo json_encode($bd->utf8ize($usuarios_arr));
        
    }
    else{
        // Codigo de respuesta
        http_response_code(404);
        //Mensaje de error
        echo json_encode(
            array("mensaje" => "No se encuentran usuarios")
        );
        
    }
?>