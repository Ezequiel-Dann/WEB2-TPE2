<?php

require_once './app/vista/APIVista.php';
require_once './app/helpers/auth-api-helper.php';
require_once "./app/modelo/ModeloUsuario.php";

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}


class APIControladorAuth {

    private $vista;
    private $authHelper;
    private $modeloUsuario;
    private $data;

    public function __construct() {
        
        $this->vista = new APIVista();
        $this->authHelper = new AuthApiHelper();
        $this->modeloUsuario = new ModeloUsuario();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function obtenerToken($params = null) {
        // Obtener "Basic base64(user:pass)
        $basic = $this->authHelper->getAuthHeader();
        
        if(empty($basic)){
            $this->vista->response('No autorizado', 401);
            return;
        }
        $basic = explode(" ",$basic); // ["Basic" "base64(user:pass)"]
        if($basic[0]!="Basic"){
            $this->vista->response('La autenticación debe ser Basic', 401);
            return;
        }

        //validar usuario:contraseña
        $userpass = base64_decode($basic[1]); // user:pass
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $pass = $userpass[1];

        $usuario = $this->modeloUsuario->obtenerUsuario($user);

        if($usuario and password_verify($pass,($usuario->contrasenia))){
            //  crear un token
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'email' => $usuario->email,
                "admin"=> $usuario->administrador,
                'exp' => time()+3600
            );
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $signature = hash_hmac('SHA256', "$header.$payload", "Clave1234", true);
            $signature = base64url_encode($signature);
            $token = "$header.$payload.$signature";
            $this->vista->response($token,200);
        }else{
            $this->vista->response('No autorizado', 401);
        }
    }


}
