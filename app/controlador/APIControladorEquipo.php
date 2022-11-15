<?php

    require_once "./app/modelo/ModeloEquipo.php";
    require_once "./app/modelo/ModeloGrupo.php";
    require_once "./app/vista/APIVista.php";

class APIControladorEquipo{

    private $modelo;
    private $vista;

    public function __construct(){
        $this->modelo = new ModeloEquipo();
        $this->modeloGrupo = new ModeloGrupo();
        $this->vista = new APIVista();
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


    public function nuevoEquipo(){
        $data = json_decode(file_get_contents("php://input"));
        if(!$this->verificarDatosEquipo($data)){
            $this->vista->response("Datos invalidos",400);
            return;
        }
        if(!$this->modeloGrupo->obtenerGrupo($data->grupo)){
            $this->vista->response("No existe el grupo",404);
            return;
        }
        
        var_dump($data);
        var_dump($data);
        $equipo = array(
            ":pp" => $data->pp,
            ":puntos" => $data->puntos,
            ":pj" => $data->pj,
            ":pe" => $data->pe,
            ":pais" => $data->pais,
            ":gc" => $data->gc,
            ":pg" => $data->pg,
            ":dif" => $data->dif,
            ":gf" => $data->gf,
            ":fk_id_grupo" => $data->grupo,
        );

        $agregado = $this->modelo->agregarEquipo($equipo);
        if($agregado){
            $this->vista->response($agregado,201);           //devuelve el id con el que se inserto
        }else{
            $this->vista->response("Error al agregar",500);  //se rompio la base de datos
        };


    }

    private function verificarDatosEquipo($data){
        return (
            isset($data->pp) and (!empty($data->pp) or $data->pp == "0") and is_numeric($data->pp) and
            isset($data->puntos) and (!empty($data->puntos) or $data->puntos == "0") and is_numeric($data->puntos) and
            isset($data->pj) and (!empty($data->pj) or $data->pj == "0") and is_numeric($data->pj) and
            isset($data->pe) and (!empty($data->pe) or $data->pe == "0") and is_numeric($data->pe) and
            isset($data->gc) and (!empty($data->gc) or $data->gc == "0") and is_numeric($data->gc) and
            isset($data->grupo) and (!empty($data->grupo) or $data->grupo == "0") and is_numeric($data->grupo) and
            isset($data->pg) and (!empty($data->pg) or $data->pg == "0") and is_numeric($data->pg) and
            isset($data->dif) and (!empty($data->dif) or $data->dif == "0") and is_numeric($data->dif) and
            isset($data->gf) and (!empty($data->gf) or $data->gf == "0") and is_numeric($data->gf) and
            isset($data->pais) and !empty($data->pais)
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