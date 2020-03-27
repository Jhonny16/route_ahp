<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 24/03/20
 * Time: 08:27 AM
 */
require_once '../model/vehiculo.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$operation = json_decode(file_get_contents("php://input"))->operation;
if ($operation != 'Nuevo') {
    $id = json_decode(file_get_contents("php://input"))->id;

}

$conductor_id = json_decode(file_get_contents("php://input"))->conductor_id;
$vehiculo_id = json_decode(file_get_contents("php://input"))->vehiculo_id;
$fecha_inicial = json_decode(file_get_contents("php://input"))->fecha_inicial;
$fecha_final = json_decode(file_get_contents("php://input"))->fecha_final;
$activo = json_decode(file_get_contents("php://input"))->activo;


try {

    if ($operation == 'Nuevo') {
        $obj = new vehiculo();
        $result = $obj->create_asignacion($conductor_id,$vehiculo_id,$fecha_inicial,$fecha_final);
        if ($result) {
            Funciones::imprimeJSON(200, "Agregado Correcto", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de agregar", "");
        }
    }else{
        $obj = new vehiculo();
        $result = $obj->update_asignacion($vehiculo_id, $conductor_id, $fecha_final, $activo, $id);
        if ($result) {
            Funciones::imprimeJSON(200, "Se actualizó correctamente", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de actualizar", $activo);
        }
    }



} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), $activo);
}
