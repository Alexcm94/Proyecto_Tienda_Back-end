<?php
class Producto{
    //Conexion con la bd y nombre de la tabla

    private $conexion;
    private $tabla ='producto';

    // Columnas

    public $id;
    public $nombre;
    public $tipo;
    public $subtipo;
    public $precio;
    public $descuento;
    public $descripcion;
    public $imagen;

    //Contructor

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    //Devuelve todos los productos de la base de datos
    public function todos(){
        $consulta = "SELECT * FROM ".$this->tabla;
        $resultado = $this->conexion->query($consulta);
        return $resultado;
    }

    public function copiar($otro){
        $this->id = $otro->id;
        $this->nombre = $otro->nombre;
        $this->tipo = $otro->tipo;
        $this->subtipo = $otro->subtipo;
        $this->precio = $otro->precio;
        $this->descuento = $otro->descuento;
        $this->descripcion = $otro->descripcion;
        $this->imagen = $otro->imagen;

    }

    public function insertar(){
        $consulta = "INSERT INTO ".$this->tabla."(nombre, tipo, subtipo, precio, descuento, descripcion, imagen) VALUES ('".$this->nombre."','".$this->tipo."','".$this->subtipo."',".$this->precio.",".$this->descuento.",'".$this->descripcion."','".$this->imagen."')";
        $resultado = $this->conexion->query($consulta);
        return $resultado;
    }
}
    ?>