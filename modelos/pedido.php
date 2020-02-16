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
                //CREAR PRECIO FINAL
                $precio_final = $this->calcularPrecioFinal($carrito->productos[$i]["id"], $carrito->productos[$i]["cantidad"]);
                $consulta='INSERT INTO linea_pedido (id_producto, id_pedido, precio_final) VALUES ('.$carrito->productos[$i]["id"].', '.$this->id.','.$precio_final.')';
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

    private function calcularPrecioFinal($id_producto, $cantidad){
        $producto = new Producto($this->conexion);
        $producto->getProducto($id_producto);
        $precio_final = ($producto->precio - ($producto->precio * $producto->descuento / 100 )) * $cantidad;
        return $precio_final;
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
        $consulta = "SELECT linea_pedido.precio_final, producto.nombre FROM linea_pedido, producto  WHERE linea_pedido.id_pedido = ".$id_pedido." AND linea_pedido.id_producto = producto.id ";
        $resultado = $this->conexion->query($consulta);
        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                array_push($lineas, $fila);
            }
        }
        return $lineas;
    }
}

?>