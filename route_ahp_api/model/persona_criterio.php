<?php

require_once '../datos/conexion.php';

class persona_criterio extends conexion
{

    public function c_tiempo_atencion(){

        try{

            $sql = "select p.id, (p.ap_paterno ||' '|| p.ap_materno ||' '|| p.nombres) as reciclador,
                           SUM(s.tiempo_aprox_atencion -
                           ( ((extract(hours from (hora_llegada)-(hora_respuesta))):: integer) * 60
                             + (extract(minutes from (hora_llegada)-(hora_respuesta)))::integer))  as bono
                    from servicio s inner join persona p on s.reciclador_id = p.id
                    group by p.id,p.ap_paterno ||' '|| p.ap_materno ||'' || p.nombres; ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function c_antiguedad(){

        try{

            $sql = "select p.id, (p.ap_paterno ||' '|| p.ap_materno ||' '|| p.nombres) as reciclador,
                          current_date - p.fecha_registro as antiguedad
                    from servicio s inner join persona p on s.reciclador_id = p.id
                    group by p.id,p.ap_paterno ||' '|| p.ap_materno ||'' || p.nombres; ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function c_calificacion(){

        try{

            $sql = "select p.id, (p.ap_paterno ||' '|| p.ap_materno ||' '|| p.nombres) as reciclador,
                          SUM(s.calificacion) as calificacion
                    from servicio s inner join persona p on s.reciclador_id = p.id
                    group by p.id,p.ap_paterno ||' '|| p.ap_materno ||'' || p.nombres;
                     ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function c_atencion(){

        try{

            $sql = "select p.id, (p.ap_paterno ||' '|| p.ap_materno ||' '|| p.nombres) as reciclador,
                           SUM(case when s.estado='Finalizado' then 1 else 0 end) - 
                           SUM(case when s.estado='Cancelado' then 1 else 0 end) as atencion
                    from servicio s inner join persona p on s.reciclador_id = p.id
                    group by p.id,p.ap_paterno ||' '|| p.ap_materno ||'' || p.nombres;
                     ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function create_update($persona_id, $criterio_id, $valor){
        $this->dblink->beginTransaction();

        try {
            $sql = "select persona_id , criterio_id, valor from persona_criterio
                    where persona_id = :p_personaid and criterio_id= :p_criterioid      ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_personaid", $persona_id);
            $sentencia->bindParam(":p_criterioid", $criterio_id);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($sentencia->rowCount()){

                $sql = "update persona_criterio  set valor = :p_valor  where 
                              persona_id = :p_personaid and criterio_id = :p_criterioid";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_valor", $valor);
                $sentencia->bindParam(":p_personaid", $persona_id);
                $sentencia->bindParam(":p_criterioid",  $criterio_id);
                $sentencia->execute();


            }
            else{
                $sql = "insert into persona_criterio(persona_id,criterio_id,valor) 
                        values(:p_personaid, :p_criterioid, :p_valor)";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_personaid", $persona_id);
                $sentencia->bindParam(":p_criterioid", $criterio_id);
                $sentencia->bindParam(":p_valor", $valor);
                $sentencia->execute();
            }
            $this->dblink->commit();
            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

    public function datos_normalizados()
    {

        try {
            $sql = "select pc.persona_id,
                           (p.ap_paterno||' '|| p.ap_materno ||' '|| p.nombres) as reciclador,
                      SUM(case when pc.criterio_id=1 then pc.valor end) as criterio1,
                      SUM(case when pc.criterio_id=2 then pc.valor end) as criterio2,
                      SUM(case when pc.criterio_id=3 then pc.valor end) as criterio3,
                      SUM(case when pc.criterio_id=4 then pc.valor end) as criterio4
                    from persona_criterio pc inner join persona p on pc.persona_id = p.id
                    group by persona_id,p.ap_paterno||' '|| p.ap_materno ||' '|| p.nombres
                    order by persona_id asc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function add_value($persona_id, $valor){
        $this->dblink->beginTransaction();

        try {

            $sql = "update persona set valor = :p_valor where id  = :p_persona_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_valor", $valor);
            $sentencia->bindParam(":p_persona_id", $persona_id);
            $sentencia->execute();
            $this->dblink->commit();
            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }

    }

}