<?php

require_once '../datos/conexion.php';

class solicitud extends conexion
{

    private $id;
    private $empresa_id;
    private $referencia_id;
    private $estado;
    private $fecha;

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
    public function getReferenciaId()
    {
        return $this->referencia_id;
    }

    /**
     * @param mixed $referencia_id
     */
    public function setReferenciaId($referencia_id)
    {
        $this->referencia_id = $referencia_id;
    }

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





    public function lista_solicitudes_por_empresa($parametro, $fecha_inicio, $fecha_fin){
        try {
            $sql = "select
                           st.id as numero_solcitud,
                    'SOL-'|| st.id as solicitud,
                    a.nombre_completo as apoderado,
                    c.nombre as colegio,
                    st.fecha as fecha_solicitud,
                           st.estado
                    from referencia_alumno ra inner join solicitud_temporal st on ra.id = st.referencia_id
                    inner join persona e on st.empresa_id = e.id
                    inner join persona p on p.id = ra.persona_id
                    inner join persona a on p.apoderado_id = a.id
                    inner join colegio c on ra.colegio_id = c.id
                    where st.empresa_id = :p_empresa_id and estado in ('Nuevo','Rechazada')
                    (case when :p_parametro= 0 then True else st.fecha between :p_fecha_inicio and :p_fecha_fin end)
                    ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa_id);
            $sentencia->bindParam(":p_parametro", $parametro);
            $sentencia->bindParam(":p_fecha_inicio", $fecha_inicio);
            $sentencia->bindParam(":p_fecha_fin", $fecha_fin);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function update()
    {
        $this->dblink->beginTransaction();

        try {

            $sql = "update solicitud_temporal set 
                    estado = :p_estado                    
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
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
}