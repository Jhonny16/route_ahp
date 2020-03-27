<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 26/03/20
 * Time: 11:27 AM
 */


require_once '../datos/conexion.php';

class conductor_licencia extends conexion
{

    private $id;
    private $fecha_registro;
    private $imagen;
    private $observacion;
    private $validate;
    private $conductor_id;
    private $descripcion;
    private $fecha_revalidacion;

    /**
     * @return mixed
     */
    public function getFechaRevalidacion()
    {
        return $this->fecha_revalidacion;
    }

    /**
     * @param mixed $fecha_revalidacion
     */
    public function setFechaRevalidacion($fecha_revalidacion)
    {
        $this->fecha_revalidacion = $fecha_revalidacion;
    }


    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }

    /**
     * @param mixed $fecha_registro
     */
    public function setFechaRegistro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
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
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @param mixed $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * @return mixed
     */
    public function getConductorId()
    {
        return $this->conductor_id;
    }

    /**
     * @param mixed $conductor_id
     */
    public function setConductorId($conductor_id)
    {
        $this->conductor_id = $conductor_id;
    }

    public function create()
    {

        try {
            $sql = "insert into conductor_licencia (fecha_registro, imagen, conductor_id, descripcion, fecha_revalidacion) 
                                  values (current_date , :p_imagen, :p_conductor_id, :p_descripcion, :p_fecha_revalidacion)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_imagen", $this->imagen);
            $sentencia->bindParam(":p_conductor_id", $this->conductor_id);
            $sentencia->bindParam(":p_descripcion", $this->descripcion);
            $sentencia->bindParam(":p_fecha_revalidacion", $this->fecha_revalidacion);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function read()
    {
        try {
            $sql = "select *
                    from conductor_licencia 
                    where current_date <= fecha_revalidacion 
                    and conductor_id = :p_conductor_id
                  
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_conductor_id", $this->conductor_id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update(){
        $this->dblink->beginTransaction();

        try {

            $sql = "update conductor_licencia set 
                    observacion = :observacion,
                    validate = :validate
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":observacion", $this->observacion);
            $sentencia->bindParam(":validate", $this->validate);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

}