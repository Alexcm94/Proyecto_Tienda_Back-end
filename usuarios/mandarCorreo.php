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

if(isset($_GET["correo_electronico"])) {
  if($usuario->buscarPorCorreo($_GET["correo_electronico"])) {
    $usuario->mandarCorreoConfirmacion();
    //Mandamos respuesta de error
    http_response_code(200);
    echo json_encode(array("mensaje" => "Correo enviado"));
  }
  else {
    //Mandamos respuesta de error
    http_response_code(404);
    echo json_encode(array("mensaje" => "El usuario no existe"));
  }
}
else {
  //Mandamos respuesta de error
  http_response_code(400);
  echo json_encode(array("mensaje" => "No se ha recibido el correo electronico"));
}

?>
