<?php
class BaseDatos{

     // private $host = 'localhost';
     // private $usuario = 'root';
     // private $contrasena = '';
     // private $nombre = 'tienda';
     //
     // public static $backend = 'http://localhost:80/api';
     // public static $frontend =  'http://localhost:4200';

    //Remoto

    private $host = 'db5000473728.hosting-data.io';
    private $usuario = 'dbu790449';
    private $contrasena = 'Tienda123.';
    private $nombre = 'dbs453948';

    public static $backend = 'https://acmtienda.es';
    public static $frontend = 'https://gallant-golick-62317f.netlify.app';



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
