<?php

require_once '../datos/conexion.php';
class posicion extends conexion
{

    private $id;
    private $latitud_actual;
    private $longitud_actual;
    private $servicio_id;

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
    public function getLatitudActual()
    {
        return $this->latitud_actual;
    }

    /**
     * @param mixed $latitud_actual
     */
    public function setLatitudActual($latitud_actual)
    {
        $this->latitud_actual = $latitud_actual;
    }

    /**
     * @return mixed
     */
    public function getLongitudActual()
    {
        return $this->longitud_actual;
    }

    /**
     * @param mixed $longitud_actual
     */
    public function setLongitudActual($longitud_actual)
    {
        $this->longitud_actual = $longitud_actual;
    }

    /**
     * @return mixed
     */
    public function getServicioId()
    {
        return $this->servicio_id;
    }

    /**
     * @param mixed $servicio_id
     */
    public function setServicioId($servicio_id)
    {
        $this->servicio_id = $servicio_id;
    }



    public function create(){
        try {

            $sql = "insert into position (posicion_inicial, posicion_actual, posicion_final, servicio_id)
                    values (:p_inicial, :p_actual ,:p_final, :p_servicio_id); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_inicial", $this->p_inicial);
            $sentencia->bindParam(":p_actual", $this->p_actual);
            $sentencia->bindParam(":p_final", $this->p_final);
            $sentencia->bindParam(":p_servicio_id", $this->servicio_id);
            $sentencia->execute();
            return True;

        }catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update_position(){

        $this->dblink->beginTransaction();
        try {
            $sql = "update ruta_servicio
                    set latitud = :p_latitud, longitud= :p_longitud where fecha = current_date and
                    (servicio_detalle_id = (select sd.id from servicio_detalle sd inner join servicio s on sd.servicio_id = s.id where s.id = :p_servicio_id))";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_id", $this->servicio_id);
            $sentencia->bindParam(":p_latitud", $this->latitud_actual);
            $sentencia->bindParam(":p_longitud", $this->longitud_actual);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        }catch (Exception $ex) {
            throw $ex;
        }
    }

    public function get_position_now()
    {
        try {

            $sql = "SELECT servicio_id,lat_actual,lon_actual from position where servicio_id = :p_servicio_id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_id", $this->servicio_id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }


}