<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 01/04/20
 * Time: 10:39 AM
 */

require_once '../datos/conexion.php';


class licencia_certificado extends conexion
{
    private $id;
    private $fecha_registro;
    private $imagen;
    private $observacion;
    private $validate;
    private $empresa_id;
    private $chofer_id;
    private $vehiculo_id;
    private $descripcion;
    private $fecha_inicial;
    private $fecha_revalidacion;
    private $tipo_id;

    /**
     * @return mixed
     */
    public function getChoferId()
    {
        return $this->chofer_id;
    }

    /**
     * @param mixed $chofer_id
     */
    public function setChoferId($chofer_id)
    {
        $this->chofer_id = $chofer_id;
    }

    /**
     * @return mixed
     */
    public function getVehiculoId()
    {
        return $this->vehiculo_id;
    }

    /**
     * @param mixed $vehiculo_id
     */
    public function setVehiculoId($vehiculo_id)
    {
        $this->vehiculo_id = $vehiculo_id;
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
    public function getFechaInicial()
    {
        return $this->fecha_inicial;
    }

    /**
     * @param mixed $fecha_inicial
     */
    public function setFechaInicial($fecha_inicial)
    {
        $this->fecha_inicial = $fecha_inicial;
    }

    /**
     * @return mixed
     */
    public function getTipoId()
    {
        return $this->tipo_id;
    }

    /**
     * @param mixed $tipo_id
     */
    public function setTipoId($tipo_id)
    {
        $this->tipo_id = $tipo_id;
    }


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


    public function create()
    {

        try {
            $sql = "insert into licencias_certificados (fecha_registro, imagen, empresa_id, chofer_id, vehiculo_id,
                                  descripcion, fecha_inicio, fecha_revalidacion,tipo_id) 
                    values (current_date , :p_imagen, :p_empresa_id,:p_conductor_id, :p_vehiculo_id ,
                            :p_descripcion, :p_fecha_inicio, :p_fecha_revalidacion, :p_tipo_id )";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_imagen", $this->imagen);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_conductor_id", $this->chofer_id);
            $sentencia->bindParam(":p_vehiculo_id", $this->vehiculo_id);
            $sentencia->bindParam(":p_descripcion", $this->descripcion);
            $sentencia->bindParam(":p_fecha_inicio", $this->fecha_inicial);
            $sentencia->bindParam(":p_fecha_revalidacion", $this->fecha_revalidacion);
            $sentencia->bindParam(":p_tipo_id", $this->tipo_id);

            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function update()
    {
        $this->dblink->beginTransaction();

        try {

            $sql = "update licencias_certificados set
                    observacion = :observacion, descripcion = :p_descripcion,
                    validate = :validate
                    where empresa_id = :p_empresa_id and chofer_id = :p_chofer_id and vehiculo_id = :p_vehiculo_id 
                    and tipo_id = :p_tipo_id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":observacion", $this->observacion);
            $sentencia->bindParam(":p_descripcion", $this->descripcion);
            $sentencia->bindParam(":validate", $this->validate);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_chofer_id", $this->chofer_id);
            $sentencia->bindParam(":p_vehiculo_id", $this->vehiculo_id);
            $sentencia->bindParam(":p_tipo_id", $this->tipo_id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

//    public function create_permisos()
//    {
//        try {
//            $sql = "insert into licencias_certificados (fecha_registro, imagen,
//                      persona_id, descripcion, fecha_inicio, fecha_revalidacion, tipo_id)
//                    values (current_date , :p_imagen, :p_persona_id, :p_descripcion, :p_fecha_inicio,
//                        :p_fecha_revalidacion, :p_tipo_id)";
//            $sentencia = $this->dblink->prepare($sql);
//            $sentencia->bindParam(":p_imagen", $this->imagen);
//            $sentencia->bindParam(":p_persona_id", $this->persona_id);
//            $sentencia->bindParam(":p_descripcion", $this->descripcion);
//            $sentencia->bindParam(":p_fecha_inicio", $this->fecha_inicial);
//            $sentencia->bindParam(":p_fecha_revalidacion", $this->fecha_revalidacion);
//            $sentencia->bindParam(":p_tipo_id", $this->tipo_id);
//
//            $sentencia->execute();
//            return True;
//
//        } catch (Exception $ex) {
//            throw $ex;
//        }
//    }

    public function read()
    {
        try {
            $sql = "select *
                    from licencias_certificados 
                    where current_date <= fecha_revalidacion 
                    and chofer_id = :p_conductor_id               
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_conductor_id", $this->chofer_id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

//    public function update(){
//        $this->dblink->beginTransaction();
//
//        try {
//
//            $sql = "update licencias_certificados set
//                    observacion = :observacion,
//                    validate = :validate
//                    where id = :p_id ";
//            $sentencia = $this->dblink->prepare($sql);
//            $sentencia->bindParam(":observacion", $this->observacion);
//            $sentencia->bindParam(":validate", $this->validate);
//            $sentencia->bindParam(":p_id", $this->id);
//            $sentencia->execute();
//            $this->dblink->commit();
//
//            return true;
//
//        } catch (Exception $exc) {
//            $this->dblink->rollBack();
//            throw $exc;
//        }
//    }

    public function list_driver()
    {
        try {
            $sql = "select *
                    from licencias_certificados 
                    where chofer_id = :p_conductor_id and tipo_id = 4
                  
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_conductor_id", $this->persona_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function licencias_list()
    {
        try {
            $sql = "select *
                    from licencias_certificados 
                    where empresa_id = :p_empresa_id and tipo_id = :p_tipo_id
                    and (case when :p_chofer_id = 0 then true else chofer_id = :p_chofer_id end)
                    and (case when :p_vehiculo_id = 0 then true else vehiculo_id = :p_vehiculo_id end)
                  
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_chofer_id", $this->chofer_id);
            $sentencia->bindParam(":p_vehiculo_id", $this->vehiculo_id);
            $sentencia->bindParam(":p_tipo_id", $this->tipo_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}