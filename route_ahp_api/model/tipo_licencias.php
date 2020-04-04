<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 02/04/20
 * Time: 04:40 PM
 */
require_once '../datos/conexion.php';
class tipo_licencias extends conexion
{
    public function lista()
    {
        try {

            $sql = "SELECT * from tipos_permisos";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;

        } catch (Exception $ex) {
            throw $ex;
        }
    }
}