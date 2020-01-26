<?php
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
        return true;
    }
}

?>