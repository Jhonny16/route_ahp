<?php


require_once '../datos/conexion.php';

class usuario extends conexion
{
    private $id;
    private $tipo;
    private $nombre;
    private $dni;
    private $clave;
    private $estado;
    private $rol;
    private $codigo;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }



    /**
     * @return mixed
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param mixed $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
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
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return mixed
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * @param mixed $clave
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
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


    public function auth()
    {
        try {
//            if($this->rol == 2){
//                $sql = "select
//                        p.rol_id,
//                        r.descripcion as rol,
//                        p.id,
//                        p.ap_paterno || ' ' || p.ap_materno ||' ' || p.nombres as persona,
//                        p.dni,
//                        p.celular,
//                        p.correo,
//                        p.direccion,
//                        p.fecha_nac,
//                        z.nombre,
//                        p.password,
//                        p.estado,
//                        p.codigo
//                        from
//                        persona p inner join rol r on p.rol_id = r.id
//                        inner join zona z on p.zona_id = z.id
//                        where p.codigo = :p_codigo";
//                $sentencia = $this->dblink->prepare($sql);
//                $sentencia->bindParam(":p_codigo", $this->codigo);
//                $sentencia->execute();
//                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
//                if ($sentencia->rowCount()) {
//                    $activo = $resultado["estado"];
//                    $clave = $resultado["password"];
////                return $resultado;
//                    if ($activo == 'A') {
//                        if (password_verify($this->clave, $clave)) {
//                            return $resultado;
//                        } else {
//                            return 0;
//                        }
//                    } else {
//                        return -1;
//                    }
//                }else {
//                    return -2;
//                }
//
//            }else{
                $sql = "select
                        p.rol_id,
                        r.nombre as rol,
                        p.id,
                        p.nombre_completo as persona,
                        p.documento_identidad,
                        p.celular,
                        p.direccion,
                        p.fecha_nacimiento,
                        p.password,
                        p.estado
                        from
                        persona p inner join rol r on p.rol_id = r.id
                        where p.documento_identidad = :p_dni";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_dni", $this->dni);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
                if ($sentencia->rowCount()) {
                    $activo = $resultado["estado"];
                    $clave = $resultado["password"];
                    if ($activo == 'A') {
                        if (password_verify($this->clave, $clave)) {
                            return $resultado;
                        } else {
                            return 0;
                        }
                    } else {
                        return -1;
                    }
                }else{
                    return -2;
                }

//            }

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function create()
    {
        try {
            $clave = password_hash($this->clave, PASSWORD_DEFAULT);

            $sql = "insert into usuario (tipo_usuario, nombre_usuario, dni, clave, estado) 
                    values (:p_tipousuario, :p_nombre, :p_dni, :p_clave, :p_estado) ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_tipousuario", $this->tipo);
            $sentencia->bindParam(":p_nombre", $this->nombre);
            $sentencia->bindParam(":p_dni", $this->dni);
            $sentencia->bindParam(":p_clave", $clave);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->execute();

            $sql = "SELECT id FROM usuario order by 1 desc limit 1";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($sentencia->rowCount()) {
                $user_id = $resultado["id"];
            }
            return $user_id;

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update_estado()
    {
        $this->dblink->beginTransaction();
        try {
            $sql = "UPDATE usuario SET estado = :p_estado, nombre_usuario = :p_name, dni = :p_dni
                    WHERE id = :p_id";

            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->bindParam(":p_name", $this->nombre);
            $sentencia->bindParam(":p_dni", $this->dni);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();
            return true;

        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }
    public function update()
    {
        $this->dblink->beginTransaction();
        try {
            $clave = password_hash($this->clave, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET dni = :p_dni, tipo_usuario = :p_rol, clave = :p_clave, estado = :p_estado
                    WHERE id = :p_id";

            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_dni", $this->dni);
            $sentencia->bindParam(":p_rol", $this->tipo);
            $sentencia->bindParam(":p_clave", $clave);
            $sentencia->bindParam(":p_estado", $this->estado);
            $sentencia->bindParam(":p_id", $this->id);
            $sentencia->execute();
            $this->dblink->commit();
            return true;

        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }

    public function list_usuarios()
    {
        try {

            $sql = "select u.*, r.nombre as rol from usuario u inner join rol r on u.tipo_usuario = r.id";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }


    }

    public function read($id)
    {
        try {
            $sql = "SELECT u.id, u.nombre_usuario, u.dni, u.estado, r.nombre, u.tipo_usuario
                    FROM usuario u INNER JOIN rol r on u.tipo_usuario = r.id
                    WHERE u.id = " . $id ;
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function validar_password(){

        $sql = "select * from usuario 
                    where id = :p_id";
        $sentencia = $this->dblink->prepare($sql);
        $sentencia->bindParam(":p_id", $this->id);
        $sentencia->execute();
        $resultado = $sentencia->fetch();
        if ($sentencia->rowCount()) {
            $contrasenia = $resultado["clave"];
            if (password_verify($this->clave, $contrasenia)) {
                return 1;
            }
            else{
                return 0;
            }
        }
        return -1;
    }







}