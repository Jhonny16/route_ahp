<?php

require_once '../datos/conexion.php';

class colegio extends conexion
{
    private $id;
    private $numero;
    private $nombre;
    private $direccion;
    private $latitud;
    private $longitud;
    private $director;

    /**
     * @return mixed
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param mixed $latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    /**
     * @return mixed
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param mixed $longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
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
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
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
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @param mixed $director
     */
    public function setDirector($director)
    {
        $this->director = $director;
    }

    public function lista()
    {

        try {
            $sql = "select * from colegio  where (case when :p_id = 0 then TRUE else id = :p_id end)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_id", $this->id);
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


            $sql = "insert into colegio (numero, nombre, direccion, latitud, longitud, director_nombre)
                    values (:p_numero,:p_nombre,:p_direccion,:p_latitud,:p_longitud,:p_director_nombre); ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_numero", $this->numero);
            $sentencia->bindParam(":p_nombre", $this->nombre);
            $sentencia->bindParam(":p_direccion", $this->direccion);
            $sentencia->bindParam(":p_latitud", $this->latitud);
            $sentencia->bindParam(":p_longitud", $this->longitud);
            $sentencia->bindParam(":p_director_nombre", $this->director);

            $sentencia->execute();
            return True;

        } catch (Exception $ex) {
            throw $ex;


        }

    }


}