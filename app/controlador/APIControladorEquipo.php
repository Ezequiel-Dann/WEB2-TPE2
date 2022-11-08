<?php

    require_once "./app/modelo/ModeloEquipo.php";
    require_once "./app/vista/APIVistaEquipo.php";

class APIControladorEquipo{

    private $modelo;
    private $vista;

    public function __construct(){
        $this->modelo = new ModeloEquipo();
        //$this->modeloGrupo = new ModeloGrupo();
        $this->vista = new APIVistaEquipo();
    }

    /*public function obtenerEquipo($params=null){

        if(!empty($params[":ID"]) and is_numeric($params[":ID"])){

            $equipo = $this->modelo->obtenerEquipos($params[":ID"]);
            if($equipo){
                $status = 200;
            }else{
                $status = 404;
            }

            $this->vista->response($equipo,$status);

            return;
        }

        if(!empty($_GET["grupo"])){
            $grupo = $this->modeloGrupo->obtenerGrupoPorNombre();
            return;
        }
        
        $equipos = $this->modelo->obtenerEquipos();

        if($equipos){
            $status = 200;
        }else{
            $status = 404;
        }

        $this->vista->response($equipos, $status);

    }*/

    public function obtenerEquipo($params=null){

        if(!empty($params[":ID"])){          //TODO  hacer el ORDEN
            if(is_numeric($params[":ID"])){
                $equipos = $this->modelo->obtenerEquipos($params[":ID"]);

                if($equipos){
                    $status = 200;
                }else{
                    $status = 404;
                }
                $this->vista->response($equipos,$status);
            }else{
                $this->vista->response("no es id",400);
            }
        }else{
            if(!empty($_GET["grupo"])){
                $grupo = $this->modeloGrupo->obtenerGrupoPorNombre();

                if($grupo){
                    $equipos = $this->modelo->obtenerEquiposGrupo($grupo->id);
                    if($equipos){
                        $status = 200;
                    }else{
                        $status = 404;
                    }
                    $this->vista->response($equipos,$status);
                }else{
                    $this->vista->response("no existe el grupo",404);
                }
            }else{
                $order="asc";
                if(isset($_GET["sort"]) and $this->columnaValida()){
                    if(isset($_GET["order"]) and $_GET["order"] == "desc"){ // TODO convertir a minuscula o mayuscula
                        $order=$_GET["order"];
                    }
                }
                $equipos = $this->modelo->obtenerEquipos(null, $_GET["sort"], $order);// TODO arreglar get sort no seteado
               // $equipos = $this->modelo->obtenerEquipos();
                if($equipos){
                    $status = 200;
                }else{
                    $status = 404;
                }
                $this->vista->response($equipos,$status);
            }

        }

    }

    public function obtenerEquiposGrupo($params=null){
        var_dump($params);
    }

    public function nuevoEquipo(){

        if(!$this->verificarDatosEquipo()){
            $this->vista->response("Datos invalidos",400);
            return;
        }
        if(!$this->controladorGrupo->obtenerGrupo($_POST["grupo"])){
            $this->vista->response("No existe el grupo",404);
            return;
        }

        $equipo = array(
            ":pp" => $_POST["pp"],
            ":puntos" => $_POST["puntos"],
            ":pj" => $_POST["pj"],
            ":pe" => $_POST["pe"],
            ":pais" => $_POST["pais"],
            ":gc" => $_POST["gc"],
            ":pg" => $_POST["pg"],
            ":dif" => $_POST["dif"],
            ":gf" => $_POST["gf"],
            ":fk_id_grupo" => $_POST["grupo"],
        );

        $agregado = $this->modelo->agregarEquipo($equipo);
        if($agregado){
            $this->vista->response($agregado,201);           //devuelve el id con el que se inserto
        }else{
            $this->vista->response("Error al agregar",500);  //se rompio la base de datos
        };


    }

    private function verificarDatosEquipo(){
        return (
            isset($_POST["pp"]) and (!empty($_POST["pp"]) or $_POST["pp"] == "0") and is_numeric($_POST["pp"]) and
            isset($_POST["puntos"]) and (!empty($_POST["puntos"]) or $_POST["puntos"] == "0") and is_numeric($_POST["puntos"]) and
            isset($_POST["pj"]) and (!empty($_POST["pj"]) or $_POST["pj"] == "0") and is_numeric($_POST["pj"]) and
            isset($_POST["pe"]) and (!empty($_POST["pe"]) or $_POST["pe"] == "0") and is_numeric($_POST["pe"]) and
            isset($_POST["gc"]) and (!empty($_POST["gc"]) or $_POST["gc"] == "0") and is_numeric($_POST["gc"]) and
            isset($_POST["grupo"]) and (!empty($_POST["grupo"]) or $_POST["grupo"] == "0") and is_numeric($_POST["grupo"]) and
            isset($_POST["pg"]) and (!empty($_POST["pg"]) or $_POST["pg"] == "0") and is_numeric($_POST["pg"]) and
            isset($_POST["dif"]) and (!empty($_POST["dif"]) or $_POST["dif"] == "0") and is_numeric($_POST["dif"]) and
            isset($_POST["gf"]) and (!empty($_POST["gf"]) or $_POST["gf"] == "0") and is_numeric($_POST["gf"]) and
            isset($_POST["pais"]) and !empty($_POST["pais"])
        );

        
    }
    private function columnaValida(){
        $columnas=['pais','pp','puntos','pj','pe','gc','grupo','pg','dif','gf'];
        if(in_array($_GET["sort"], $columnas)){
            return true;
        }
        echo "donde ta";
        return false;

    }

}