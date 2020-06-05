<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/Exception.php';
require '../lib/PHPMailer.php';
require '../lib/SMTP.php';

class Usuario{
    //Conexion con la bd y nombre de la tabla

    private $conexion;
    private $tabla ='usuario';

    // Columnas

    public $id;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $admin;
    public $correo_electronico;
    public $contrasena;
    public $direccion;
    public $numero_tarjeta;
    public $cvv;
    public $fecha_tarjeta;
    public $tipo_tarjeta;
    public $confirmado;

    //Contructor

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    //Leer todos los usuarios
    public function todos(){
        $consulta = "SELECT * FROM ".$this->tabla;
        $resultado = $this->conexion->query($consulta);
        return $resultado;
    }

    // Busca usuario por su correo electrónico

    public function buscarPorCorreo($correo){
        $consulta = "SELECT * FROM ".$this->tabla." WHERE correo_electronico = '" .$correo."'";
        $resultado = $this->conexion->query($consulta);
        if($resultado->num_rows == 0){
            return 0;
        }
        else{
            $fila = $resultado->fetch_assoc();
            $this->id = $fila["id"];
            $this->nombre = $fila["nombre"];
            $this->apellidos = $fila["apellido"];
            $this->telefono = $fila["telefono"];
            $this->admin= $fila["admin"];
            $this->correo_electronico= $fila["correo_electronico"];
            $this->contrasena= $fila["contrasena"];
            $this->direccion= $fila["direccion"];
            $this->numero_tarjeta = $fila["numero_tarjeta"];
            $this->fecha_tarjeta = $fila["fecha_tarjeta"];
            $this->tipo_tarjeta = $fila["tipo_tarjeta"];
            $this->cvv = $fila["cvv"];
            $this->confirmado = $fila["confirmado"];

            return $this->id;
        }
    }

    public function insertar(){
        $consulta = "INSERT INTO ".$this->tabla."(nombre, apellido, telefono, contrasena, direccion, correo_electronico, numero_tarjeta, tipo_tarjeta, fecha_tarjeta, cvv) VALUES ('".$this->nombre."', '".$this->apellidos."','".$this->telefono."','".$this->contrasena."', '".$this->direccion."','".$this->correo_electronico."','".$this->numero_tarjeta."','".$this->tipo_tarjeta."','".$this->fecha_tarjeta."','".$this->cvv."')";
        $resultado = $this->conexion->query($consulta);
        return $resultado;
    }

    public function copiar($otro_usuario){
        $this->id = $otro_usuario->id;
            $this->nombre = $otro_usuario->nombre;
            $this->apellidos = $otro_usuario->apellidos;
            $this->telefono = $otro_usuario->telefono;
            $this->admin= $otro_usuario->admin;
            $this->correo_electronico= $otro_usuario->correo_electronico;
            $this->contrasena= $otro_usuario->contrasena;
            $this->direccion= $otro_usuario->direccion;
            $this->numero_tarjeta= $otro_usuario->numero_tarjeta;
            $this->tipo_tarjeta= $otro_usuario->tipo_tarjeta;
            $this->fecha_tarjeta= $otro_usuario->fecha_tarjeta;
            $this->cvv= $otro_usuario->cvv;
            $this->confirmado= $otro_usuario->confirmado;

    }

    public function buscarPorId($id){
        $consulta = "SELECT * FROM ".$this->tabla." WHERE id = '" .$id."'";
        $resultado = $this->conexion->query($consulta);
        if($resultado->num_rows == 0){
            return 0;
        }
        else{
            $fila = $resultado->fetch_assoc();
            $this->id = $fila["id"];
            $this->nombre = $fila["nombre"];
            $this->apellidos = $fila["apellido"];
            $this->telefono = $fila["telefono"];
            $this->admin= $fila["admin"];
            $this->correo_electronico= $fila["correo_electronico"];
            $this->contrasena= $fila["contrasena"];
            $this->direccion= $fila["direccion"];
            $this->numero_tarjeta = $fila["numero_tarjeta"];
            $this->fecha_tarjeta = $fila["fecha_tarjeta"];
            $this->tipo_tarjeta = $fila["tipo_tarjeta"];
            $this->cvv = $fila["cvv"];
            $this->confirmado = $fila["confirmado"];
            return $this->id;
        }
    }

    public function cambiarTarjeta($tipo_tarjeta, $numero_tarjeta, $fecha_tarjeta, $cvv, $id_usuario){
        $consulta = "UPDATE usuario SET numero_tarjeta='$numero_tarjeta',`cvv`='$cvv',`fecha_tarjeta`='$fecha_tarjeta',`tipo_tarjeta`='$tipo_tarjeta' WHERE id=$id_usuario";
        $resultado = $this->conexion->query($consulta);
        return $resultado;

    }

    public function mandarCorreoConfirmacion() {
      $mailer = new PHPMailer(false);
      $to_email = $this->correo_electronico;
      $body = "Para confirmar tu cuenta haz click <a href='" . BaseDatos::$backend . "/usuarios/confirmar.php?id_usuario=$this->id' target='_blank'>aquí</a>";

      try {
        $mailer->SMTPDebug = 0;
        $mailer->isSMTP();

        $mailer->Host = 'smtp.ionos.es';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'tienda@acmtienda.es';
        $mailer->Password = 'CorreoTienda123.';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 587;

        $mailer->setFrom('tienda@acmtienda.es', 'Tienda ACM');
        $mailer->addAddress($to_email);
        $mailer->isHTML(true);
        $mailer->Subject = "Confirma tu cuenta";
        $mailer->Body = $body;

        $mailer->send();

        $mailer->ClearAllRecipients();

        return true;
      } catch(Exception $e) {
        return false;
      }
    }

    public function confirmar($id_usuario) {
      $consulta = "UPDATE usuario SET confirmado='1' WHERE id=$id_usuario";
      $resultado = $this->conexion->query($consulta);
      return $resultado;
    }
}
?>
