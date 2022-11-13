<?php

    require_once "./app/modelo/ModeloEquipo.php";
    require_once "./app/modelo/ModeloGrupo.php";
    require_once "./app/vista/APIVistaEquipo.php";

class APIControladorEquipo{

    private $modelo;
    private $vista;

    public function __construct(){
        $this->modelo = new ModeloEquipo();
        $this->modeloGrupo = new ModeloGrupo();
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

        if(!empty($params[":ID"])){
            //pide equipo por id            
            if(is_numeric($params[":ID"])){

                $equipos = $this->modelo->obtenerEquipos($params[":ID"]);

            }else{
                $this->vista->response("no es id",400);
                return;
            }
        }else{
            //puede ser mas de un equipo
            //configurar ordenamiento
            $order = null;
            $sort = null;
            $limit = null;
            $offset = 0;
            
            $_GET = array_change_key_case ($_GET,CASE_LOWER);
                        
            if (!$this->parametrosValidos()){
                return;
            }
           

            if (isset($_GET["sort"])) {
                $sort = strtolower($_GET["sort"]);
                $order = "asc"; //direccion por defecto si hay un sort
            }
            if (isset($_GET["order"])){

                $order = strtoupper($_GET["order"]);  //para que no se rompa con mayusculas     
            }
            if(isset($_GET["limit"])){
                $limit = intval($_GET["limit"]);
            }
            if(isset($_GET["offset"])){
                $offset = intval($_GET["offset"]);
            }
            var_dump($_GET);
            
            if(!empty($_GET["grupo"])){
                //pide los equipos de un grupoo
                $grupo = $this->modeloGrupo->obtenerGrupo($_GET["grupo"]);

                if($grupo){
                    $equipos = $this->modelo->obtenerEquiposGrupo($grupo->id_grupo,$sort,$order,$limit,$offset);
                }else{
                    $this->vista->response("No existe el grupo",404);
                    return;
                }
            }else{
                //todos los equipos
                $equipos = $this->modelo->obtenerEquipos(null, $sort, $order, $limit, $offset);
                //holi
            }
        }

        //aca llega si pudo hacer la consulta

        if($equipos){
            $status = 200;
        }else{
            $status = 404;
        }
        $this->vista->response($equipos,$status);

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
    private function columnaValida($sort){
        $columnas=['pais','pp','puntos','pj','pe','gc','grupo','pg','dif','gf'];

        if(in_array($sort, $columnas)){
            return true;
        }
        return false;

    }

    private function esOrderValido($string){
        return $string == "DESC" or $string=="ASC";
    }


    private function parametrosValidos(){
        $valido = true;
        $parametrosinvalidos = [];
        foreach ($_GET as $key => $value) {
            $key = strtolower($key);
            switch(strtolower($key)){

                case "sort":{
                    if (!$this->columnaValida(strtolower($value))) {
                        array_push($parametrosinvalidos,$value);
                        $valido = false;
                    }
                    break;
                }

                case "order":{
                    if (!$this->esOrderValido(strtoupper($value)))  { //check si es asc o desc
                        array_push($parametrosinvalidos,$value);
                        $valido = false;
                    }
                    break;
                }
                case "limit":{
                    if(($value <= 0) or ($value > 10) or (!is_numeric($value))){
                        $valido=false;
                    }
                    break;
                }
                
                case "offset":{
                    if(($value < 0) or (!is_numeric($value) or !isset($_GET["limit"]))){
                        $valido=false;
                    }
                    break;
                }

                case "grupo":break;

                case "resource":break;
                default:{
                    array_push($parametrosinvalidos,$key);
                    $valido = false;
                }
               
            }
        }
        if(!$valido){
            $this->vista->response($parametrosinvalidos, 400); // devuelve clave o valor del parametro invalido
            return false;
        }
        return true;
    }
} 