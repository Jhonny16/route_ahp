<?php


require_once '../datos/conexion.php';
class vehiculo extends conexion
{
    private $id;
    private $placa;
    private $marca;
    private $color;
    private $empresa_id;

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

    public function lista()
    {

        try {
            $sql = "select v.id, v.placa, v.marca, v.color, p.nombre_completo as empresa 
                    from vehiculo v inner join persona p on p.id = v.empresa_id
                        where (case when :p_empresa_id = 0 then TRUE else v.empresa_id = :p_empresa_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
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
                           cv.fecha_fin
                    
                    from persona p inner join conductor_vehiculo cv on p.id = cv.persona_id
                    inner join vehiculo v on cv.vehiculo_id = v.id
                    where 
                    (case when :p_vehiculo_id = 0 then True else v.id = :p_vehiculo_id end) and 
                    (case when :p_chofer_id = 0 then True else p.id = :p_chofer_id end) and
                    (fecha_inicio between :p_fecha_inicio and :p_fecha_fin )";
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

}