<?php
    
    class ModeloEquipo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=db_catar;charset=utf8', 'root', '');
        }
   
        public function obtenerEquipos($id = null , $sort = null , $order = null , $limit = null , $offset = null){
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
            }//." LIMIT ". $limit . " OFFSET " .$offset

            if($limit){

                $paginado = $this->paginado($limit, $offset);

            }else{$paginado =" ";}

            $sentencia = $this->db->prepare("SELECT id_equipo,pais,puntos,pj,pg,pe,pp,gf,gc,dif,nombre as grupo FROM (equipos INNER JOIN grupos) WHERE equipos.fk_id_grupo = grupos.id_grupo " . $stringOrderBySQL . $paginado); // esto es  legal?
            $sentencia->execute();
            
            return $sentencia->fetchALL(PDO::FETCH_OBJ);
        }

        public function obtenerEquiposGrupo($grupo, $sort = null, $order = null, $limit = null, $offset = null){

            if(!$sort and $order){ // si solo viene la direccion ordena como para hacer la tabla
                $stringOrderBySQL = $this->stringOrderbyCompletoSQL($order);
            }else{ //sino segun los parametros
                $stringOrderBySQL = $sort ? " ORDER BY $sort $order" : "";
            }

            if($limit){

                $paginado = $this->paginado($limit, $offset);

            }else{$paginado =" ";}
            
            $sentencia = $this->db->prepare("SELECT id_equipo,pais,puntos,pj,pg,pe,pp,gf,gc,dif,nombre as grupo FROM (equipos INNER JOIN grupos) WHERE equipos.fk_id_grupo = grupos.id_grupo AND grupos.id_grupo = ?" . $stringOrderBySQL . " " . $paginado);
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
            return "ORDER BY puntos $order, pg $order, pj $orderOpuesto, gf $order ,dif $order";
        }
        private function paginado($limit, $offset){
            return $paginado = "LIMIT " . $limit . " OFFSET " . $offset;
        }
    }