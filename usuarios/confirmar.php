<?php

// Cabeceras para GET
// Tiene que ser GET porque se llama desde un enlace en el correo electronico
// y los enlaces solo pueden enviar peticiones GET
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain; charset=UTF-8");

// Incluimos ficheros que vamos a necesitar
include_once '../config/basededatos.php';
include_once '../modelos/usuarios.php';

//Conectamos con las Base de Datos
$bd = new BaseDatos();
$conexion = $bd->getConexion();
$usuario = new Usuario($conexion);




// Comprobar que se ha recibido el id_usuario
if(isset($_GET["id_usuario"])){
  // Llamar al metodo del modelo de usuario que sea confirmar()

  $usuario->confirmar($_GET["id_usuario"]);

  // Redirigir a la tienda
  header("Location: " . BaseDatos::$frontend . "/login");
}else{
    //Mandamos respuesta de error
    http_response_code(400);
    echo json_encode(array("mensaje" => "No se ha recibido el id de usuario"));
}




?>
