<?php
    //Cabeceras
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    //Añadimos las cabeceras necesarias para las peticiones POST
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/basededatos.php';

    if(isset($_FILES['imagen']))
    {
      $imagen_name = $_FILES["imagen"]["name"];
      $imagen_tmp_name = $_FILES["imagen"]["tmp_name"];
      $error = $_FILES["imagen"]["error"];

      if($error > 0){
          // Codigo de respuesta
          http_response_code(503);
          //Mensaje de error
          echo json_encode(
            array("mensaje" => "No se ha podido subir la imagen")
          );
       }else
       {
         $imagen_definitiva = './'.$imagen_name;
         if(move_uploaded_file($imagen_tmp_name , $imagen_definitiva)) {
           // Codigo de respuesta
           http_response_code(200);
           //Mensaje de exito
           $url = BaseDatos::$backend . '/imagenes/' . $imagen_name;
           echo json_encode(
             array("url" => $url)
           );
        }else
        {
          // Codigo de respuesta
          http_response_code(503);
          //Mensaje de error
          echo json_encode(
            array("mensaje" => "No se ha podido subir la imagen")
          );
        }
       }
    }else{
      // Codigo de respuesta
      http_response_code(400);
      //Mensaje de error
      echo json_encode(
      array(
        "mensaje" => "No se ha recibido ninguna imagen",
        "datos_recibidos" => json_encode($_FILES)
      )
      );
    }
