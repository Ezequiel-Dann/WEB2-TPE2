<?php
require_once './app/vista/APIVista.php';
require_once './app/modelo/APIModeloArtista.php';
require_once './app/modelo/APIModeloAlbum.php';

class APIControladorArtista {

    private $modeloArtistas;
    private $modeloAlbums;
    private $vista;


    public function __construct(){
        $this->modeloArtistas = new modeloArtista();
        $this->vista = new vista();
        $this->modeloAlbums = new modeloAlbums();

    }
    

}

?>