<?php
class LineaPedido{
    //Conexion con la bd y nombre de la tabla

    private $conexion;
    private $tabla ='lineas_pedido';

    //Columnas
    public $id_producto;
    public $id_pedido;
    public $precio;
    public $cantidad;
    public $talla;

    //Contructor

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    
}

?>