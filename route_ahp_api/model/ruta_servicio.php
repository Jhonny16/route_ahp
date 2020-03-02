<?php

require_once '../datos/conexion.php';
class ruta_servicio extends conexion
{

    private $id;
    private $fecha;
    private $latitud;
    private $longitud;
    private $observacion;
    private $conductor_vehiuclo_id;
    private $servicio_detalle_id;
    private $hora_salida;
    private $hora_llegada;

    /**
     * @return mixed
     */
    public function getHoraSalida()
    {
        return $this->hora_salida;
    }

    /**
     * @param mixed $hora_salida
     */
    public function setHoraSalida($hora_salida)
    {
        $this->hora_salida = $hora_salida;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param mixed $latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    /**
     * @return mixed
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param mixed $longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }

    /**
     * @return mixed
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * @param mixed $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * @return mixed
     */
    public function getConductorVehiucloId()
    {
        return $this->conductor_vehiuclo_id;
    }

    /**
     * @param mixed $conductor_vehiuclo_id
     */
    public function setConductorVehiucloId($conductor_vehiuclo_id)
    {
        $this->conductor_vehiuclo_id = $conductor_vehiuclo_id;
    }

    /**
     * @return mixed
     */
    public function getServicioDetalleId()
    {
        return $this->servicio_detalle_id;
    }

    /**
     * @param mixed $servicio_detalle_id
     */
    public function setServicioDetalleId($servicio_detalle_id)
    {
        $this->servicio_detalle_id = $servicio_detalle_id;
    }

    /**
     * @return mixed
     */
    public function getHoraEntrada()
    {
        return $this->hora_entrada;
    }

    /**
     * @param mixed $hora_entrada
     */
    public function setHoraEntrada($hora_entrada)
    {
        $this->hora_entrada = $hora_entrada;
    }

    /**
     * @return mixed
     */
    public function getHoraLlegada()
    {
        return $this->hora_llegada;
    }

    /**
     * @param mixed $hora_llegada
     */
    public function setHoraLlegada($hora_llegada)
    {
        $this->hora_llegada = $hora_llegada;
    }




    public function ruta_servicio_consulta()
    {

        try {
            $sql = "select
                        rs.latitud, rs.longitud, rs.fecha,
                        p.nombre_completo as chofer,
                               v.placa,
                               v.marca
                        from
                        ruta_servicio rs
                            inner join persona p on rs.coductor_vehiculo_id = p.id
                        inner join conductor_vehiculo cv on cv.persona_id = p.id
                        inner join vehiculo v on cv.vehiculo_id = v.id
                        where rs.servicio_detalle_id = :p_servicio_detalle_id and rs.fecha = :p_fecha ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_detalle_id", $this->servicio_detalle_id);
            $sentencia->bindParam(":p_fecha", $this->fecha);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function ruta_servicio_historial()
    {

        try {
            $sql = "select
                        rs.latitud, rs.longitud, rs.fecha,
                        p.nombre_completo as chofer,
                        v.placa,
                        v.marca,
                        ra.hora_entrada as hora_entrada,
                        rs.hora_llegada as hora_llegada_real,
                        ra.hora_salida as hora_salida,
                        rs.hora_salida as hora_salida_real,
                        rs.observacion
                    
                    from
                        ruta_servicio rs
                            inner join persona p on rs.coductor_vehiculo_id = p.id
                            inner join conductor_vehiculo cv on cv.persona_id = p.id
                            inner join vehiculo v on cv.vehiculo_id = v.id
                            inner join servicio_detalle sd on rs.servicio_detalle_id = sd.id
                            inner join referencia_alumno ra on sd.referencia_id = ra.id
                        where rs.servicio_detalle_id = :p_servicio_detalle_id and rs.fecha = :p_fecha ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_detalle_id", $this->servicio_detalle_id);
            $sentencia->bindParam(":p_fecha", $this->fecha);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function rutas_chofer_hoy($servicio_id, $chofer_id){

        try {
            $sql = "select
                        s.code ,
                        'SERV-D-' || sd.id as code_servicio_detalle,
                        p.nombre_completo as alumno,
                        p.direccion as direccion_alumno,
                        c.direccion as direccion_colegio,
                        c.latitud as latitud_colegio,
                        c.longitud as longitud_colegio,
                        ra.hora_entrada,
                        ra.hora_salida,
                        rs.observacion,
                        rs.estado,
                        rs.id as ruta_servicio_id

                    
                    from
                        servicio s inner join servicio_detalle sd on s.id = sd.servicio_id
                                   inner join referencia_alumno ra on sd.referencia_id = ra.id
                                   inner join ruta_servicio rs on sd.id = rs.servicio_detalle_id
                                   inner join persona p on ra.persona_id = p.id
                                   inner join colegio c on ra.colegio_id = c.id
                    where s.id = :p_servicio_id and rs.coductor_vehiculo_id = :p_chofer_id and rs.fecha = current_date ; ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_id", $servicio_id);
            $sentencia->bindParam(":p_chofer_id", $chofer_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function rutas_chofer_rango_fechas($servicio_id, $chofer_id, $fecha_inicio, $fecha_fin){

        try {
            $sql = "select
                        s.code ,
                        'SERV-D-' || sd.id as code_servicio_detalle,
                        p.nombre_completo as alumno,
                        p.direccion as direccion_alumno,
                        c.direccion as direccion_colegio,
                        c.latitud as latitud_colegio,
                        c.longitud as longitud_colegio,
                        ra.hora_entrada,
                        rs.hora_llegada as hora_llegada_real
                        ra.hora_salida,
                        rs.hora_salida as hora_salida_real
                        rs.observacion,
                        rs.estado
                    
                    from
                        servicio s inner join servicio_detalle sd on s.id = sd.servicio_id
                                   inner join referencia_alumno ra on sd.referencia_id = ra.id
                                   inner join ruta_servicio rs on sd.id = rs.servicio_detalle_id
                                   inner join persona p on ra.persona_id = p.id
                                   inner join colegio c on ra.colegio_id = c.id
                    where s.id = :p_servicio_id and rs.coductor_vehiculo_id = :p_chofer_id 
                    and  (rs.fecha between :p_fecha_inicio and :p_fecha_fin)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_id", $servicio_id);
            $sentencia->bindParam(":p_chofer_id", $chofer_id);
            $sentencia->bindParam(":p_fecha_inicio", $fecha_inicio);
            $sentencia->bindParam(":p_fecha_fin", $fecha_fin);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }

    }


    public function hora_llegada(){
        try {


            $this->dblink->beginTransaction();
            $sql = "update ruta_servicio set hora_llegada = :p_hora_llegada
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_hora_llegada", $this->hora_llegada);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;


        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function hora_salida(){
        try {


            $this->dblink->beginTransaction();
            $sql = "update ruta_servicio set hora_salida = :p_hora_salida, estado = 'F'
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_hora_salida", $this->hora_salida );
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;


        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function observacion(){
        try {


            $this->dblink->beginTransaction();
            $sql = "update ruta_servicio set observacion = :p_observacion
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_observacion", $this->observacion );
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;


        } catch (Exception $ex) {
            throw $ex;


        }
    }
}