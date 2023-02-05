<?php

class modeloAlbums{


    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=discos2;charset=utf8', 'root', '');
    }
    public function obtenerAlbums($id=null){
        $sentencia = $this->db->prepare("SELECT id, titulo, productor, genero, fechaLanzamiento, id_artista FROM album where album.id = ? ");
        $sentencia -> execute([$id]);
        return $sentencia -> fetch(PDO::FETCH_OBJ);
        
    }
    public function eliminarAlbum($id){
        $sentencia = $this-> db-> prepare("DELETE FROM album WHERE album.id = ?");
        $sentencia -> execute ([$id]);
        return $sentencia -> rowCount();
    }
    public function estrellasAlbunes($estrellas){
        $sentencia = $this-> db-> prepare("SELECT estrellas, id_album FROM valoracion WHERE valoracion.estrellas=?");
        $sentencia-> execute([$estrellas]);
        return $sentencia ->fetchAll(PDO::FETCH_OBJ);
    }
    public function listarAlbunesPorArtista($idArtista){
        $sentencia = $this->db->prepare("SELECT album.titulo FROM album JOIN artista on album.id_artista = artista.id where artista.id = ? ");
        $sentencia -> execute([$idArtista]);
        return $sentencia -> fetchAll(PDO::FETCH_OBJ);

    }
}

/*CREATE TABLE `album` (
  `id` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `productor` varchar(250) NOT NULL,
  `genero` varchar(250) NOT NULL,
  `fechaLanzamiento` varchar(250) NOT NULL,
  `id_artista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;*/
//mysql: es un sistema de gestion de bases de datos (software).
//sql: es un lenguaje universal de las base de datos.

//server side: modelo cliente servidor.

?>
