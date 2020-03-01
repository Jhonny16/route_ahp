<?php

require_once '../datos/conexion.php';


class servicio extends conexion
{


    public function servicio_detalle_aceptacion($apoderado_id)
    {

        try {
            $sql = "select
                           (case when sd.aceptacion = true then 'Aceptado' else 'No Aceptado'end) as solicitud,
                           s.code, s.fecha_registro,
                    p.nombre_completo as alumno,
                           c.nombre,
                           c.direccion,
                           ra.hora_entrada,
                           ra.hora_salida,
                           s.calificacion
                    from persona p
                             inner join referencia_alumno ra on p.id = ra.persona_id
                        inner join servicio_detalle sd on sd.referencia_id = ra.id
                        inner join servicio s on sd.servicio_id = s.id
                         inner join colegio c on ra.colegio_id = c.id
                    where p.apoderado_id = :p_apoderado_id 

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