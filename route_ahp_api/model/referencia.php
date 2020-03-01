<?php

require_once '../datos/conexion.php';

class referencia  extends conexion
{
    private $turno;
    private $grado;
    private $seccion;
    private $hora_entrada;
    private $hora_salida;
    private $persona_id;
    private $colegio_id;

    /**
     * @return mixed
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * @param mixed $turno
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;
    }

    /**
     * @return mixed
     */
    public function getGrado()
    {
        return $this->grado;
    }

    /**
     * @param mixed $grado
     */
    public function setGrado($grado)
    {
        $this->grado = $grado;
    }

    /**
     * @return mixed
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * @param mixed $seccion
     */
    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
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
    public function getPersonaId()
    {
        return $this->persona_id;
    }

    /**
     * @param mixed $persona_id
     */
    public function setPersonaId($persona_id)
    {
        $this->persona_id = $persona_id;
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


    public function create()
    {

        try {


            $sql = "insert into referencia_alumno (turno, grado, seccion, hora_entrada, hora_salida, persona_id, colegio_id) 
                    VALUES (:p_turno,:p_grado, :p_seccion, :p_hora_entrada, :p_hora_salida, :p_persona_id, :p_colegio_id); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_turno", $this->turno);
            $sentencia->bindParam(":p_grado", $this->grado);
            $sentencia->bindParam(":p_seccion", $this->seccion);
            $sentencia->bindParam(":p_hora_entrada", $this->hora_entrada);
            $sentencia->bindParam(":p_hora_salida", $this->hora_salida);
            $sentencia->bindParam(":p_persona_id", $this->persona_id);
            $sentencia->bindParam(":p_colegio_id", $this->colegio_id);

            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }

    }
}