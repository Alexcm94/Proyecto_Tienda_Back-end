<?php
class Carrito{
    private $conexion;
    private $tabla = "carrito";

    //atributos del carrito
    public $id_usuario;
    public $productos;

    //Contructor
    public function __construct($conexion){
        $this->conexion = $conexion;
        $this->productos = array();
    }
    public function getCarrito($id_usuario){
        $consulta = "SELECT producto.id, producto.nombre, producto.imagen, producto.precio, carrito.cantidad, carrito.talla FROM producto, carrito WHERE carrito.id_usuario = ".$id_usuario." AND carrito.id_producto = producto.id";
        $resultado = $this->conexion->query($consulta);
        if($resultado){
            $this->id_usuario = $id_usuario;
            $this->productos = array();
            while($fila = $resultado->fetch_assoc()){
                array_push($this->productos, $fila);
            }
            return true;
        }else{
            return false;
        }
    }
    public function cantidadProducto($id_producto){
        $sql = "SELECT cantidad FROM carrito WHERE id_producto = ".$id_producto." AND id_usuario=".$this->id_usuario;
        if($resultado = $this->conexion->query($sql)){
            if($fila = $resultado->fetch_assoc()){
                return $fila["cantidad"];
            }
        }
        return 0;
    }

    public function insertarProducto($id_producto,$cantidad, $talla){
        $cantidad_existente = $this->cantidadProducto($id_producto);
        if($cantidad_existente == 0){
            $sql = 'INSERT INTO carrito(id_usuario, id_producto, cantidad, talla) VALUES ('.$this->id_usuario.','.$id_producto.','.$cantidad.', "'.$talla.'")';
            $resultado = $this->conexion->query($sql);
            if($resultado){
                $producto = array();
                $producto["id"] = $id_producto;
                $producto["cantidad"] = $cantidad;
                $producto["talla"] = $talla;
                array_push($this->productos, $producto);
                return true;
            }else{
                return false;
            }
        }
        else{
            $nueva_cantidad = $cantidad_existente + $cantidad;
            $sql = "UPDATE carrito SET cantidad =".$nueva_cantidad." WHERE id_usuario =".$this->id_usuario." AND id_producto=".$id_producto;
            if($resultado = $this->conexion->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        
    }

    public function numeroElementos(){
        return count($this->productos);
    }
}
?>