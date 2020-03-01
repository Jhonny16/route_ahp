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
    private $hora_entrada;
    private $hora_llegada;

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

}