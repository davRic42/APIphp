<?php
require_once("config.php");

class Conection{

    public $mensaje = "";

    static public function connection(){
        $con = false;
        try{
            $data = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
            $con = new PDO($data, DB_USERNAME, DB_PASSWORD);
            return $con;
        } catch(PDOException $e){
            $mensaje = array(
                "COD" => "000",
                "MENSAJE" => ("Error en base de datos".$e)
            );
        }
        return $con;
    }
}


?>