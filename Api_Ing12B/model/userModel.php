<?php
require_once "ConDB.php";

class UserModel{
    static public function createUser($data){
        $cantMail = self::getMail($data["use_mail"]);
        if($cantMail == 0){
            $query = "INSERT INTO users(use_id, use_mail, use_pss, use_dateCreate, us_identifier, us_key, us_status) 
            VALUES (NULL, :use_mail, :use_pss, :use_dateCreate, :us_identifier, :us_key, :us_status)";
            $status="0";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
            $stament->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
            $stament->bindParam(":use_dateCreate", $data["use_dateCreate"], PDO::PARAM_STR);
            $stament->bindParam(":us_identifier", $data["us_identifier"], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data["us_key"], PDO::PARAM_STR);
            $stament->bindParam(":us_status", $status, PDO::PARAM_STR);
            $mensaje = $stament->execute() ? "ok" : Conection::connection() ->errorInfo();
            $stament -> closeCursor();
            $stament = null;
            $query = "";
        }
        else{
            $mensaje = "Usuario ya esta registrado";
        }
        return $mensaje;
    }
   // traer el correo
    static private function getMail($mail){
        $query = "";
        $query = "SELECT use_mail FROM users WHERE use_mail = '$mail';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->rowCount();
        return $result;
    }
    //traer los usuarios
    static function getUsers($id){
        $query = "";
        $id = is_numeric($id) ? $id : 0;
        $query = "SELECT use_id, use_mail, use_dateCreate FROM users";
        $query.=($id > 0) ? " WHERE users.use_id = '$id' AND " : "";
        $query.=($id > 0) ? " us_status='1';" : " WHERE us_status = '1';";  
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Login
    static public function login($data){
        $query = "";
        $user = $data['use_mail'];
        $pss = md5($data['use_pss']);
        
        if(!empty($user) && !empty($pss)){
            $query = "SELECT us_identifier, us_key, use_id FROM users WHERE use_mail='$user' and use_pss = '$pss' and us_status = '1'";
            $stament = Conection::connection()->prepare($query);
            $stament->execute();
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        else{
            $mensaje = array(
                "COD" => "001",
                "MENSAJE" => ("error en credenciales")
            );
            return $mensaje;
        }
        $query="";
    }
    //Autentificar
    static public function getUserAuth(){
        $query="";
        $query="SELECT us_identifier,us_key FROM users WHERE us_status = '1';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //eleminar usuario
    static public function deleteUser($data){
        $query="";
        $delEmail= self::getMail($data['use_mail']);
        if($delEmail==1){
            $query = "DELETE FROM users WHERE use_mail = :userMail";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":userMail", $data['use_mail'], PDO::PARAM_STR);
            $mensaje = $stament->execute() ? "Usuario eliminado correctamente" : Conection::connection()->errorInfo();
            $stament->closeCursor();
            $stament = null;
        } else{
            $mensaje="correo incorrecto";
        }
        
        return $mensaje;
    }
    //actualizar data de usuario
    static public function updateUser($data){
        $query="";
        if($delEmail==1){
            $delEmail= self::getMail($data['use_mail']);
            $query = "UPDATE users 
                    SET use_mail = :use_mail, 
                        use_pss = :use_pss, 
                    WHERE use_id = :userId 
                    AND user_status = '1';";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":userId",  $data["use_id"], PDO::PARAM_INT);
            $stament->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
            $stament->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
            $mensaje = $stament->execute() ? "Información de usuario actualizada correctamente" : Conection::connection()->errorInfo();
            $stament->closeCursor();
            $stament = null;
        }  else{
            $mensaje="correo incorrecto";
        }
        return $mensaje;
    }
    
    //eactivar usuario
    static public function activateUser($data){
        $query="";
        $delEmail= self::getMail($data['use_mail']);
        if($delEmail==1){
            $query = "UPDATE users SET us_status = '1' WHERE use_mail = :userMail";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":userMail", $data["use_mail"], PDO::PARAM_STR);
            $mensaje = $stament->execute() ? "Usuario activado correctamente" : Conection::connection()->errorInfo();
            $stament->closeCursor();
            $stament = null;
        }  else{
            $mensaje="correo incorrecto";
        }
        return $mensaje;
    }
    
    
}


?>