<?php

    require_once "./app/modelo/ModeloEquipo.php";
    require_once "./app/modelo/ModeloGrupo.php";
    require_once "./app/vista/APIVista.php";
    require_once "./app/helpers/auth-api-helper.php";

class APIControladorGrupo{

    private $modeloGrupo;
    private $vista;
    private $authHelper;

    public function __construct(){
        $this->modeloGrupo = new ModeloGrupo();
        $this->vista = new APIVista();
        $this->authHelper = new AuthApiHelper();
    }

    public function obtenerGrupo($params=null){

        if(!$this->authHelper->isLoggedIn()){
            $this->vista->response("No estas logeado", 401);
            return;
        }
        
        if(!empty($params[":ID"])){         
            if(is_numeric($params[":ID"])){

                $grupos = $this->modeloGrupo->obtenerGrupo($params[":ID"]);

            }else{
                $this->vista->response("ID invalido",400);
                return;
            }
        }else{
            $grupos = $this->modeloGrupo->obtenerGrupo();
        }

        if($grupos){
            $status = 200;
        }else{
            $status = 404;
        }
        $this->vista->response($grupos,$status);

    }
}