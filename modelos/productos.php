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
        $productos = array();
        while($fila = $resultado->fetch_assoc()) {
            array_push($productos, $fila);
        }
        return $productos;
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

    public function getProducto($id){
        $consulta = "SELECT * FROM ".$this->tabla." WHERE id = '" .$id."'";
        $resultado = $this->conexion->query($consulta);
        if($resultado->num_rows == 0){
            return 0;
        }
        else{
            $fila = $resultado->fetch_assoc();
            $this->id = $fila["id"];
            $this->nombre = $fila["nombre"];
            $this->tipo = $fila["tipo"];
            $this->subtipo = $fila["subtipo"];
            $this->precio = $fila["precio"];
            $this->descuento = $fila["descuento"];
            $this->descripcion = $fila["descripcion"];
            $this->imagen = $fila["imagen"];

            return $this->id;
        }
    }
}
    ?>