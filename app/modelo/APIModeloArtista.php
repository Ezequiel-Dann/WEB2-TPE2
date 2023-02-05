<?php

class modeloArtista {

    private $db;

    public function __construct(){

        $this->db = new PDO('mysql:host=localhost;'.'dbname=discos2;charset=utf8', 'root', '');
    }

    public function listarArtistas($id=null){
        if(isset($id)){
            $sentencia = $this->db->prepare("SELECT * FROM artistas WHERE id= ?");
            $sentencia -> execute([$id]);
            return $sentencia -> fetch(PDO::FETCH_OBJ);
        }
        $sentencia = $this->db->prepare("SELECT * FROM artista");
        $sentencia -> execute();
        return $sentencia -> fetchAll(PDO::FETCH_OBJ);
    }



    /*

    public function listarArtistas($id=null){
        if(isset($id)){
            $sentencia = $this->db->prepare('SELECT * FROM artistas where id');
            $sentencia->execute([$id]);
            return $sentencia->fetch(PDO::FETCH_OBJ);
        }
        $sentencia = $this->db->prepare('SELECT artistas.nombre, artistas.id,  albums.id_artista
        FROM albums JOIN artistas on artistas.id = albums.id_artista');
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }
   /* public function cantidadAlbums($cantidadSolicitada){
        $sentencia = $this->db->prepare();
    }*/
}

?>