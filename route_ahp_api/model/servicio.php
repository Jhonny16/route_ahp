<?php

require_once '../datos/conexion.php';


class servicio extends conexion
{
    private $ruta_servicio_id;
    private $calificacion;
    private $referencia_id;
    private $empresa_id;

    /**
     * @return mixed
     */
    public function getReferenciaId()
    {
        return $this->referencia_id;
    }

    /**
     * @param mixed $referencia_id
     */
    public function setReferenciaId($referencia_id)
    {
        $this->referencia_id = $referencia_id;
    }

    /**
     * @return mixed
     */
    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    /**
     * @param mixed $empresa_id
     */
    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }


    /**
     * @return mixed
     */
    public function getRutaServicioId()
    {
        return $this->ruta_servicio_id;
    }

    /**
     * @param mixed $ruta_servicio_id
     */
    public function setRutaServicioId($ruta_servicio_id)
    {
        $this->ruta_servicio_id = $ruta_servicio_id;
    }

    /**
     * @return mixed
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * @param mixed $calificacion
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;
    }


    public function servicio_detalle_aceptacion($apoderado_id)
    {

        try {
            $sql = "select
                        (case when ra.aceptacion = true then 'Aceptado' else 'No Aceptado'end) as solicitud,
                        e.nombre_completo as empresa,
                        s.code, s.fecha_registro,
                        ra.fecha_inicio,
                        ra.fecha_fin,
                        p.nombre_completo as alumno,
                        c.nombre as colegio,
                        c.direccion as colegio_direccion,
                        ra.hora_entrada,
                        ra.hora_salida,
                        sd.id as servicio_detalle_id,
                        (case when (current_date between ra.fecha_inicio and ra.fecha_fin) then 'Vigente' else 'No vigente' end)
                                                                                               as vigencia
                    from persona p
                             inner join referencia_alumno ra on p.id = ra.persona_id
                             inner join servicio_detalle sd on sd.referencia_id = ra.id
                             inner join servicio s on sd.servicio_id = s.id
                             inner join colegio c on ra.colegio_id = c.id
                             inner join persona e on s.empresa_id =e.id
                    where p.apoderado_id = :p_apoderado_id order by s.id desc
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_apoderado_id", $apoderado_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function lista_servicios_chofer($chofer_id)
    {
        try {
            $sql = "select
                        s.code, s.id as servicio_id,
                               (case when current_date between  MIN(ra.fecha_inicio) and
                                   MAX(ra.fecha_fin) then 'Vigente' else 'No vigente'end) as vigencia
                        from   servicio s inner join servicio_detalle sd on s.id = sd.servicio_id
                        inner join referencia_alumno ra on sd.referencia_id = ra.id
                        inner join persona e on s.empresa_id = e.id
                        inner join vehiculo v on e.id = v.empresa_id
                        inner join ruta_servicio rs on sd.id = rs.servicio_detalle_id
                        where rs.coductor_vehiculo_id = :p_chofer_id and ra.aceptado = True
                        group by s.code, s.id
                        order by s.id desc;
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_chofer_id", $chofer_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update_calificacion()
    {

        $this->dblink->beginTransaction();
        try {
            $sql = "update ruta_servicio set calificacion = :p_calificacion
                    where :p_id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $this->ruta_servicio_id);
            $sentencia->bindParam(":p_calificacion", $this->calificacion);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function create_servicio($conducto_vehiculo_id)
    {
        try {

            $sql = "select secuencia from correlativo where tabla = 'servicio' ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            $secuencia = $resultado["secuencia"];
            $secuencia = $secuencia + 1;
            if (strlen($secuencia) == 1) {
                $pad = 5;
            } else {
                if (strlen($secuencia) == 2) {
                    $pad = 4;
                } else {
                    if (strlen($secuencia) == 3) {
                        $pad = 3;
                    } else {
                        if (strlen($secuencia) == 4) {
                            $pad = 2;
                        } else {
                            if (strlen($secuencia) == 5) {
                                $pad = 1;
                            }
                        }
                    }
                }
            }
            $correlativo = str_pad($secuencia, $pad, "0", STR_PAD_LEFT);
            $numeracion = "SRV-" . $correlativo;
            $code = $numeracion;

            $datosDetalle = json_decode($this->referencia_id);

            $empresa_id = $this->empresa_id;

            $sql = "insert into servicio (fecha_registro, empresa_id, code) VALUES 
                    (current_date ,:p_empresa_id, :p_code); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $empresa_id);
            $sentencia->bindParam(":p_code", $code);
            $sentencia->execute();


            $this->dblink->beginTransaction();
            $sql = "update correlativo set secuencia = :p_secuencia where tabla = 'servicio' ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_secuencia", $secuencia);
            $sentencia->execute();
            $this->dblink->commit();


            $sql = "select id from servicio order by id desc limit 1 ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($sentencia->rowCount()) {
                $servicio_id = $resultado['id'];
                $res = $this->create_servicio_detalle($servicio_id, $datosDetalle,$conducto_vehiculo_id);
                if ($res) {
                    return true;
                } else {
                    return false;
                }
            }

        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function create_servicio_detalle($servicio_id, $datosDetalle,$conducto_vehiculo_id)
    {
        try {


            foreach ($datosDetalle as $key => $value) {

                $sql = "insert into servicio_detalle (servicio_id, referencia_id) VALUES 
                    (:p_servicio_id, :p_referencia_id); ";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_servicio_id", $servicio_id);
                $sentencia->bindParam(":p_referencia_id", $value->referencia_id);
                $sentencia->execute();

                $sql = "select id from servicio_detalle order by id desc limit 1 ";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($sentencia->rowCount()) {
                    $servicio_detalle_id = $resultado['id'];

                    $sql = "select
                            fecha_inicio, fecha_fin,
                                   fecha_fin - fecha_inicio as dias
                            from referencia_alumno
                            where id = :p_ref_id ";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_referencia_id", $value->referencia_id);
                    $sentencia->execute();
                    $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
                    if ($sentencia->rowCount()) {
                        $fecha_inicio = $resultado['fecha_inicio'];
                        $fecha_fin = $resultado['fecha_fin'];
                        $dias = $resultado['dias'];

                        $j = 6;
                        for ($i = 0; $i <= $dias; $i++) {
                            $alerta_calificacion = false;
                            if ($i == $j){
                                $alerta_calificacion = true;
                                $j = $j + 6;
                            }

                            $sql = "insert into ruta_servicio (fecha, conductor_vehiculo_id,servicio_detalle_id,alerta_calificacion) VALUES 
                                    ((CAST($fecha_inicio AS DATE) + CAST(''+ i +' days' AS INTERVAL), :p_cv_id, :p_serdet_id, :p_alerta_calificacion); ";
                            $sentencia = $this->dblink->prepare($sql);
                            $sentencia->bindParam(":p_cv_id", $conducto_vehiculo_id);
                            $sentencia->bindParam(":p_serdet_id", $servicio_detalle_id);
                            $sentencia->bindParam(":p_alerta_calificacion", $alerta_calificacion);
                            $sentencia->execute();


                        }
                        return true;

                    }else{
                        return false;
                    }
                }
                else{
                    return false;
                }


            }



        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function servicios_lista_por_empresa()
    {
        try {
            $sql = "select
                           s.code,
                            count(d.referencia_id) as numero_alumnos,
                           (case when
                                     (select count(*) from servicio_detalle sd
                                                               inner join ruta_servicio rs on sd.id = rs.servicio_detalle_id
                                      where rs.estado = 'P' and sd.servicio_id = s.id) > 0 then 'En proceso' else 'Finiquitado'
                               end) as state_service
                    from servicio as s left join servicio_detalle d on s.id = d.servicio_id
                    where s.empresa_id = :p_empresa_id
                    group by s.code,s.id
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}