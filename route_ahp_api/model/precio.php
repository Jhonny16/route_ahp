<?php

require_once '../datos/conexion.php';
class precio extends conexion
{
    private $id;
    private $costo;
    private $empresa_id;
    private $colegio_id;
    private $fecha_inicial;
    private $fecha_final;
    private $descripcion;

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
    public function getFechaFinal()
    {
        return $this->fecha_final;
    }

    /**
     * @param mixed $fecha_final
     */
    public function setFechaFinal($fecha_final)
    {
        $this->fecha_final = $fecha_final;
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
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * @param mixed $costo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;
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
    public function getColegioId()
    {
        return $this->colegio_id;
    }

    /**
     * @param mixed $colegio_id
     */
    public function setColegioId($colegio_id)
    {
        $this->colegio_id = $colegio_id;
    }

    public function lista()
    {

        try {
            $sql = "select p.id, p.costo,p.empresa_id, p.colegio_id, p.fecha_inicio, p.fecha_fin,
                    (case when p.descripcion is null then '-' else p.descripcion end) as descripcion,
                    c.nombre as colegio,per.nombre_completo as empresa, c.id as colegio_id,
                    (case when current_date  between  p.fecha_inicio and p.fecha_fin then 'Vigente' else 'No Vigente' end) as vigencia,
                    ((p.fecha_fin + cast('1 days' as interval) )::timestamp::date) as fecha_inicializacion
                    from precio p 
                    inner join persona per on p.empresa_id = per.id
                    inner join colegio c on c.id = p.colegio_id
                    where (case when :p_empresa_id = 0 then TRUE else p.empresa_id = :p_empresa_id end)
                    and   (case when :p_colegio_id = 0 then TRUE else c.id = :p_colegio_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_colegio_id", $this->colegio_id);
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


            $sql = "insert into precio (costo, empresa_id, colegio_id, fecha_inicio, fecha_fin, descripcion)
                    values (:p_costo,:p_empresa_id,:p_colegio_id,:p_fecha_inicio, :p_fecha_fin, :p_des); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_costo", $this->costo);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_colegio_id", $this->colegio_id);
            $sentencia->bindParam(":p_fecha_inicio", $this->fecha_inicial);
            $sentencia->bindParam(":p_fecha_fin", $this->fecha_final);
            $sentencia->bindParam(":p_des", $this->descripcion);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }

    }

}