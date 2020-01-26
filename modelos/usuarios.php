<?php
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
            return $this->id;
        }
    }
}
?>