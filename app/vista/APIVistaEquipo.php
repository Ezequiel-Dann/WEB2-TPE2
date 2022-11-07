<?php

class APIVistaEquipo {

    public function __construct()
    {
        
    }

    public function response($body, $status){
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        echo json_encode($body, JSON_UNESCAPED_UNICODE);
    }

    private function _requestStatus($code){
        $status = array(
          200 => "OK",
          201 => "Created",
          400 => "bad request",
          404 => "Not found",
          500 => "Internal Server Error"
        );
        return (isset($status[$code]))? $status[$code] : $status[500];
      }
}