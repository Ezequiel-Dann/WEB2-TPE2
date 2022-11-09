<?php
    
    class ModeloEquipo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=db_catar;charset=utf8', 'root', '');
        }
   
        public function obtenerEquipos($id = null , $sort = null , $order = null){
            if (isset($id)){
                //un solo equipo
                $sentencia = $this->db->prepare("SELECT id_equipo,pais,puntos,pj,pg,pe,pp,gf,gc,dif,nombre as grupo FROM (equipos INNER JOIN grupos) WHERE id_equipo = ? and (fk_id_grupo = id_grupo)");
                $sentencia->execute([$id]);
                return $sentencia->fetch(PDO::FETCH_OBJ);
            }
            //todos los equipos

            if(!$sort and $order){ // si solo viene la direccion ordena como para hacer la tabla
                $stringOrderBySQL = $this->stringOrderByCompletoSQL($order);
            }else{ //sino segun los parametros
                $stringOrderBySQL = $sort ? " ORDER BY $sort $order" : "";
            }

            $sentencia = $this->db->prepare("SELECT id_equipo,pais,puntos,pj,pg,pe,pp,gf,gc,dif,nombre as grupo FROM (equipos INNER JOIN grupos) WHERE equipos.fk_id_grupo = grupos.id_grupo " . $stringOrderBySQL); // esto es  legal?
            var_dump($stringOrderBySQL);
            var_dump($sentencia);  //TODO sacar
            $sentencia->execute();
            
            return $sentencia->fetchALL(PDO::FETCH_OBJ);
        }

        public function obtenerEquiposGrupo($grupo, $sort = null, $order = null){

            if(!$sort and $order){ // si solo viene la direccion ordena como para hacer la tabla
                $stringOrderBySQL = $this->stringOrderbyCompletoSQL($order);
            }else{ //sino segun los parametros
                $stringOrderBySQL = $sort ? " ORDER BY $sort $order" : "";
            }
            $sentencia = $this->db->prepare("SELECT id_equipo,pais,puntos,pj,pg,pe,pp,gf,gc,dif,nombre as grupo FROM (equipos INNER JOIN grupos) WHERE equipos.fk_id_grupo = grupos.id_grupo AND grupos.id_grupo = ?" . $stringOrderBySQL);
            $sentencia->execute([$grupo]);
            return $sentencia->fetchALL(PDO::FETCH_OBJ);
        }
 
        public function agregarEquipo($equipo){
            $sentencia = $this->db->prepare("INSERT INTO equipos (pais, puntos, pj, pg, pe, pp, gf, gc, dif, fk_id_grupo) VALUES (:pais,:puntos,:pj,:pg,:pe,:pp,:gf,:gc,:dif,:fk_id_grupo)");
            $sentencia->execute($equipo);
            if($sentencia->rowCount()){
                return $this->db->lastInsertId();
            }
            return false;
        }

        public function modificarEquipo($equipo){
            $sentencia =$this->db->prepare("UPDATE equipos SET pais=:pais,puntos=:puntos,pj=:pj,pg=:pg,pe=:pe,pp=:pp,gf=:gf,gc=:gc,dif=:dif,fk_id_grupo=:fk_id_grupo WHERE id_equipo=:id_equipo");
            $sentencia->execute($equipo);
            return $sentencia->rowCount();
        }

        public function eliminarEquipo($id){
            $equipoEliminado=$this->db->prepare("DELETE FROM equipos WHERE id_equipo=?");
            $equipoEliminado->execute([$id]);
            return $equipoEliminado->rowCount();
        }

        public function eliminarEquiposGrupo($idGrupo){
            $sentencia = $this->db->prepare("DELETE FROM equipos WHERE fk_id_grupo=?");
            $sentencia->execute([$idGrupo]);
        }

        private function stringOrderByCompletoSQL($order){
            $orderOpuesto = ($order == "ASC") ? "DESC": "ASC" ;
            return "ORDER BY puntos $order, pj $orderOpuesto, pg $order, gf $order ,dif $order";
        }
    }