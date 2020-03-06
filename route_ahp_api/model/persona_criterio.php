<?php

require_once '../datos/conexion.php';

class persona_criterio extends conexion
{

    public function c_calidad_servicio(){

        try{

            $sql = "select p.id, p.nombre_completo as empresa,
                           (case when SUM(rs.calificacion) > 0 then SUM(rs.calificacion) else 0 end)  as calificacion
                    from persona p left join servicio s on p.id = s.empresa_id
                    left join servicio_detalle sd on s.id = sd.servicio_id 
                    left join ruta_servicio rs on rs.servicio_detalle_id = sd.id
                    where rol_id = 2
                    group by p.id, p.nombre_completo;
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function c_precio(){

        try{

            $sql = "select p.id, p.nombre_completo as empresa, (case when SUM(p2.costo) > 0 then SUM(p2.costo) else 0 end ) as precio
                    from persona p left join precio p2 on p.id = p2.empresa_id
                    where rol_id= 2
                    group by p.id, p.nombre_completo; ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function c_puntualidad(){

        try{

            $sql = "select p.id, p.nombre_completo as empresa,
                           (case when SUM(extract(minutes from (r.hora_entrada - rs.hora_llegada)) +
                                          extract(minutes from (r.hora_salida - rs.hora_salida)))
                               is null then 0 else
                               SUM(extract(minutes from (r.hora_entrada - rs.hora_llegada)) +
                                   extract(minutes from (r.hora_salida - rs.hora_salida)))
                               end) as acumulado
                    from persona p left outer join servicio se on p.id = se.empresa_id
                    left join servicio_detalle sd on se.id = sd.servicio_id
                    left join referencia_alumno r on r.id = sd.referencia_id
                    left join ruta_servicio rs on sd.id = rs.servicio_detalle_id
                    where rol_id= 2
                    group by  p.id, p.nombre_completo;

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
                           p.nombre_completo as empresa,
                      SUM(case when pc.criterio_id=1 then pc.valor end) as criterio1,
                      SUM(case when pc.criterio_id=2 then pc.valor end) as criterio2,
                      SUM(case when pc.criterio_id=3 then pc.valor end) as criterio3,
                      SUM(case when pc.criterio_id=4 then pc.valor end) as criterio4
                    from persona_criterio pc inner join persona p on pc.persona_id = p.id
                    group by persona_id,p.nombre_completo
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