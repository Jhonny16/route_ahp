<?php


require_once '../datos/conexion.php';

class vehiculo extends conexion
{
    private $id;
    private $placa;
    private $marca;
    private $color;
    private $empresa_id;
    private $estado;

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
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
    public function getPlaca()
    {
        return $this->placa;
    }

    /**
     * @param mixed $placa
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;
    }

    /**
     * @return mixed
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
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

    public function lista($vehiculo_id)
    {

        try {
            $sql = "select v.id, v.placa, v.marca, v.color, p.nombre_completo as empresa 
                    from vehiculo v inner join persona p on p.id = v.empresa_id
                        where (case when :p_empresa_id = 0 then TRUE else v.empresa_id = :p_empresa_id end)
                        and (case when :p_vehiculo_id = 0 then TRUE else v.id = :p_vehiculo_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_vehiculo_id", $vehiculo_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function lista_asignacion($vehiculo_id, $chofer_id, $fecha_inicio, $fecha_fin)
    {
        try {
            $sql = "select
                    cv.id,
                    p.nombre_completo as chofer,
                    ('Marca: ' || v.marca || ' / Placa: ' || v.placa) as vehiculo,
                           cv.fecha_inicio,
                           cv.fecha_fin,
                           (case when cv.activo is true then 'Activo' else 'Inactivo' end) as estado
                    
                    from persona p inner join conductor_vehiculo cv on p.id = cv.persona_id
                    inner join vehiculo v on cv.vehiculo_id = v.id
                    where 
                    (case when :p_vehiculo_id = 0 then True else v.id = :p_vehiculo_id end) and 
                    (case when :p_chofer_id = 0 then True else p.id = :p_chofer_id end) 
                    and (cv.fecha_inicio between :p_fecha_inicio and :p_fecha_fin ) 
                    or (cv.fecha_fin between :p_fecha_inicio and :p_fecha_fin )";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_vehiculo_id", $vehiculo_id);
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

    public function chofer_vehiculos_serv($empresa_id)
    {
        try {
            $sql = "select
                    cv.id,
                    p.nombre_completo as chofer,
                    ('Marca: ' || v.marca || ' / Placa: ' || v.placa) as vehiculo,
                           cv.fecha_inicio,
                           cv.fecha_fin
                    
                    from persona p inner join conductor_vehiculo cv on p.id = cv.persona_id
                    inner join vehiculo v on cv.vehiculo_id = v.id
                    where v.empresa_id = :p_empresa_id and
                    (current_date between cv.fecha_inicio and cv.fecha_fin )";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $empresa_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function create()
    {
        try {


            $sql = "insert into vehiculo (placa, marca, color, empresa_id, activo) 
                                  values (:p_placa, :p_marca, :p_color, :p_empresa_id, :p_estado)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_placa", $this->placa);
            $sentencia->bindParam(":p_marca", $this->marca);
            $sentencia->bindParam(":p_color", $this->color);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function read()
    {

        try {
            $sql = "select * from vehiculo where id = :p_id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update()
    {
        $this->dblink->beginTransaction();

        try {

            $sql = "update vehiculo set 
                    placa = :p_placa,
                    marca = :p_marca,
                    color = :p_colpr,
                    activo = :p_estado
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_placa", $this->placa);
            $sentencia->bindParam(":p_marca", $this->marca);
            $sentencia->bindParam(":p_colpr", $this->color);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }


    public function create_asignacion($conductor_id, $vehiculo_id, $fecha_inicial, $fecha_final)
    {

        try {


            $sql = "insert into conductor_vehiculo (persona_id, vehiculo_id, fecha_inicio, fecha_fin)
                    values (:p_persona_id, :p_vehiculo_id, :p_fecha_inicio, :p_fecha_fin); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_persona_id", $conductor_id);
            $sentencia->bindParam(":p_vehiculo_id", $vehiculo_id);
            $sentencia->bindParam(":p_fecha_inicio", $fecha_inicial);
            $sentencia->bindParam(":p_fecha_fin", $fecha_final);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }

    }

    public function read_asignacion($id)
    {

        try {
            $sql = "select * from conductor_vehiculo where id = :p_id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function update_asignacion($vehiculo_id, $persona_id, $fecha_fin, $estado, $id)
    {
        $this->dblink->beginTransaction();

        try {

            $sql = "update conductor_vehiculo set 
                    vehiculo_id = :p_vehiculo_id,
                    persona_id = :p_persona_id,
                    fecha_fin = :p_fecha_fin,
                    activo = :p_estado where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_vehiculo_id", $vehiculo_id);
            $sentencia->bindParam(":p_persona_id", $persona_id);
            $sentencia->bindParam(":p_fecha_fin", $fecha_fin);
            $sentencia->bindParam(":p_estado", $estado);
            $sentencia->bindParam(":p_id", $id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

}