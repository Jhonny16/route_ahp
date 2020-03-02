<?php

require_once '../datos/conexion.php';


class servicio extends conexion
{


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

}