<?php
class modeloValoraciones{

    private $db;

    public function __construct(){
        $this-> db = new PDO('mysql:host=localhost;'.'dbname=discos2;charset=utf8', 'root', '');
    }


    public function valorado($id){
        $sentencia= $this->db->prepare("SELECT estrellas, id_album, id_user FROM valoracion where valoracion.id_album = ?");
        $sentencia ->execute([$id]);
        return $sentencia -> fetch(PDO::FETCH_OBJ);
        
    }

    /*CREATE TABLE `valoracion` (
  `id` int(11) NOT NULL,
  `estrellas` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
}


?>