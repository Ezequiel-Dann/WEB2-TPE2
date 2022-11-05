<?php
    class ModeloGrupo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=db_catar;charset=utf8', 'root', '');
        }

        public function obtenerGrupo($id = null){
            if (isset($id)){
                $sentencia = $this->db->prepare("SELECT * FROM grupos WHERE id_grupo = ?");
                $sentencia->execute([$id]);
                return $sentencia->fetch(PDO::FETCH_OBJ);
            }
            $sentencia = $this->db->prepare("SELECT * FROM grupos");
            $sentencia->execute();
            return $sentencia->fetchALL(PDO::FETCH_OBJ);
        }
        public function agregarGrupo($grupo){
            $sentencia = $this->db->prepare("INSERT INTO grupos (nombre,finalizado) VALUES (:nombre,:finalizado)");
            $sentencia->execute($grupo);
        }

        public function modificarGrupo($grupo){
            $sentencia =$this->db->prepare("UPDATE grupos SET nombre=:nombre,finalizado=:finalizado WHERE id_grupo=:id_grupo");
            $sentencia->execute($grupo);
            return $sentencia->rowcount();
        }

        public function eliminarGrupo($id){
            $grupoEliminado=$this->db->prepare("DELETE FROM grupos WHERE id_grupo=?");
            $grupoEliminado->execute([$id]);
            return $grupoEliminado->rowCount();
        }
    }
    