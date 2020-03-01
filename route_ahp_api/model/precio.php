<?php

require_once '../datos/conexion.php';
class precio extends conexion
{
    private $id;
    private $costo;
    private $empresa_id;
    private $colegio_id;

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
            $sql = "select p.*,c.nombre as colegio,per.nombre_completo as empresa from precio p 
                    inner join persona per on p.empresa_id = per.id
                    inner join colegio c on c.id = p.colegio_id
                    where (case when :p_empresa_id = 0 then TRUE else p.empresa_id = :p_empresa_id end)
                    and   (case when :p_colegio_id = 0 then TRUE else p.colegio_id = :p_colegio_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_colegio_id", $this->empresa_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}