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


    public function create($empresa_id, $fecha_inicio, $fecha_fin)
    {

        try {


            $sql = "insert into referencia_alumno (turno, grado, seccion, hora_entrada, hora_salida, 
                    persona_id, colegio_id, fecha_inicio, fecha_fin) 
                    VALUES (:p_turno,:p_grado, :p_seccion, :p_hora_entrada, :p_hora_salida, 
                    :p_persona_id, :p_colegio_id,:p_fecha_inicio, :p_fecha_fin); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_turno", $this->turno);
            $sentencia->bindParam(":p_grado", $this->grado);
            $sentencia->bindParam(":p_seccion", $this->seccion);
            $sentencia->bindParam(":p_hora_entrada", $this->hora_entrada);
            $sentencia->bindParam(":p_hora_salida", $this->hora_salida);
            $sentencia->bindParam(":p_persona_id", $this->persona_id);
            $sentencia->bindParam(":p_colegio_id", $this->colegio_id);
            $sentencia->bindParam(":p_fecha_inicio", $fecha_inicio);
            $sentencia->bindParam(":p_fecha_fin", $fecha_fin);

            $sentencia->execute();

            $sql = "select id from referencia_alumno order by id desc limit 1 ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($sentencia->rowCount()) {
                $referencia_id = $resultado['id'];
                  $res = $this->create_solicitud($referencia_id, $empresa_id);
//                $res = $this->create_servicio($empresa_id, $fecha_inicio, $fecha_fin, $referencia_id);
                if($res){
                    return true;
                }
                else{
                    return false;
                }
            }else{
                return false;
            }

        } catch (Exception $ex) {
            throw $ex;


        }

    }

    public function create_servicio($empresa_id, $fecha_inicio, $fecha_fin, $referencia_id){
        try {

            $sql = "select secuencia from correlativo where tabla = 'servicio' ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            $secuencia = $resultado["secuencia"];
            $secuencia = $secuencia + 1;
            if (strlen($secuencia) == 1) {
                $pad = 5;
            } else {
                if (strlen($secuencia) == 2) {
                    $pad = 4;
                } else {
                    if (strlen($secuencia) == 3) {
                        $pad = 3;
                    } else {
                        if (strlen($secuencia) == 4) {
                            $pad = 2;
                        } else {
                            if (strlen($secuencia) == 5) {
                                $pad = 1;
                            }
                        }
                    }
                }
            }
            $correlativo = str_pad($secuencia, $pad, "0", STR_PAD_LEFT);
            $numeracion = "SRV-" . $correlativo;
            $code = $numeracion ;
            date_default_timezone_set("America/Lima");
            $fecha = date('Y-m-d');

            $sql = "insert into servicio (fecha_registro, empresa_id, code, fecha_inicio, fecha_fin) VALUES 
                    (:p_fecha_registro,:p_empresa_id, :p_code, :p_fecha_inicio, :p_fecha_fin); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_fecha_registro", $fecha);
            $sentencia->bindParam(":p_empresa_id", $empresa_id);
            $sentencia->bindParam(":p_code", $code);
            $sentencia->bindParam(":p_fecha_inicio", $fecha_inicio);
            $sentencia->bindParam(":p_fecha_fin", $fecha_fin);
            $sentencia->execute();


            $this->dblink->beginTransaction();
            $sql = "update correlativo set secuencia = :p_secuencia where tabla = 'servicio' ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_secuencia", $secuencia);
            $sentencia->execute();
            $this->dblink->commit();


            $sql = "select id from servicio order by id desc limit 1 ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($sentencia->rowCount()) {
                $servicio_id = $resultado['id'];

                $res = $this->create_servicio_detalle($servicio_id, $referencia_id);
                if($res){
                    return $resultado;
                }
                else{
                    return $resultado;
                }
            }


        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function create_servicio_detalle($servicio_id, $referencia_id){
        try {


            $sql = "insert into servicio_detalle (servicio_id, referencia_id) VALUES
                    (:p_servicio_id, :p_referencia_id); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_servicio_id", $servicio_id);
            $sentencia->bindParam(":p_referencia_id", $referencia_id);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }
    }

    public function create_solicitud($referencia_id, $empresa_id){
        try {


            $sql = "insert into solicitud_temporal (referencia_id, fecha, empresa_id) VALUES
                    (:p_referencia_id, current_date, :p_empresa_id); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $empresa_id);
            $sentencia->bindParam(":p_referencia_id", $referencia_id);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }
    }
}