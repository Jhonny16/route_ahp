<?php

require_once '../datos/conexion.php';

class persona extends conexion
{

    private $id;
    private $nombre_completo;
    private $documento_identidad;
    private $fecha_registro;
    private $celular;
    private $direccion;
    private $estado;
    private $fecha_nacimiento;
    private $sexo;
    private $apoderado_id;
    private $usuario;
    private $rol_id;
    private $documentos;
    private $empresa;
    private $password;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }



  /**
   * @return mixed
   */
  public function getEmpresa()
  {
    return $this->empresa;
  }

  /**
   * @param mixed $empresa
   */
  public function setEmpresa($empresa)
  {
    $this->empresa = $empresa;
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
  public function getNombreCompleto()
  {
    return $this->nombre_completo;
  }

  /**
   * @param mixed $nombre_completo
   */
  public function setNombreCompleto($nombre_completo)
  {
    $this->nombre_completo = $nombre_completo;
  }

  /**
   * @return mixed
   */
  public function getDocumentoIdentidad()
  {
    return $this->documento_identidad;
  }

  /**
   * @param mixed $documento_identidad
   */
  public function setDocumentoIdentidad($documento_identidad)
  {
    $this->documento_identidad = $documento_identidad;
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
  public function getCelular()
  {
    return $this->celular;
  }

  /**
   * @param mixed $celular
   */
  public function setCelular($celular)
  {
    $this->celular = $celular;
  }

  /**
   * @return mixed
   */
  public function getDireccion()
  {
    return $this->direccion;
  }

  /**
   * @param mixed $direccion
   */
  public function setDireccion($direccion)
  {
    $this->direccion = $direccion;
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
  public function getFechaNacimiento()
  {
    return $this->fecha_nacimiento;
  }

  /**
   * @param mixed $fecha_nacimiento
   */
  public function setFechaNacimiento($fecha_nacimiento)
  {
    $this->fecha_nacimiento = $fecha_nacimiento;
  }

  /**
   * @return mixed
   */
  public function getSexo()
  {
    return $this->sexo;
  }

  /**
   * @param mixed $sexo
   */
  public function setSexo($sexo)
  {
    $this->sexo = $sexo;
  }

  /**
   * @return mixed
   */
  public function getApoderadoId()
  {
    return $this->apoderado_id;
  }

  /**
   * @param mixed $apoderado_id
   */
  public function setApoderadoId($apoderado_id)
  {
    $this->apoderado_id = $apoderado_id;
  }

  /**
   * @return mixed
   */
  public function getUsuario()
  {
    return $this->usuario;
  }

  /**
   * @param mixed $usuario
   */
  public function setUsuario($usuario)
  {
    $this->usuario = $usuario;
  }

  /**
   * @return mixed
   */
  public function getRolId()
  {
    return $this->rol_id;
  }

  /**
   * @param mixed $rol_id
   */
  public function setRolId($rol_id)
  {
    $this->rol_id = $rol_id;
  }

  /**
   * @return mixed
   */
  public function getDocumentos()
  {
    return $this->documentos;
  }

  /**
   * @param mixed $documentos
   */
  public function setDocumentos($documentos)
  {
    $this->documentos = $documentos;
  }



    public function create()
    {

        try {


            $sql = "insert into persona (nombre_completo, documento_identidad, fecha_registro, celular,
                    direccion, estado, fecha_nacimiento, sexo, apoderado_id, usuario, rol_id, empresa_id)
                    values (:p_nombre_completo, :p_documento ,:p_fecha_registro, :p_celular, :p_direccion,
                     :p_estado, :p_fecha_nacimiento, :p_sexo,
                    :p_apoderado_id,:p_usuario,:p_rol, :p_empresa); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_nombre_completo", $this->nombre_completo);
            $sentencia->bindParam(":p_documento", $this->documento_identidad);
            $sentencia->bindParam(":p_fecha_registro", $this->fecha_registro);
            $sentencia->bindParam(":p_celular", $this->celular);
            $sentencia->bindParam(":p_direccion", $this->direccion);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->bindParam(":p_fecha_nacimiento", $this->fecha_nacimiento);
            $sentencia->bindParam(":p_sexo", $this->sexo);
            $sentencia->bindParam(":p_apoderado_id", $this->apoderado_id);
            $sentencia->bindParam(":p_usuario", $this->usuario);
            $sentencia->bindParam(":p_rol", $this->rol_id);
            $sentencia->bindParam(":p_empresa", $this->empresa);
            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function read()
    {

        try {
            $sql = "select * from persona where id = :p_id";
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

            $sql = "update persona set 
                    nombre_completo = :p_nc,
                    documento_identidad = :p_doc,
                    celular = :p_celular,
                    direccion = :p_direccion,
                    sexo = :p_sexo,
                    fecha_nacimiento = :p_fn
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_nc", $this->nombre_completo);
            $sentencia->bindParam(":p_doc", $this->documento_identidad);
            $sentencia->bindParam(":p_celular", $this->celular);
            $sentencia->bindParam(":p_direccion", $this->direccion);
            $sentencia->bindParam(":p_sexo", $this->sexo);
            $sentencia->bindParam(":p_fn", $this->fecha_nacimiento);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

    public function update_password()
    {
        $this->dblink->beginTransaction();

        try {

            $password = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "update persona set 
                    password = :p_password
                    where id = :p_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_password", $password);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();

            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

    public function empresas_list()
    {

        try {
            $sql = "select *, (case when estado ='A' then 'Activo' else 'No Activo' end) ,
                    (valor * 100) as porcentaje
                    from persona where rol_id = 2";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function empresas_list_por_colegio($colegio_id)
    {

        try {
            $sql = "select p.id, p.nombre_completo as empresa from
                    persona p inner join  precio p2 on p.id = p2.empresa_id inner join colegio c on p2.colegio_id = c.id
                    where p.rol_id = 2 and p2.colegio_id = :p_colegio_id ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_colegio_id", $colegio_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function choferes_list($chofer_id)
    {

        try {
            $sql = "select p.*, (case when p.estado ='A' then 'Activo' else 'No Activo' end),
                    (select validate from licencias_certificados where chofer_id = p.id order by id desc limit 1 )
                    as validate
                    from persona as p where rol_id = 3 
                    and (case when :p_empresa_id = 0 then TRUE else empresa_id = :p_empresa_id end)
                    and (case when :p_chofer_id = 0 then TRUE else id = :p_chofer_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_empresa_id", $this->empresa);
            $sentencia->bindParam(":p_chofer_id", $chofer_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function apoderados_list()
    {

        try {
            $sql = "select *, (case when estado ='A' then 'Activo' else 'No Activo' end) as estado
                    from persona where (case when :p_id = 0 then TRUE else id = :p_id end) and rol_id=4";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function vehiculos_por_chofer($chofer_id)
    {

        try {
            $sql = "select
                        p.nombre_completo as chofer,
                        v.marca, v.placa,v.color,
                           cv.fecha_inicio, cv.fecha_fin,
                           v.activo as vehiculo_activo
                    from vehiculo v inner join conductor_vehiculo cv on v.id = cv.vehiculo_id
                    inner join persona p on cv.persona_id = p.id
                    where p.id = :p_chofer_id  ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_chofer_id", $chofer_id);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

        public function alumnos_list()
        {

            try {
                $sql = "select *, (case when estado ='A' then 'Activo' else 'No Activo' end) 
                    from persona where rol_id = 5 and (case when :p_apoderado_id = 0 then TRUE else apoderado_id = :p_apoderado_id end)";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_apoderado_id", $this->apoderado_id);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            } catch (Exception $ex) {
                throw $ex;
            }
        }




    public function update_perfil($cambio)
    {
        $this->dblink->beginTransaction();

        try {

            if ($cambio == 1) {
                $clave = password_hash($this->clave, PASSWORD_DEFAULT);

                $sql = "update persona set 
                    dni = :p_dni,
                    nombres = :p_nombres,
                    ap_materno = :p_mateno,
                    ap_paterno = :p_paterno,
                    sexo = :p_sexo,
                    fecha_nac = :p_fc,
                    celular = :p_celular,
                    direccion = :p_direccion,
                    correo = :p_correo,
                    password = :p_password
                    where id = :p_persona_id ";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_dni", $this->dni);
                $sentencia->bindParam(":p_nombres", $this->nombres);
                $sentencia->bindParam(":p_mateno", $this->ap_materno);
                $sentencia->bindParam(":p_paterno", $this->ap_paterno);
                $sentencia->bindParam(":p_sexo", $this->sexo);
                $sentencia->bindParam(":p_fc", $this->fn);
                $sentencia->bindParam(":p_celular", $this->celular);
                $sentencia->bindParam(":p_direccion", $this->direccion);
                $sentencia->bindParam(":p_correo", $this->correo);
                $sentencia->bindParam(":p_password", $clave);
                $sentencia->bindParam(":p_persona_id", $this->id);

            } else {

                $sql = "update persona set 
                    dni = :p_dni,
                    nombres = :p_nombres,
                    ap_materno = :p_mateno,
                    ap_paterno = :p_paterno,
                    sexo = :p_sexo,
                    fecha_nac = :p_fc,
                    celular = :p_celular,
                    direccion = :p_direccion,
                    correo = :p_correo
                    where id = :p_persona_id ";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_dni", $this->dni);
                $sentencia->bindParam(":p_nombres", $this->nombres);
                $sentencia->bindParam(":p_mateno", $this->ap_materno);
                $sentencia->bindParam(":p_paterno", $this->ap_paterno);
                $sentencia->bindParam(":p_sexo", $this->sexo);
                $sentencia->bindParam(":p_fc", $this->fn);
                $sentencia->bindParam(":p_celular", $this->celular);
                $sentencia->bindParam(":p_direccion", $this->direccion);
                $sentencia->bindParam(":p_correo", $this->correo);
                $sentencia->bindParam(":p_persona_id", $this->id);

            }

            $sentencia->execute();
            $this->dblink->commit();
            return true;

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }


    }

    public function perfil(){
        try {

            $sql = "SELECT * from persona where id = :p_id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function reporte_empresas_priorizadas(){
        try {

            $sql = "select
                        pe.id,
                               pe.nombre_completo,
                        (select valor * 100 from persona_criterio where criterio_id = 1 and persona_id = pe.id) as calidad_servicio,
                        (select valor * 100 from persona_criterio where criterio_id = 2 and persona_id = pe.id) as precio,
                        (select valor * 100 from persona_criterio where criterio_id = 3 and persona_id = pe.id) as puntualidad,
                        (select valor * 100 from persona_criterio where criterio_id = 4 and persona_id = pe.id) as antiguedad_vehicular,
                        pe.valor*100 valor_empresa ,
                         (case
                              when (select validate from licencias_certificados
                                    where tipo_id = 2 and empresa_id = pe.id and (current_date between fecha_inicio and fecha_revalidacion)) is null
                              then FALSE
                              when (select validate from licencias_certificados
                                    where tipo_id = 2 and empresa_id = pe.id and (current_date between fecha_inicio and fecha_revalidacion)) = false
                                then FALSE
                              else TRUE
                              end)
                             as cedula_autorizacion,
                        (case
                          when  (select count(id) from vehiculo where empresa_id = pe.id) = 0 then 0 else
                            ((select count(validate) from licencias_certificados where tipo_id = 2 and empresa_id = pe.id
                              order by 1 desc limit 1) * 100  /  (select count(id) from vehiculo where empresa_id = pe.id) )
                          end)
                               as tarjeta_unica_circulacion,
                        (case
                           when  count(c.id) = 0 then 0 else
                            ((select count(validate) from licencias_certificados where tipo_id = 3 and empresa_id = pe.id
                              order by 1 desc limit 1) * 100  /  count(c.id) )
                          end) as credencial_conductor,
                        (case
                           when  count(c.id) = 0 then 0 else
                            ((select count(validate) from licencias_certificados where tipo_id = 4 and empresa_id = pe.id
                              order by 1 desc limit 1) * 100  /  count(c.id) )
                          end) as brevete_conductor
                        from
                        persona pe left join persona c on pe.id = c.empresa_id
                        where pe.rol_id = 2
                        group by pe.id
                        order by pe.valor desc ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }


}
