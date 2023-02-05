<?php
require_once './app/modelo/APIModeloAlbum.php';
require_once './app/vista/APIVista.php';
require_once './app/modelo/APIModeloArtista.php';
require_once './app/modelo/modeloValoraciones.php';

class APIControladorAlbum{
    private $modeloAlbums;
    private $vista;
    private $modeloArtistas;
    private $modeloValoraciones;

    public function __construct(){
        $this->modeloAlbums= new modeloAlbums();
        $this -> vista = new vista();
        $this-> modeloArtistas = new modeloArtista();
        $this-> modeloValoraciones = new modeloValoraciones();
    }

    public function eliminarAlbum ($params){
       /* if(!isslogedin){
            $this->response("no estaslogeado" , 401);
        }*/
        
        if(!is_numeric($params[":ID"])){
            $this->vista->response("id invalido debe ser un numero",400);
            return;
            
        }
        if(!isset($params[":ID"]) or empty($params[":ID"])){
            $this->vista->response("error de peticion",400);
            return;
        }
        $album = $this->modeloAlbums->obtenerAlbums($params[":ID"]);
        if($album){
            echo " entro a encontro";
            $this-> vista->response($album,200);
            $valoracion = $this-> modeloValoraciones -> valorado($album->id);
            if(!$valoracion){
                $eliminar= $this-> modeloAlbums ->eliminarAlbum($album->id);
                if(!$eliminar>0){
                    $this->vista->response("no se pudo eliminar",300);
                    return;
                }
                $this-> vista ->response("se elimino correctamente",200);
                return;
            }

            $this-> vista->response("este album contiene valoraciones por eso no puede ser elimiando",200);
        }
        else {echo "no encontro";}
                
    }

    public function albunesArtistas($params){
        $artistasFinales=[];
        if(!is_numeric($params[":albunes"])){
            $this->vista->response("debe ingresar un numero",400);
            return;
        }
        if(!isset($params[":albunes"]) or empty($params[":albunes"])){
            $this->vista->response("error en solicutud",400);
            return;
        }
        $artistas = $this->modeloArtistas->listarArtistas();
        

        foreach($artistas as $key => $artista){
            
            $albumsArtista = $this-> modeloAlbums-> listarAlbunesPorArtista($artista->id);
            if(count($albumsArtista) >= $params[":albunes"]){
                array_push($artistasFinales , $artista ->nombre);
                var_dump($artista->nombre);
            }
            
        }
        echo "ultimo";
        var_dump($artistasFinales);
    }
    
    
}
?>