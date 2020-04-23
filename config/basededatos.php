<?php
class BaseDatos{

    private $host = 'localhost';
    private $usuario = 'root';
    private $contrasena = '';
    private $nombre = 'tienda';

    //Remoto

    // private $host = 'db5000222612.hosting-data.io';
    // private $usuario = 'dbu340239';
    // private $contrasena = '1234Abcd:';
    // private $nombre = 'dbs217331';
    
    public $conexion;

    public function getConexion(){
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombre);

        if($this->conexion->connect_error){
            echo "Error, la conexión ha fallado".$this->conexion->connect_error;
            exit;
        }

        return $this->conexion;
    }

    public function utf8ize($d){
         if(is_array($d)){
                foreach ($d as $k => $v){
                    $d[$k] = $this->utf8ize($v);
                }
         }elseif (is_string($d)){
             return mb_convert_encoding($d, "UTF-8");
         }
         return($d);
    }
}
?>