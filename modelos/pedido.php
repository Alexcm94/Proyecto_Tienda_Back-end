<?php
include_once '../modelos/productos.php';
class Pedido{
    //Conexion con la bd y nombre de la tabla

    private $conexion;
    private $tabla ='pedidos';

    //Columnas
    public $id;
    public $id_usuario;
    public $estado;
    public $fecha;

    //Contructor

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function crearPedido($carrito){
        $consulta = " INSERT INTO ".$this->tabla."(id_usuario, estado, fecha) VALUES (".$carrito->id_usuario.", 'PENDIENTE', NOW())";
        $resultado = $this->conexion->query($consulta);
        if($resultado){
            $this->id = $this->conexion->insert_id;
            $this->id_usuario = $carrito->id_usuario;
            $this->estado = "PENDIENTE";
            $this->fecha = date("Y-m-d");
            for ($i=0; $i < count($carrito->productos); $i++) { 
                
                $precio = $this->calcularPrecioFinal($carrito->productos[$i]["id"]);
                $consulta='INSERT INTO linea_pedido (id_producto, id_pedido, precio, cantidad, talla) VALUES ('.$carrito->productos[$i]["id"].', '.$this->id.','.$precio.','.$carrito->productos[$i]['cantidad'].', "'.$carrito->productos[$i]['talla'].'")';
                $resultado = $this->conexion->query($consulta);
                if(!$resultado){
                    return $resultado;
                }
            }
            //Borramos el carrito de la compra
            $consulta = "DELETE FROM carrito WHERE id_usuario = ".$this->id_usuario;
            $resultado = $this->conexion->query($consulta);
        }
        return $resultado;
    }

    private function calcularPrecioFinal($id_producto){
        $producto = new Producto($this->conexion);
        $producto->getProducto($id_producto);
        $precio = ($producto->precio - ($producto->precio * $producto->descuento / 100 ));
        return $precio;
    }

    public function pedidosUsuario($id_usuario){
        $consulta = "SELECT id, estado, fecha FROM pedidos WHERE id_usuario = ".$id_usuario;
        $resultado = $this->conexion->query($consulta);

        if($resultado){
            $pedidos = [];
            while($fila = $resultado->fetch_assoc()){
                $pedido = [];
                $pedido["id"] = $fila["id"];
                $pedido["estado"] = $fila["estado"];
                $pedido["fecha"] = $fila["fecha"];
                $pedido["lineas"] = $this->lineasPedido($pedido["id"]);
                array_push($pedidos, $pedido);
            }
            return $pedidos;
        }else{
            return false;
        }
    }

    public function lineasPedido($id_pedido){
        $lineas = [];
        $consulta = "SELECT linea_pedido.precio, linea_pedido.cantidad, producto.nombre, linea_pedido.talla FROM linea_pedido, producto  WHERE linea_pedido.id_pedido = ".$id_pedido." AND linea_pedido.id_producto = producto.id ";
        $resultado = $this->conexion->query($consulta);
        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                array_push($lineas, $fila);
            }
        }
        return $lineas;
    }

    public function todos(){
        $sql = "SELECT id, fecha, estado FROM pedidos";
        $resultado = $this->conexion->query($sql);
        $pedidos = array();
        while($fila = $resultado->fetch_assoc()){
            $pedido = $fila;
            $pedido["lineas"] = $this->sacarFilas($pedido["id"]);
            $pedido["comprador"] = $this->sacarDatosComprador($pedido["id"]);
            array_push($pedidos, $pedido);
        }
        return $pedidos;
    }
    // ARREGLAR FALLO
    private function sacarFilas($id_pedido){
        $sql = "SELECT producto.nombre, linea_pedido.cantidad, linea_pedido.precio, linea_pedido.talla FROM linea_pedido, producto WHERE linea_pedido.id_producto = producto.id AND linea_pedido.id_pedido = ".$id_pedido;
        $resultado = $this->conexion->query($sql);
        $filas = array();
        while($fila = $resultado->fetch_assoc()){
            array_push($filas, $fila);
        }
        return $filas;
    }
    private function sacarDatosComprador($id_pedido){
        $comprador = array();
        $sql = 'SELECT usuario.nombre, usuario.apellido, usuario.direccion, usuario.telefono FROM usuario, pedidos WHERE pedidos.id = '.$id_pedido.' AND pedidos.id_usuario = usuario.id ';
        if($resultado = $this->conexion->query($sql)){
            if($fila = $resultado->fetch_assoc()){
                $comprador = $fila;
            }
        }
        return $comprador;
    }

    public function borrarPedidosVacios() {
        $sql = "DELETE FROM pedidos WHERE id NOT IN (SELECT id_pedido FROM linea_pedido)";
        $resultado = $this->conexion->query($sql);
        return $resultado;
    }
}

?>